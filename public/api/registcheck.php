<?php
/**
 * check username and email unique?
 * @params sid, username, email
 * @return {ok:true|false, errorMsg:'xxxxx'
 */
error_reporting(E_ERROR);
include_once __DIR__.'/common.php';

include_once __DIR__.'/../objects/users.php';
$userObj = new Users();
$result = $userObj->initResult();
$result->errorMsg = $userObj->checkRegist($userObj->getRequest('username',''),
$userObj->getRequest('email','') );
$result->ok = ($result->errorMsg == '');
echo JSON_encode($result);
?>
