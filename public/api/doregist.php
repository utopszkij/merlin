<?php
/**
 * do regist
 * @params sid, username, email, password, realname, avatar, two_factor
 * @return { ok:true|false, errorMsg:''|'xxxx' }
 *    USERNAME_REQURED PASSWORD_REQUERED PASSWORD_INVALID USERNAME_EXISTS
 *    EMAIL_EXISTS EMAIL_REQUIRED EMAIL_INVALID 
 *    mysql_error email_send_error
 * Process:
 * 1. validate imput datas
 * 2. save record
 * 3. write log
 * 4. if two_factor then generate two_factor_secret
 * 5. set it registered user
 * 6. write log
 * 7. if it is the first record: set it system admin
 * 8. if it is the first record: write log
 * 9. send activator email (if two_factor include two_factor_secret and QRcode image)
 */
if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}    
if (!defined('SITETITLE')) include_once __DIR__.'/../config.php';
include_once __DIR__.'/../objects/users.php';
if (!defined('UNITTEST')) {
    session_id( Api::getRequest('sid') );
    session_start();
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin:*');
}    
$userObj = new Users();
$result = $userObj->initResult();
$result->errorMsg = $userObj->doRegist();
$result->ok = ($result->errorMsg == '');
echo JSON_encode($result);
?>
