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
error_reporting(E_ERROR);
include_once __DIR__.'/common.php';

include_once __DIR__.'/../objects/users.php';
$userObj = new Users();
$result = $userObj->initResult();
$email = Api::getRequest('email','');
$password = Api::getRequest('password','');
$result->errorMsg = $userObj->doRegist($email, $password);
$result->ok = ($result->errorMsg == '');

echo JSON_encode($result);
?>
