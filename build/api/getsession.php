<?php
/**
 * get session data
 * @params sid
 * @return {session:object}
 */
session_id( $_POST['sid']);
session_start();

$result = new \stdClass();
/* CHECK sid is valid? */
if (isset($_SESSION['REMOTE_ADDR'])) {
    if (($_SESSION['REMOTE_ADDR'] != $_SERVER['REMOTE_ADDR']) |
        ($_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT'])) {
        // session id is invalid!
        $result->txt = 'sid invalid ';
        $result->session = [];
        echo JSON_encode($result);
        exit();
    }
}
$_SESSION['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
$_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];

$result->session = $_SESSION;
$result->txt = 'getSession ';
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin:*');
echo JSON_encode($result);
?>