<?php
/**
 * do logout
 * @params sid
 * @return {loged:false} 
 */
if (!defined('UNITTEST')) {
	session_id( $_POST['sid']);
	session_start();
	header('Content-Type: application/json; charset=utf-8');
	header('Access-Control-Allow-Origin:*');
}
$result = new \stdClass();
$result->loged=0;
$result->id='';
$result->nick='';
$result->avatar='';
$result->groups='';

/* CHECK sid is valid? */
if (($_SESSION['REMOTE_ADDR'] != $_SERVER['REMOTE_ADDR']) |
    ($_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT'])) {
	// session id is invalid!
	$result->errorMsg = 'SESSION_INVALID';
	echo JSON_encode($result);
	exit();
}
$_SESSION['logedId'] = '';

echo JSON_encode($result);
?>
