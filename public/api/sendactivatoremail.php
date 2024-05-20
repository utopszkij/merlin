<?php
/**
 * send activator email
 * @params sid, email|username
 * @return {errorMsg, ok}
 */
if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}    
if (!defined('SITETITLE')) include_once __DIR__.'/../config.php';
include_once __DIR__.'/../objects/users.php';
include_once __DIR__.'/../../vendor/autoload.php';
if (!defined('UNITTEST')) {
	session_id( Api::getRequest('sid','0'));
	session_start();
	header('Content-Type: application/json; charset=utf-8');
	header('Access-Control-Allow-Origin:*');
}	
$userObj = new Users();
$result = $userObj->initResult();
$id = 0;
$q = new \RATWEB\DB\Query('users');
$user = $q->where('email','=', Api::getRequest('email'))->first();
if (!isset($user->id)) {
	$q = new \RATWEB\DB\Query('users');
	$user = $q->where('username','=',Api::getRequest('email'))->first();
}
if (isset($user->id)) {
	$result->errorMsg = $userObj->sendActivatorEmail( $user->id );
	$result->ok = ($result->errorMsg == '');
	if ($result->ok) {
		$result->errorMsg = 'EMAIL_SENDED';
	}
} else {
	$result->errorMsg = 'NOT_FOUND';
	$result->ok = false;
}	
echo JSON_encode($result);

?>
