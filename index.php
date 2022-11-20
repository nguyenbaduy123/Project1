<?php
session_start();

$title = "Trang chủ";
require_once ("./utils/utility.php");
require_once ("./database/dbhelper.php");
include_once ('layouts/header.php');

$productList = [];
$search = "";
if(!empty($_GET)) {
    if(isset($_GET['category'])) {
        $category_id = getGet('category');
        $sql = "SELECT * FROM products WHERE category_id = $category_id";
        $productList = executeResult($sql);
    }
    else if(isset($_GET['search'])) {
        $search = "%".getGet('search')."%";
        $sql = "SELECT * FROM products WHERE name LIKE '$search' OR description LIKE '$search'";
        $productList = executeResult($sql);
    }
}
else {
    $sql = "SELECT * FROM products";
    $productList = executeResult($sql);
}
?>

<div class="container">
    <div class="grid product">
        <div class="catergory">
            <div class="category-header">
                Danh Mục
            </div>
            <div class="category-list">

<?php
    $sql = "SELECT * FROM category";
    $categoryList = executeResult($sql);
    foreach($categoryList as $categoryItem) {
        echo '
                <a href="?category='.$categoryItem['id'].'" class="category-item">
                    <div class="category-item__img" style = "background-image: url('.$categoryItem['image'].')"></div>
                    <div class="category-item__name">'.$categoryItem['name'].'</div>
                </a>';
    } 
?>
            </div>
        </div>
        <div class="grid__row">
        <?php 
            if(count($productList) == 0) {
                echo '<h4 style = " width: 100%; text-align: center;">Không tìm thấy kết quả phù hợp</h4>';
            }
            foreach($productList as $product) {
            echo '<div class="grid__column-2">
                <a class="product-item" href="sanpham.php?id='.$product['id'].'">
                <div class="product-item__img" style="background-image: url('.$product['image'].')"></div>
                <h4 class="product-item__name">'.$product['name'].'</h4>
                <h5 class="product-item__price">'.currency_format($product['price']).'</h5>
                </a>
                </div>';
    }
?>
                </div>
            </div>
            </div>

<?php include_once('layouts/modal.php'); ?>

<?php
    include_once ('layouts/footer.php');
?>