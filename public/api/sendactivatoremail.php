<?php
/**
 * send activator email
 * @params sid, email|username
 * @return {errorMsg, ok}
 */
error_reporting(E_ALL);
include_once __DIR__.'/common.php';

include_once __DIR__.'/../objects/users.php';
include_once __DIR__.'/../../vendor/autoload.php';
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
