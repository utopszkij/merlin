<?php
/**
 * get session id
 * @params none
 * @return {sid:string}
 */
session_start();
$result = new \stdClass();
$result->sid = session_id();
$_SESSION['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
$_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin:*');
echo json_encode($result);
?>
