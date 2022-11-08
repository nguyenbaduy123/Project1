<?php
session_start();
$id = "";
if(!empty($_POST)) {
    require_once ('../utils/utility.php');
    require_once ('../database/dbhelper.php');
    $action = getPost('action');
    $id = getPost('id');
    switch($action) {
        case 'add': addToCart($id);
            break;
        case 'delete': deleteItem($id);
            break;
    }
}

function addToCart($id) {

    $cart = [];
    if(isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    }

    $isFind = false;
    for($i=0; $i<count($cart); $i++) {
        if($cart[$i]['id'] == $id) {
            $cart[$i]['num']++;
            $isFind = true;
            break;
        }
    }
    if($isFind == false) {
        $sql = "SELECT * FROM products WHERE id = '$id'";
        $product = executeResult($sql, true);
        $product['num'] = 1;
        $cart[] = $product;
    }
    $_SESSION['cart'] = $cart;
}

function deleteItem($id){
    $cart = [];
    if(isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    }
    for($i=0; $i<count($cart); $i++) {
        if($cart[$i]['id'] == $id) {
            array_splice($cart, $i, 1);
            break;
        }
    }
    $_SESSION['cart'] = $cart;
}
