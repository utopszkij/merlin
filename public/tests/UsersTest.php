<?php

use PHPUnit\Framework\TestCase;
use RATWEB\DB\Query;
use RATWEB\DB\Record;

// mock
if (!defined('UNITTEST')) define('UNITTEST',1);
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
include_once __DIR__.'/../config_test.php';
include_once __DIR__.'/../objects/users.php';
include_once __DIR__.'/../objects/db_dat_file.php';

class UsersTest extends TestCase {

    protected $users;

	// ez csak egyszer fut
	public function test_start()  {
		$q = new \RATWEB\DB\Query('users');
		$q->drop();
		$this->assertEquals('','');
    }

	// ez minden egyes test rutin előtt lefut
	public function setup():void {
		$this->assertEquals('','');
	}

	public function test_registcheck_ok() {
        $this->users = new Users();
        $res = $this->users->checkRegist('assaegb','asddfgr');
		$this->assertEquals($res,'');
	}

	public function test_registcheck_username_exists() {
		$q = new \RATWEB\DB\Query('users');
		$q->drop();
		$q->insert(JSON_decode('{"id":0, "username":"guest"}'));
		
        $this->users = new Users();
        $res = $this->users->checkRegist('guest','asddfgr');
		$this->assertEquals($res,'USERNAME_EXISTS');
	}

	public function test_registcheck_email_exists() {
		$q = new \RATWEB\DB\Query('users');
		$q->drop();
		$q->insert(JSON_decode('{"id":0, "email":"none"}'));
		
        $this->users = new Users();
        $res = $this->users->checkRegist('adsr','none');
		$this->assertEquals($res,'EMAIL_EXISTS');
	}

	public function test_doregist_ok() {
		$q = new \RATWEB\DB\Query('users');
		$q->drop();
		// dbinit kialakítása (in dat_file interface not required all field)
		$q->insert(JSON_decode('{"name":"algoritm","email":"nome","two_factor":0, "two_factor_secret":""}'));
		$q->insert(JSON_decode('{"name":"guest","email":"nome2","two_factor":0, "two_factor_secret":""}'));
		
		$q = new \RATWEB\DB\Query('members');
		$q->drop();
		$q = new \RATWEB\DB\Query('users_log');
		$q->drop();
		$_POST['username'] = 'test_elek';
        $_POST['email'] = 'test_elek@test.test';
        $_POST['password'] = '12345678';
        $_POST['realname'] = 'Test Elek';
        $_POST['two_factor'] = 1;
        $this->users = new Users();
        $res = $this->users->doregist();
		$this->assertEquals($res,'');
		$_POST['username'] = 'user';
        $_POST['email'] = 'user@test.test';
        $_POST['password'] = '12345678';
        $_POST['realname'] = 'User';
        $_POST['two_factor'] = 0;
        $this->users = new Users();
        $res = $this->users->doregist();
		$this->assertEquals($res,'');
		
	}

	public function test_doregist_emtyFields() {
        $_POST['username'] = '';
        $_POST['email'] = '';
        $_POST['password'] = '';
        $_POST['realname'] = '';
        $_POST['two_factor'] = 1;
        $this->users = new Users();
		$res = $this->users->doregist();
		$this->assertEquals($res,'USERNAME_REQUIRED,EMAIL_REQUIRED,REALNAME_REQUIRED,NOT_VALID_EMAIL');
	}

	public function test_doregist_userExists() {
        $_POST['username'] = 'test_elek';
        $_POST['email'] = 'test_elek@test.test';
        $_POST['password'] = '123ABCDabcd-';
        $_POST['realname'] = 'Test Elek';
        $_POST['two_factor'] = 1;
        $this->users = new Users();
		$res = $this->users->doregist();
		$this->assertEquals($res,'USERNAME_EXISTS,EMAIL_EXISTS');
	}

	public function test_forgetpassword_wrongData() {
        $this->users = new Users();
		$res = $this->users->forgetpassword(1234);
		$this->assertEquals($res,'NOT_FOUND');
	}

	public function test_forgetpassword_correctData() {
        $this->users = new Users();
		$res = $this->users->forgetpassword(1);
		$this->assertEquals($res,'');
	}

	public function test_sendActivatorEmail_wrongData() {
        $this->users = new Users();
		$res = $this->users->sendActivatorEmail(1934);
		$this->assertEquals($res,'NOT_FOUND');
	}

	public function test_sendActivatorEmail_correctData() {
        $this->users = new Users();
		$res = $this->users->sendActivatorEmail(1);
		$this->assertEquals($res,'');
	}

	public function test_doLogin_wrongPassword() {
		$q = new \RATWEB\DB\Query('iplocks');
		$q->drop();

        $this->users = new Users();
		$res = $this->users->dologin('test_elek','yxycx');
		$this->assertEquals('WRONG_PASSWORD',$res->errorMsg);
	}

	public function test_doLogin_notfound() {
        $this->users = new Users();
		$res = $this->users->dologin('1sqdwe123r','yxycx');
		$this->assertEquals('NOT_FOUND',$res->errorMsg);
	}

	public function test_doLogin_TwoFactorOk() {
        $this->users = new Users();
		$res = $this->users->dologin('test_elek','12345678');
		$this->assertEquals('TWO_FACTOR',$res->errorMsg);
	}

	public function test_doLogin_Ok() {
        $this->users = new Users();
		$res = $this->users->dologin('user@test.test','12345678');
		$this->assertEquals('',$res->errorMsg);
	}

	public function test_doLogin_userDisabled() {
		$q = new \RATWEB\DB\Query('users');
		$record = $q->where('username','=','user')->first();
		$record->status = 'disabled';
		$record = $q->where('username','=','user')->update($record);

        $this->users = new Users();
		$res = $this->users->dologin('user@test.test','12345678');
		$this->assertEquals('USER_DISABLED',$res->errorMsg);

	}

	public function test_doLogin_userblocked() {
        $this->users = new Users();
		$q = new \RATWEB\DB\Query('users');
		$record = $q->where('username','=','user')->first();
		$record->status = 'active';
		$record->error_count = 100;
		$record = $q->where('username','=','user')->update($record);

		$res = $this->users->dologin('user@test.test','12345678');
		$this->assertEquals('USER_BLOCKED',$res->errorMsg);

	}

	public function test_doLogin_ipblocked() {
		$q = new \RATWEB\DB\Query('iplocks');
		$q->drop();
		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

		$this->users = new Users();
		$q = new \RATWEB\DB\Query('users');
		$record = $q->where('username','=','user')->first();
		$record->status = 'active';
		$record->error_count = 0;
		$record = $q->where('username','=','user')->update($record);
		
		$q = new \RATWEB\DB\Query('iplocks');
		$record = new IplockRecord();
		$record->ip = $_SERVER['REMOTE_ADDR'];
		$record->error_count = 100;
		$q->insert($record);

		$res = $this->users->dologin('user@test.test','12345678');
		$this->assertEquals('IP_BLOCKED',$res->errorMsg);

		$q = new \RATWEB\DB\Query('iplocks');
		$q->drop();

	}

	public function test_doTwoFactor_notok() {
		$this->users = new Users();
		$q = new \RATWEB\DB\Query('users');
		$record = $q->where('username','=','user')->first();
		$record->two_factor_secret = '123';
		$_SESSION['twoFactorUser'] = $record;
		$res = $this->users->doTwoFactor('abc');
		$this->assertEquals('WRONG_KEY',$res->errorMsg);
	}

	public function test_doTwoFactor_ok() {
		$this->users = new Users();
		$q = new \RATWEB\DB\Query('users');
		$record = $q->where('username','=','user')->first();
		$record->two_factor_secret = '123';
		$_SESSION['twoFactorUser'] = $record;
		$res = $this->users->doTwoFactor('test');
		$this->assertEquals('',$res->errorMsg);
	}

/*

	public function test_getProfile_notFound() {
        $this->users = new Users();
		$res = $this->users->getProfile(33);
		$this->assertEquals('NOT_FOUND',$res->errorMsg);
	}

	public function test_getProfile_logedEqUserId() {
        $this->users = new Users();
		$this->users->setSession('loged',1);
		$res = $this->users->getProfile(1);
		$this->assertEquals('',$res->errorMsg);
		$this->assertEquals(true,$res->ok);
		$this->assertEquals('none',$res->email);
	}

	public function test_getProfile_logedNeUserId() {
        $this->users = new Users();
		$this->users->setSession('loged',2);
		$res = $this->users->getProfile(1);
		$this->assertEquals('',$res->errorMsg);
		$this->assertEquals(true,$res->ok);
		$this->assertEquals('***',$res->email);
	}
*/

}
?>