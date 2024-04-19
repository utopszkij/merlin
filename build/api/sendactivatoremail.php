<?php
/**
 * send activator email
 * @params sid, email
 * @return {mailsended:true|false}
 */
session_id( $_POST['sid']);
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin:*');
$result = new \stdClass();
$result->mailsended=false;

/* CHECH sid is valid? */
if (($_SESSION['REMOTE_ADDR'] != $_SERVER['REMOTE_ADDR']) |
    ($_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT'])) {
	// session id is invalid!
	$result->errorMsg = 'SESSION_INVALID';
	echo JSON_encode($result);
	exit();
}

//+ TEST
$result->mailsended=true;
//- TEST

echo JSON_encode($result);
?>
