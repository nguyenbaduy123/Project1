<?php
session_start();
include './database/dbhelper.php';
include './utils/utility.php';

if(isset($_COOKIE['token'])) {
    $token = $_COOKIE['token'];
    $sql = "UPDATE users SET token = '' WHERE token = '$token'";
    $data = execute($sql);
}
setcookie('token', '', time() - 10, '/');
header('Location: index.php');
session_destroy();