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
error_reporting(E_ALL);
include_once __DIR__.'/common.php';

include_once __DIR__.'/../objects/users.php';
$userObj = new Users();
$userId = Api::getRequest('userId',0);
$result = $userObj->getProfile($userId);
echo JSON_encode($result);
?>
