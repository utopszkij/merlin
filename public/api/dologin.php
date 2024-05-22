<?php
/**
 * do login
 * @params sid, username|email, password
 * @return {ok:true|false, errorMsg:'xxxxxx'} | {loged:true, id:'xxx', nick:'xxx', avatar:'xxx', groups:'xxx'}
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
error_reporting(E_ERROR);
include_once __DIR__.'/common.php';

include_once __DIR__.'/../objects/users.php';
$userObj = new Users();
$email = Api::getRequest('email','');
$password = Api::getRequest('password','');
$result = $userObj->doLogin($email,$password);
echo JSON_encode($result);
?>
