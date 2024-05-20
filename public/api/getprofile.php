<?php
/**
 * getprofile
 * @params sid, userId
 *  @return {ok:true|false, 
 *           errorMsg, 
 *           id,
 *           username, 
 *           email, 
 *           realname, 
 *           avatar, 
 *           status, 
 *           two_factor,
 *           two_factor_secret,
 *           email_verifyed, 
 *           groups  [{id,rank,name},..]
 *           allGroups  [{id,name},..]
 * Ha sysadmin van bejelentkezve a result minden aadata ki van töltve
 * Ha loged == userId akkor a result minden aadata ki van töltve
 * Egyébként csak az ok, errorMsg,id, username,avatar van kitöltve atöbbi '*' vagy []
 * 
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
if (!defined('UNITTEST')) {
	session_id( $_POST['sid'] );
	session_start();
	header('Content-Type: application/json; charset=utf-8');
	header('Access-Control-Allow-Origin:*');
}
$userObj = new Users();
$result = $userObj->initResult();
$userId = Api::getRequest('userId',0);
$result = $userObj->getProfile($userId);
echo JSON_encode($result);
?>
