<?php

use PHPUnit\Framework\TestCase;
use RATWEB\DB\Query;
use RATWEB\DB\Record;

// mock
if (!defined('UNITTEST')) define('UNITTEST',1);
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
include_once __DIR__.'/../config_test.php';

class RegistCheckTest extends TestCase {

	// ez csak egyszer fut
	public function test_start()  {
		$this->assertEquals('','');
    }

	// ez minden egyes test rutin előtt lefut
	public function setup():void {
		$this->assertEquals('','');
	}

	public function test_syntaxis() {
		$_POST['sid'] = '1';
		$_POST['username'] = '123guest';
		$_POST['email'] = '123456';
		ob_start();
		include_once(__DIR__.'/../api/registcheck.php');
		ob_end_clean();
		$this->assertEquals('','');
	}
}
?>