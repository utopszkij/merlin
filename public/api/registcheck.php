<?php
/**
 * check username and email unique?
 * @params sid, username, email
 * @return {ok:true|false, errorMsg:'xxxxx'
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
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin:*');
    session_id( Api::getRequest('sid'));
    session_start();
}

$userObj = new Users();
$result = $userObj->initResult();
$result->errorMsg = $userObj->checkRegist($userObj->getRequest('username',''),
$userObj->getRequest('email','') );
$result->ok = ($result->errorMsg == '');
echo JSON_encode($result);
?>
