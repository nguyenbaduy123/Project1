<?php
session_start();
require_once ("./utils/utility.php");
require_once ("./database/dbhelper.php");
    
$user = validateToken();
if($user != null) {
    header('Location: index.php');
    die();
}

$email = $password = "";
    $email = getPost('email');
    $password = getPost('password');
$sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
$data = executeResult($sql);
    
if($data != null && count($data) > 0) {

    // setcookie('login', 'true', time() + 24*60*60, "/");

    $token = getPwdSecurity(time().$data[0]['id']);
    setcookie('token', $token, time()+24*60*60, '/');
    $sql = "UPDATE users SET token = '$token' WHERE id = ".$data[0]['id'];
    execute($sql);
    header('Location: index.php');
    die();
}
else {
    echo "<script>
    window.location.href = 'index.php';
    alert('Tài khoản hoặc mật khẩu không chính xác');
    </script>";
}

?>