<?php
/**
 * do login
 * @params sid, email, password
 * @return {loged:false, errorMsg:'xxxxxx'} | {loged:true, id:'xxx', nick:'xxx', avatar:'xxx', groups:'xxx'}
 *    errorMsg:'NICK_INVALID'|'PASSWORD_INVALID'|'NOT_ACTIVATED'|'DISABLED'|'ACCOUNT_BLOCKED'|'IP_BLOCKED'|
 *             'TWO FACTOR' 
 * Process:
 * 1. old IP_blocks deleted
 * 2. old accout blocks deleted
 * 3. IP blocked? true --> return with errorMsg
 * 4. nick exists? felse --> IP_errocount++; option: set ip block; return with errorMsg
 * 5. account blocked? true --> return with errorMsg
 * 6. password ellenörzés? false --> account_errorCount++ ; option: set account block; return with errormsg
 * 7. disabled? true --> return with errorMsg
 * 8. activeted? false --> return with errorMsg
 * 8. two factor? truen --> store to session(1), return with errorMsg
 * 9. OK --> account_errorcount = 0; store to session (2), return
 * store session (1): logedUser:{loged:false, id:'xxxx', twoFactor:true}  
 * store session (2): logedUser:{loged:true, id:'xxx', nick:'xxx', avatar:'xxx', groups:'xxx,xxx,xxx'}
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
$result->errorMsg = '';
$_SESSION['logedId'] = $result->id;
//- TEST
echo JSON_encode($result);
?>
