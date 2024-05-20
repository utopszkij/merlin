<?php 
declare(strict_types=1);
DEFINE('SITETITLE','Merlin');
DEFINE('SITEURL','https://valami.hu');
DEFINE('DBTYPE','mysql');
DEFINE('ERRORLIMIT',5); // if errorCounter > 5 lock user/ip
DEFINE('LOCKTIME',300); // lock time sec

// MYSQL
DEFINE('HOST','localhost');
DEFINE('USER','***');
DEFINE('PSW','***');
DEFINE('DBNAME','***');

// SMTP
DEFINE('MAIL_HOST','***');
DEFINE('MAIL_PORT','465');
DEFINE('MAIL_USERNAME','***');
DEFINE('MAIL_PASSWORD','***');
DEFINE('MAIL_ENCRYPTION','SSL');
DEFINE('MAIL_FROM_ADDRESS','***');
DEFINE('MAIL_FROM_NAME','***');
DEFINE('MAIL_WAIT_SEC','130');
// Facebook login
DEFINE('FB_APPID','***');
DEFINE('FB_SECRET','***');
DEFINE('FB_REDIRECT',SITEURL.'/vendor/fblogin.php');
// Google login
DEFINE('GOOGLE_APPID','***');
DEFINE('GOOGLE_SECRET','***');
DEFINE('GOOGLE_REDIRECT',SITEURL.'/vendor/googlelogin.php');

?>
