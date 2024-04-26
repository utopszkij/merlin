<?php
/**
 * do two factor key
 * @params sid, key
 * sessionban input: logedUser:{loged: false, id:'xxx', twoFactor:true}
 * @return {loged:false, errorMsg:'KEY_INVALID'} | {loged:true, id:'xxx', nick:'xxx', avatar:'xxx', groups:'xxx'}
 * Müködés:
 * 1. user ekord beolvasása
 * 2. key ellenörzése ha nem jó ---> return
 * 9. OK --> a sessionba tárol, return
 * session: logedUser:{loged:true, id:'xxx', nick:'xxx', avatar:'xxx', groups:'xxx'}
 */
if (!defined('UNITTEST')) {
	session_id( $_POST['sid'] );
	session_start();
	header('Content-Type: application/json; charset=utf-8');
	header('Access-Control-Allow-Origin:*');
}	
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
	$result->errorMsg = 'SESSION_INVALID';
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
