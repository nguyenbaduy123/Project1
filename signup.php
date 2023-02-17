<?php
require_once './utils/utility.php';
require_once './database/dbhelper.php';

$user = validateToken();
if ($user != null) {
    header('Location: index.php');
    die();
}

$email = $password = $confirmationPassword = '';
$email = getPost('email');
$password = getPost('password');
$confirmationPassword = getPost('confirmation_password');

$sql = "SELECT * FROM users WHERE email = '$email'";
$data = executeResult($sql);

if ($data == null && count($data) == 0) {
    $sql =
        "INSERT INTO users(email, password)
        VALUE ('" .
        $email .
        "','" .
        $password .
        "')";
    execute($sql);
    echo "<script>
        window.location.href = 'index.php';
        alert('Chúc mừng bạn đã đăng ký thành công');
        </script>";
} else {
    echo "<script>
        window.location.href = 'index.php';
        alert('Email đã được đăng ký');
        </script>";
}
?>
