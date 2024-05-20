<?php
if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ERROR);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}   
if (!defined('SITETITLE')) include_once __DIR__.'/../config.php';
include_once __DIR__.'/../objects/users.php';
if (!defined('UNITTEST')) {
	session_id( Api::getRequest('sid') );
	session_start();
	header('Content-Type: application/json; charset=utf-8');
	header('Access-Control-Allow-Origin:*');
}
$userObj = new Users();
$key = Api::getRequest('key','');
$result = $userObj->doTwoFactor($key);
echo JSON_encode($result);
?>