<?php
session_start();
$title = "Chi tiết sản phẩm";
require_once ("./utils/utility.php");
require_once ("./database/dbhelper.php");
include_once ('layouts/header.php');
$seller = "";
$productId = "";
if(!empty($_GET)) {
    $productId = getGet('id');
    $sql = "SELECT * FROM products WHERE id = '$productId'";
    $thisProduct = executeResult($sql, true);
    $sql = "SELECT * FROM users WHERE id = ".$thisProduct['seller_id'];
    $seller = executeResult($sql, true); 
}
?>
    <div class="container">
        <div class="grid">
                <div class="product-detail">
                    <div class="this-product__img" 
                    style="background-image: url('<?= $thisProduct['image'] ?>'); 
                    background-size: contain; background-repeat: no-repeat;">
                    </div>
                    <div class="this-product__info">
                        <span class="this-product__name"><?=$thisProduct['name'] ?></span>
                        <div class="this-product__price"><?= currency_format($thisProduct['price']) ?></div>
                        <div class="btn add-btn" onclick="addToCart(<?=$productId?>)" style="background-color: green;">Thêm vào giỏ hàng</div>
                        <div class="btn buy-btn" onclick= "window.location.href='checkout.php?buy=<?=$productId?>'">Mua ngay</div>
                        <div class="this-product__seller">
                            <span>
                                Người bán:
                                <?= $seller['email'];?>
                            </span>
                            <span>
                                Ngày đăng: <?= $thisProduct['created_date'] ?>
                            </span>
                            <?php if($thisProduct['updated_date'] != null){
                                echo "<span>Ngày cập nhật: ";
                                echo $thisProduct['updated_date']."</span>";    
                            } ?>
                        </div>
                    </div>
                </div>
                <div class="this-product_des" style="word-wrap: break-word;">
                    <div class="this-product_des-header">Mô tả sản phẩm</div>
                    <div class="this-product_des-body">
                        <pre>
                            <p style="white-space: pre-wrap;"><?= $thisProduct['description']?></p>
                        </pre>
                    </div>
            </div>
        </div>
        <?php include_once('layouts/modal.php'); 
        $isLogin = 'false';
        if(isLogin()) {
            $isLogin = 'true';
        }
        echo "
        <script>
        var isLogin = '$isLogin';
        </script>"?>
    </div>

<script type="text/javascript">
    function addToCart(id) {
        if(isLogin == 'false') {
            alert ("Cần đăng nhập trước!");
            return;
        }
        else {
            $.post('api/api-sanpham.php', {
                'action': 'add',
                'id': id
            }, function(data) {
                location.reload();
            })
        }

    }
</script>

<?php 
    include_once ('layouts/footer.php');
?>

