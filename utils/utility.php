<?php

function isLogin() {
    if(validateToken() != null) {
        return true;
    }
    return false;
}

function validateToken() {

    if(isset($_SESSION['user'])) {
        return $_SESSION['user'];
    }

    $token = '';
    if(isset($_COOKIE['token'])) {
        $token = $_COOKIE['token'];
        $sql = "SELECT * FROM users WHERE token = '$token'";
        $data = executeResult($sql);
        if($data != null && count($data) > 0) {
            $_SESSION['user'] = $data[0];
            return $data[0];
        }
    }
    return null;
}
function fixSqlInjection($str) {
    $str = str_replace('\'', '\\\'', $str);
    return $str;
}

function getGet($key) {
    $value = '';
    if(isset($_GET[$key])) {
        $value = fixSqlInjection($_GET[$key]);
    }
    return $value;
}
function getPost($key) {
    $value = '';
    if(isset($_POST[$key])) {
        $value = fixSqlInjection($_POST[$key]);
    }
    return $value;
}

function getCookie($key) {
    $value = '';
    if(isset($_COOKIE[$key])) {
        $value = $_COOKIE[$key];
    }
    return $value;
}

function getPwdSecurity($pwd) {
    return md5(md5($pwd).MD5_PRIVATE_KEY);
}
if (!function_exists('currency_format')) {
    function currency_format($number, $suffix = 'đ') {
        if (!empty($number)) {
            return number_format($number, 0, ',', '.') . "{$suffix}";
        }
    }
}