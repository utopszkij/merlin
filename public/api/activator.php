<?php
/**
 * user account activator. process activator email link
 * @params code     two_factor_secret-password_hash($user->id)l
 * echo account activated message and int into home page
 */
if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
if (!defined('SITETITLE')) include_once __DIR__.'/../config.php';
include_once __DIR__.'/../objects/users.php';

$userObj = new Users();
$w = explode('-', Api::getRequest('code','1-1'));
$errorMsg = $userObj->activator($w[0],$w[1]);
if ($errorMsg == '') {
    $msg = 'Fiók aktiválva. (account activated.)';
} else {
    $msg = $errorMsg;
}
echo '
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8" />
    <title>'.SITETITLE.'</title>
    </head>
    <body>
    <p>&nbsp;</p>
    <div style="text-align:center; background-image:url(../images/giphy2.gif);  madding:5%; margin:20%; border-radius:40px">
        <div style="background-color:white; opacity:0.5; color:black">
            <p>&nbsp;</p>
            <img src="../images/merlin.png" style="width:30%; float:left" />
            <p>&nbsp;</p>
            <h2>'.SITETITLE.'</h2>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>'.$msg.'
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <a href="'.SITEURL.'" style="color:blue">'.SITEURL.'</a>
            <p>&nbsp;</p>
        </div>
    </div>
    <p>&nbsp;</p>
    </body>
</html>  
';
?>
