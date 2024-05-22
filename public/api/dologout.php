<?php
/**
 * do logout
 * @params sid
 * @return {loged:false} 
 */
error_reporting(E_ERROR);
include_once __DIR__.'/common.php';

$result = new \stdClass();
$result->loged=0;
$result->id='';
$result->nick='';
$result->avatar='';
$result->groups='';
$result->sysAdin = false;

/* CHECK sid is valid? */
if (($_SESSION['REMOTE_ADDR'] != $_SERVER['REMOTE_ADDR']) |
    ($_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT'])) {
	// session id is invalid!
	$result->errorMsg = 'SESSION_INVALID';
	echo JSON_encode($result);
	exit();
}
$_SESSION['logedId'] = '';
$_SESSION['loged'] = '';
$_SESSION['logedNick'] = '';
$_SESSION['logedAvatar'] = '';
$_SESSION['logedGroups'] = [];
$_SESSION['logedSysAdmin'] = false;

echo JSON_encode($result);
?>
