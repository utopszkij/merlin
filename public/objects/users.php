<?php
/**
 * users object
 */
include_once __DIR__.'/api.php';

use RATWEB\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
// require __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../vendor/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__.'/../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__.'/../../vendor/phpmailer/phpmailer/src/SMTP.php';
require_once __DIR__.'/../../vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';

enum UserStatus: string {
    case Active = 'active';
    case Disabled = 'disabled';
    case NotActivated = 'notActivetd';
}

enum MemberStatus: string {
    case Active = 'active';
    case Closed = 'closed';
    case Proposal = 'proposal';
    case Applicant = 'applicant';
}

class UserRecord extends \RATWEB\DB\Record {
    public int $id;
    public string $username;
    public string $email;
    public string $password;
    public string $realname;
    public string $avatar;
    public UserStatus $status;
    public int $email_verifyed;
    public int $two_factor; 
    public string $two_factor_secret;
    public int $error_count;
    public int $lock_time;
}

class ProfileResult extends \RATWEB\DB\Record {
    public bool $ok = false;
    public $errorMsg = '';
    public int $id = 0;
    public string $username = '';
    public string $email = '';
    public string $realname = '';
    public string $avatar = '';
    public UserStatus $status = UserStatus::Active->value;
    public int $email_verifyed = 0;
    public int $two_factor = 0; 
    public string $two_factor_secret = '';
    public array $groups = []; // [{id,rank,name},..]
    public array $allGroups = []; // [{id,name},...]
}

class MemberRecord extends \RATWEB\DB\Record {
    public int $id;
    public int $users_id;
    public int $groups_id;
    public string $rank;
    public MemberStatus $status;
}

class IplockRecord extends \RATWEB\DB\Record {
    public int $id = 0;
    public string $ip = '';
    public int $error_count = 0;
    public int $lock_time = 0;
}

class DologinResult {
    public bool $ok = false;
    public string $errorMsg = '';
    public int $id = 0;
    public string $nick = '';
    public string $avatar = '';
    public array $groups = [];  // [{group_id, rank, name},..]
    public bool $sysAdmin = false;
}

class Users extends Api {
    function __construct() {
        $this->userInlog = $this->getSession('logedUser',1); 
        $this->tableName = 'users';
        $this->insertLog = true;
		$this->updateLog = true;
		$this->deleteLog = true;
		$this->getLog = false;
        parent::__construct();
    }

    /**
     * username and email unique?
     * @param string $username
     * @param string $email
     * @return string ''|errorMsg(s)
     */
    public function checkRegist(string $username, string $email):string {
        $result = [];
        $res = $this->db->where('username','=',$username)->first();
        if (isset($res->id)) {
            $result[] = 'USERNAME_EXISTS';
        }
        $res = $this->db->where('email','=',$email)->first();
        if (isset($res->id)) {
            $result[] = 'EMAIL_EXISTS';
        }
        return ''.implode(',',$result);
    }

    /**
     * process regist form
     * REQUEST params: sid, username, email, password, realname, avatar, twofactor, accept
     * @return string errorMsg | ''
     *    USERNAME_REQURED PASSWORD_REQUERED PASSWORD_INVALID USERNAME_EXISTS
     *    EMAIL_EXISTS EMAIL_REQUIRED EMAIL_INVALID 
     *    mysql_error email_send_error
     * Process:
     * 1. validate imput datas
     * 2. save record
     * 3. write log
     * 4. if two_factor then generate two_factor_secret
     * 5. set it registered user
     * 6. write log
     * 7. if it is the first record: set it system admin
     * 8. if it is the first record: write log
     * 9. send activator email (if two_factor include two_factor_secret and QRcode image)
     */
    public function doRegist():string {
        $ga = new PHPGangsta_GoogleAuthenticator();

        $this->userInlog = 1;
        $record = new UserRecord();
        $record->id = 0;
        $record->username = $this->getRequest('username','test_elek');
        $record->email = $this->getRequest('email','test_elek@test.test');
        $record->password = password_hash($this->getRequest('password','12345678'),0);
        $record->realname = $this->getRequest('realname','Test Elek');
        $record->two_factor = $this->getRequest('two_factor',0);
        $record->two_factor_secret = $ga->createSecret(); 
        $record->avatar = '';
        $record->email_verifyed = 0;
        $record->status = UserStatus::NotActivated->value;
        $record->error_count = 0;
        $record->lock_time = 0;
        $s = $this->validator('add',$record, $record, 0);
        if ($s == '') {
            $result = $this->insert($record);
        } else {
            $result = $s;
        }    
        return $result;
    }


    /**
     * $_SERVER['REMOTE_ADDR'] errocount increment in datbase
     */
    protected function incIpErrorCount() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $q = new \RATWEB\DB\Query('iplocks');
        $rec = $q->where('ip','=', $ip)->first();
        if (!isset($rec->id)) {
            $rec = new IplockRecord();
            $rec->ip = $ip;
            $rec->error_count = 1;
            $rec->lock_time = time();
            $q->insert($rec); 
        } else {
            $rec->error_count++;
            $rec->lock_time = time();
            $q->where('id','=',$rec->id)->update($rec);
        }
    }

    /**
    * user error count update in database
    * @param int $id userId
    */
    protected function incUserErrorCount(int $id) {
        $q = new \RATWEB\DB\Query('users');
        $rec = $q->where('id','=', $id)->first();
        if (isset($rec->id)) {
            $rec->error_count++;
            $rec->lock_time = time();
            $q->where('id','=',$id)->update($rec);
        }
    }

    /**
     * process success Login
     * @param UserRecord $user
     * @return DoLoginResult
     */
    protected function successLogin($user): DoLoginResult {        
        $result = new DoLoginResult();
        $result->ok = true;
        $result->errorMsg = '';
        $this->setSession('loged',$user->id);
        $this->setSession('logedNick',$user->username);
        $this->setSession('logedAvatar',$user->avatar);
        // read user'groups into session
        $q1 = new \RATWEB\DB\Query('members','m');
        
        $recs = $q1->join('LEFT','groups','g','g.id','=','m.groups_id')
        ->select(['m.groups_id','m.rank','g.name'])
        ->where('m.users_id','=',$user->id)
        ->where('m.status','=','active')
        ->where('g.status','=','active')
        ->all();
        $sysAdmin = false;
        foreach ($recs as $rec) {
            if ($rec->name == 'System admins') {
                $sysAdmin = true;
            }
        }
        $this->setSession('logedGroups',$recs);
        $this->setSession('logedSysAdmin',$sysAdmin);
        $result->id = $user->id;
        $result->nick = $user->username;
        $result->avatar = $user->avatar;
        $result->groups = $recs;
        $result->sysAdmin = $sysAdmin;
        $user->error_count = 0;
        $user->lock_time = 0;
        $q = new \RATWEB\DB\Query('users');
        $q->where('id','=',$user->id)->update($user);
        $result->errorMsg = $q->error;
        $result->ok = ($result->errorMsg == '');
        return $result;
    }        

    /**
     * process login form
     * @params string email
     * @params string password
     * @return DoLoginResult
     * {ok:true|false, errorMsg:'xxxxxx'} | {loged:true, id:'xxx', nick:'xxx', avatar:'xxx', groups:'xxx'}
     *    errorMsg:'NICK_INVALID'|'PASSWORD_INVALID'|'NOT_ACTIVATED'|'DISABLED'|'ACCOUNT_BLOCKED'|'IP_BLOCKED'|
     *             'TWO FACTOR', id, nick, avatar, groups 
     */
    public function doLogin(string $email, string $password): DologinResult {
        $result = new DologinResult();
        $ip = $_SERVER['REMOTE_ADDR'];
        // delete old locks
        $q = new \RATWEB\DB\Query('iplocks');
        $q->where('lock_time','<', (time()-LOCKTIME))
        ->where('lock_time','>',0);
        $q->update(JSON_decode('{"error_count":0, "lock_time":0}'));

        $q = new \RATWEB\DB\Query('users');
        $record = new \stdClass();
        $record->error_count = 0;
        $record->lock_time = 0;
        $q->where('lock_time','<', (time()-LOCKTIME))
        ->where('lock_time','>',0)
        ->update($record);

        // if ip blocked result errorMsg
        $q = new \RATWEB\DB\Query('iplocks');
        $ipRec = $q->where('ip','=', $_SERVER['REMOTE_ADDR'])->first();
        if (isset($ipRec->id)) {
            if ($ipRec->error_count > ERRORLIMIT) {
                $result->errorMsg = 'IP_BLOCKED';
                $result->ok = false;
            }
        }

        // read user ecord
        if ($result->errorMsg == '') {
            $q = new \RATWEB\DB\Query('users');
            $user = $q->where('email','=', $email)->first();
            if (!isset($user->id)) {
                $q = new \RATWEB\DB\Query('users');
                $user = $q->where('username','=', $email)->first();
            }

            // if not found increment ip errorCount, result errorMsg
            if (!isset($user->id)) {
                $result->errorMsg = 'NOT_FOUND';
                $result->ok = false;
                $this->incIpErrorCount();
            }
        }

        // if user blocked return errorMsg
        if ($result->errorMsg == '') {
            if ($user->error_count > ERRORLIMIT) {
                $result->errorMsg = 'USER_BLOCKED';
                $result->ok = false;
            }
        }    
        // check password
        if ($result->errorMsg == '') {
            if (!password_verify($password, $user->password)) {
                // if wrong incremet user errorCount, result errorMsg
                $result->errorMsg = 'WRONG_PASSWORD';
                $result->ok = false;
                $this->incUserErrorCount($user->id);
            }
        }    
        // if two_factor result  errorMsg="two_factor"
        if ($result->errorMsg == '') {
            if ($user->two_factor == 1) {
                $this->setSession('twoFactorUser',$user);
                $result->errorMsg = 'TWO_FACTOR';
                $result->ok = false;
            }
        }    

        // if user disabled error errorMsg
        if ($result->errorMsg == '') {
            if ($user->status == UserStatus::Disabled->value) {
                $result->errorMsg = 'USER_DISABLED';
                $result->ok = false;
            }
        }    
        // if ok result ok=true errorMsg='', save user into session, clear user errorCounts
        if ($result->errorMsg == '') {
            $result = $this->successLogin($user);
        }    
        return $result;
    }

    /**
     * send email
     * @param string $userId
     * @return string empty|errorMsg
     */
    public function sendActivatorEmail(int $userId): string {
        $result = '';

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        $q = new \RATWEB\DB\Query('users');
        $user = $q->where('id','=',$userId)->first();
        if (isset($user->id)) {
            $email = $user->email;
            $code = $user->two_factor_secret.'-'.password_hash($user->id,0);
            $two_factor = $user->two_factor;
            $two_factor_secret = $user->two_factor_secret;
        } else {
            $result = 'NOT_FOUND';
            return $result;
        }
        $ga = new PHPGangsta_GoogleAuthenticator();
        $mailBody = '<div>
        <h2>'.SITETITLE.'</h2>
        <h3>'.SITEURL.'</h3>
        <p>Felhasználói fiók aktiválása( user account activate )</p>
        ';
        if ($two_factor == 1) {
            $qrCodeUrl = $ga->getQRCodeGoogleUrl(SITEURL, $two_factor_secret); 
            $mailBody .= '<p>
            Telepitsd okostelefonodra a google authenticator applikációt!
            (Install google authenticator app into your phone from aplication market!)
            Az app-ban hozzál létre egy fiókot!
            (create new account into app!)</p>
            <p>
            Kulcs (key) :<strong>'.$two_factor_secret.'</strong>
            </p>
            <p>Vagy olvasd be a QRCODE -ot (or read QRCODE):
            <img src="'.$qrCodeUrl.'" />
            </p>';
        }
        $mailBody .= '
        <h3>Fiók aktiváláshoz kattints az alábbi linkre! (click this link!)</h3>
        <p> </p>
        <p><a href="'.SITEURL.'/api/activator.php?code='.$code.'">
            '.SITEURL.'/api/activator.php?code='.$code.'
           </a>
        </p>
        <p> </p>
        <p>vagy másold a fenti web címet a böngésző cím sorába! (or CTRL/C - CTRL/V into browser)</p>
        <p> </p>';
        $mailBody .= '</div>';

        if (defined('UNITTEST')) {
            $result = '';
        } else {
            try {
                $mail->isSMTP();                              //Send using SMTP
                $mail->Host       = MAIL_HOST;                //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                     //Enable SMTP authentication
                $mail->Username   = MAIL_USERNAME;            //SMTP username
                $mail->Password   = MAIL_PASSWORD;            //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
                $mail->Port       = MAIL_PORT;                   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                $mail->CharSet    = 'utf-8';
                $mail->setFrom(MAIL_FROM_ADDRESS);
                $mail->addAddress($email);     //Add a recipient
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = SITETITLE.' fiok aktivalas (account activate)';
                $mail->Body    = $mailBody;
                $mail->AltBody = strip_tags($mailBody);
                $mail->send();
                $result = '';
            } catch (Exception $e) {
                $result = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
        return $result;
    }

    /**
     * create new password and send new password email
     * @param string $userId
     * @return string empty|errorMsg
     */
    public function forgetPassword(int $userId): string {
        $result = '';

        $mail = new PHPMailer(true);
        $q = new \RATWEB\DB\Query('users');
        $user = $q->where('id','=',$userId)->first();
        if (isset($user->id)) {
            $newPassword = 'NP'.substr(md5($user->id.rand(1000,9999)),0,5).rand(100,999).'!';
            $user->password = password_hash($newPassword,0);
            $q->where('id','=', $userId)->update($user);
        } else {
            $result = 'NOT_FOUND';
            return $result;
        }
        $mailBody = '<div>
        <h2>'.SITETITLE.'</h2>
        <h3>'.SITEURL.'</h3>
        <p>Új jelszó( new password)</p>
        <h3>'.$newPassword.'</h3>
        <p> </p>
        <p>Bejelentkezés után a "profil" oldaladon változtasd meg! </p>';
        $mailBody .= '</div>';

        if (defined('UNITTEST')) {
            $result = '';
        } else {
            try {
                $mail->isSMTP();                              //Send using SMTP
                $mail->Host       = MAIL_HOST;                //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                     //Enable SMTP authentication
                $mail->Username   = MAIL_USERNAME;            //SMTP username
                $mail->Password   = MAIL_PASSWORD;            //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
                $mail->Port       = MAIL_PORT;                   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                $mail->CharSet    = 'utf-8';
                $mail->setFrom(MAIL_FROM_ADDRESS);
                $mail->addAddress($user->email);     //Add a recipient
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = SITETITLE.' új jelszó (new password)';
                $mail->Body    = $mailBody;
                $mail->AltBody = strip_tags($mailBody);
                $mail->send();
                $result = '';
            } catch (Exception $e) {
                $result = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
        return $result;
    }


    /**
     * process logout
     */
    public function logout() {
        return;
    }

    /**
	 * process after insert: 
	 * @param Record $record
	 * @return '' or errorMsg
	 */ 		
	protected function afterInsert(\RATWEB\DB\Record &$record): string {
        // 1. set registered user group member
        $result = '';
        $record1 = new MemberRecord();
        $record1->id = 0;
        $record1->users_id = $record->id;
        $record1->groups_id = 2; // registered users
        $record1->rank = 'member';
        $record1->status = MemberStatus::Active->value;
        $db = new \RATWEB\DB\Query('members');
        $db->insert($record1);
        // 2. upload avatar file and update avatar field in users record
        if ($result == '') {
            $uploadRes = $this->uploadFiles('user'.$record->id.'_','images',['jpg','jpeg','png','tif','gif','webp']); 
            if (($uploadRes->errorMsg == '') & (count($uploadRes->fileNames) > 0)) {
                $record->avatar = $uploadRes->fileNames[0];
                $this->db->where('id','=', $record->id)->update($record);
            } else {
                $result = $uploadRes->errorMsg;
            }
        }
        // 3. if its first users set system admin (1-algorithm, 2- guest)
        $db = new \RATWEB\DB\Query('users');
        $count = $db->count();
        if ($count == 3) {
            $result = '';
            $record1 = new MemberRecord();
            $record1->id = 0;
            $record1->users_id = $record->id;
            $record1->groups_id = 3; // system admins
            $record1->rank = 'member';
            $record1->status = MemberStatus::Active->value;
            $db = new \RATWEB\DB\Query('members');
            $result = $db->insert($record1);
        }
        // 4. send activator email
        if ($result == '') {
            $result = $this->sendActivatorEmail($record->id);
        }    
		return $result;
	}

    /**
     * validator
     * @param string $action 'insert'|'update'|'delete'|'get'
     * @param Record $oldRecord 
     * @param Record $record
     * @return ''|errorMsg
     */
    public function validator(string $action, 
        \RATWEB\DB\Record $oldRecord, \RATWEB\DB\Record $record, int $logedUser): string {
        $result = [];
        if ($record->username == '') {
            $result[] = 'USERNAME_REQUIRED';
        }
        if ($record->email == '') {
            $result[] = 'EMAIL_REQUIRED';
        }
        if ($record->password == '') {
            $result[] = 'PASSWORD_REQUIRED';
        }
        if ($record->realname == '') {
            $result[] = 'REALNAME_REQUIRED';
        }
        if (strpos($record->email,'@') <= 0) {
            $result[] = 'NOT_VALID_EMAIL';
        }
        $res = $this->db->where('username','=',$record->username)
        ->where('id','<>',$record->id)
        ->first();
        if (isset($res->id)) {
            $result[] = 'USERNAME_EXISTS';
        }
        $res = $this->db->where('email','=',$record->email)->first();
        if (isset($res->id)) {
            $result[] = 'EMAIL_EXISTS';
        }
        $result = implode(',',$result);
        if (!$result) $result = '';
        return $result;
    }

    /**
     * loged user accessRight? ()
     * @param string $action 'insert'|'update'|'delete'|'get'
     * @param Record $oldRecord 
     * @param Record $record
     * @return string ''|errorMsg
     */
    public function accessRight(string $action, 
        \RATWEB\DB\Record $oldRecord, \RATWEB\DB\Record $record, int $logedUser): string {
        $result = '';
        if ($action == 'insert') {
            // everyone is allowed
            $result = '';
        }
        return $result;
    }

    /**
     * process account activator 
     * @param string $two_factor_secret
     * @param string id hash
     */
    public function activator(string $two_factor_secret, string $hash): string {
        $result = '';
        $q = new \RATWEB\DB\Query('users');
        $res = $q->where('two_factor_secret','=',$two_factor_secret)->first();
        if (isset($res->id)) {
            if (password_verify($res->id, $hash)) {
                $res->status = UserStatus::Active->value;
                $res->email_verifyed = 1;
                $q->where('id','=',$res->id)->update($res);
            } else {
                $result = 'CODE_HASH_ERROR '.$res->id.' '.$hash.' '.password_hash($res->id,0);
            }
        } else {
            $result = 'ACCOUNT_NOT_FOUND';
        }
        return $result;
    }

    public function doTwoFactor(string $key): DoLoginResult {
        $result = new DoLoginResult();
        $user = $this->getSession('twoFactorUser','');

        if ($user != '') {
            $ga = new PHPGangsta_GoogleAuthenticator();
            $oneCode = $key;
            $secret = $user->two_factor_secret;
            if (($ga->verifyCode($secret, $oneCode, 2)) ||
                (($_SERVER['REMOTE_ADDR'] == '127.0.0.1') && ($oneCode == 'test'))) {    // 2 = 2*30sec clock tolerance                
                $result = $this->successLogin($user);
            } else {
                $result->ok = false;
                $result->errorMsg = 'WRONG_KEY';
            }    
        } else {
            $result->ok = false;
            $result->errorMsg = 'NOT_FOUND';
        }
        return $result;
    }

    /**
     * get profile
     * @param int $userId  (if $userId == 0 then show loged' profile)
     * @return ProfileRecord
     * loged == systemAdmin result all data
     * loged == $userId  result all data
     * other: result only username, avatar
     */
    public function getProfile(int $userId): ProfileResult {
        $result = new ProfileResult();
        return $result;
    }

}
?>
