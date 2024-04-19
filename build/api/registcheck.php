<?php
/**
 * check username and email unique?
 * @params sid, username, email
 * @return {ok:true|false, errorMsg:'xxxxx'
 */

include __DIR__.'/../objects/users.php';
session_id( Api::getRequest('sid'));
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin:*');

$userObj = new Users();
$result = $userObj->initResult();
$result->errorMsg = $userObj->checkRegist($userObj->getRequest('username',''),
         								  $userObj->getRequest('email','') );
$result->ok = ($result->errorMsg == '');										
echo JSON_encode($result);
?>
