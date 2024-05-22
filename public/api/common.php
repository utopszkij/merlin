<?php
/**
 * common code for all api 
 * 
 * example:
 * error_reporting(E_ALL);
 * include_once __DIR__.'/common.php
 * ....api specific code
 */
if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
} 
if (!defined('SITETITLE')) include_once __DIR__.'/../config.php';
if (!defined('UNITTEST')) {
    if (isset($_POST['sid'])) {
	    session_id( $_POST['sid'] );
    }    
    session_start();
    header('Content-Type: application/json; charset=utf-8');
	header('Access-Control-Allow-Origin:*');
}
?>
