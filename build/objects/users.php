<?php
/**
 * users object
 */
include __DIR__.'/api.php';
class Users extends Api {
    function __construct() {
        $this->userInlog = $this->getSession('logedUser',0); 
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
        $record = new \RATWEB\DB\Record();
        $record->id = 0;
        $record->username = $this->getRequest('username');
        $record->email = $this->getRequest('email');
        $record->password = password_hash($this->getRequest('password'));
        $record->realname = $this->getRequest('realname');
        $record->two_factor = $this->getRequest('twofactor');
        $record->avatar = '';
        $record->email_veryfied = 0;
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
        $record = new \RATWB\DB\Record();
        $record->id = 0;
        $record->users_id = $record->id;
        $record->groups_id = 2; // registered users
        $record->rank = 'member';
        $record->status = 'active';
        $db = new \RATWEB\DB\Query('members');
        $result = $db->insert($record);
        // 2. upload avatar file and update avatar field in users record

        // 3. if its first users set system admin
        $count = $this->db->count();
        if ($count == 1) {
            $result = '';
            $record = new \RATWB\DB\Record();
            $record->id = 0;
            $record->users_id = $record->id;
            $record->groups_id = 3; // system admins
            $record->rank = 'member';
            $record->status = 'active';
            $db = new \RATWEB\DB\Query('members');
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
        $result = '';
        return $result;
    }

    /**
     * loged user accessRight?
     * @param string $action 'insert'|'update'|'delete'|'get'
     * @param Record $oldRecord 
     * @param Record $record
     * @return string ''|errorMsg
     */
    public function accessRight(string $action, 
        \RATWEB\DB\Record $oldRecord, \RATWEB\DB\Record $record, int $logedUser): string {
        $result = '';
        return $result;
    }



}
?>