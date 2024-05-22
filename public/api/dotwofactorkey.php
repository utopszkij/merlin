<?php
error_reporting(E_ERROR);
include_once __DIR__.'/common.php';

include_once __DIR__.'/../objects/users.php';
$userObj = new Users();
$key = Api::getRequest('key','');
$result = $userObj->doTwoFactor($key);
echo JSON_encode($result);
?>