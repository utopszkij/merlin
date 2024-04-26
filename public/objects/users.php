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

class UserRecord extends \RATWEB\DB\Record {
    public int $id;
    public string $username;
    public string $email;
    public string $password;
    public string $realname;
    public string $avatar;
    public string $status;
    public int $email_verifyed;
    public int $two_factor; 
    public string $two_factor_secret;
    public int $error_count;
    public int $lock_time;
}

class MemberRecord extends \RATWEB\DB\Record {
    public int $id;
    public int $users_id;
    public int $groups_id;
    public string $rank;
    public string $status;
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
        $record->status = 'notActivated';
        $record->error_count = 0;
        $record->lock_time = 0;

        $result = $this->insert($record);
        return $result;
    }

    /**
     * process login form
     * REQUEST params: username, password
     *   (can email in $usernae)
     * @return string ''|errorMsg
     */
    public function doLogin(): string {
        return '';
    }

    /**
     * send email
     * @param int $userId
     * @return string empty|errorMsg
     */
    public function sendActivatorEmail(int $userId): string {
        $result = '';
        //Import PHPMailer classes into the global namespace
        //These must be at the top of your script, not inside a function

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        $q = new \RATWEB\DB\Query('users');
        $user = $q->where('id','=',$userId)->first();
        if (isset($user->id)) {
            $email = $user->email;
            $code = $user->two_factor_secret.'-'.password_hash($user->id);
            $two_factor = $user->two_factor;
            $two_factor_secret = $user->two_factor_secret;
        } else {
            $email = 'tibor.fogler@gmail.com';
            $code = '123456';
            $two_factor = 0;
            $two_factor_secret = '123456';
        }
        
        $email = 'tibor.fogler@gmail.com';

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
        return $result;
    }

    /**
     * process logout
     */
    public function logout() {
        return;
    }

    /**
     * get user and user' groups
     * @param int $userId
     * @return Record {id,username,email,realname,avatar,twofactor,groups:[groupId,...] }
     */
    public function getById(int $userId): \RATWEB\DB\Record {
        $result = new \RATWEB\DB\Record();
        return $result;
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
        $record1->status = 'active';
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
            $record1->status = 'active';
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



}
?>
