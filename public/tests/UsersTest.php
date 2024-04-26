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
		$this->assertEquals('','');
    }

	// ez minden egyes test rutin előtt lefut
	public function setup():void {
		$this->assertEquals('','');
	}

	public function test_registcheck_ok() {
		$q = new \RATWEB\DB\Query('users');
		$q->drop();
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
		$q->insert(JSON_decode('{"name":"algkoritm","email":"nome"}'));
		$q->insert(JSON_decode('{"name":"guest","email":"nome"}'));
		
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

}
?>