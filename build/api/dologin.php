<?php
/**
 * do login
 * @params sid, email, password
 * @return {loged:false} | {loged:true, id:'', nick:'', avatar:'', groups:''}
 */
session_id( $_POST['sid'] );
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


//+ TEST
$result->loged=true;
$result->id='1';
$result->nick='user1';
$result->avatar='images/noimage.png';
$result->groups='registered';
$_SESSION['logedId'] = $result->id;
//- TEST
echo JSON_encode($result);
?>
