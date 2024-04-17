<?php
/**
 * do logout
 * @params sid, email, password
 * @return {loged:false} | {loged:true, id:'', nick:'', avatar:'', groups:''}
 */
session_id( $_POST['sid']);
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin:*');
$result = new \stdClass();
$result->loged=false;
$result->id='';
$result->nick='';
$result->avatar='';
$result->groups='';

/* CHECK sid is valid? */
if (($_SESSION['REMOTE_ADDR'] != $_SERVER['REMOTE_ADDR']) |
    ($_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT'])) {
	// session id is invalid!
	echo JSON_encode($result);
	exit();
}

$_SESSION['logedId'] = '';
echo JSON_encode($result);
?>
