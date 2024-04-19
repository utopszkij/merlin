<?php
/**
 * users object
 */
include __DIR__.'/api.php';
use RATWEB\DB;

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
        return implode(',',$result);
    }

    /**
     * process regist form
     * REQUEST params: sid, username, email, password, realname, avatar, twofactor, accept
     * @return { ok:true|false, errorMsg:''|'xxxx' }
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
        $this->userInlog = 1;
        $record = new UserRecord();
        $record->id = 0;
        $record->username = $this->getRequest('username','test_elek');
        $record->email = $this->getRequest('email','test_elek@test.test');
        $record->password = password_hash($this->getRequest('password','12345678'),0);
        $record->realname = $this->getRequest('realname','Test Elek');
        $record->two_factor = $this->getRequest('twofactor',0);
        $record->avatar = '';
        $record->email_verifyed = 0;
        $record->two_factor_secret = '';
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
     * @return string ''|errorMsg
     */
    public function sendActivatorEmail(int $userId): string {
        return '';
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
        $result = $db->insert($record1);
        // 2. upload avatar file and update avatar field in users record
        if ($result == '') {
            $uploadRes = $this->uploadFiles('userAvatar_','images',['jpg','jpeg','png','tif','gif','webp']); 
            if (($uploadRes->errorMsg == '') & (count($uploadRes.fileNames) > 0)) {
                $record->avatar = $uploadRes->fileNames[0];
                $this->db->update($record);
            } else {
                $result = $uploadRes->errorMsg;
            }
        }
        // 3. if its first users set system admin
        $db = new \RATWEB\DB\Query('users');
        $count = $db->count();
        if ($count == 1) {
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
            $result = sendActivatorEmail($record->id);
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
        if (strlen($record->password) < 8) {
            
            $result[] = 'SHORT_PASSWORD_MIN8';
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
        return implode(',',$result);
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