<?php
session_start();
$title = "Giỏ hàng";
require_once ("./utils/utility.php");
require_once ("./database/dbhelper.php");
if(isLogin() == false) {
    header("Location: index.php");
    die();
}
include_once ('layouts/header.php');
?>

<div class="container">
    <div class="grid">
        <div class="grid__row">
            <div class="cart">
            <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Giá tiền</th>
                                    <th>Số lượng</th>
                                    <th>Tổng</th>
                                    <th width = "60px"></th>
                                </tr>
                            </thead>
                            <tbody>
<?php
$cart = [];
if(isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
}
$count = 1;
$total = 0;
if(count($cart) < 1) {
    echo '<h1>Bạn chưa lựa chọn sản phẩm nào</h1>';
}
else {
    foreach($cart as $item) {
        $total += $item['num'] * $item['price'];
        echo '
        <tr>
        <td>'.$count++.'</td>
        <td><img height = "100" width = "auto" src = "'.$item['image'].'"</td>
        <td>'.$item['name'].'</td>
        <td>'.currency_format($item['price']).'</td>
        <td>'.$item['num'].'</td>
        <td>'.currency_format($item['num']*$item['price']).'</td>
        <td><button class="btn btn-danger" onclick="deleteItem('.$item['id'].')">
                    Delete</button></td>
    </tr>';
    }
}
?>    
                            </tbody>
                        </table>
                        <h2 style="color: red;">Tổng: <?=currency_format($total)?></h2>
                        <?php if(count($cart) > 0) {
                            echo '<a href="checkout.php" style="text-decoration: none">
                            <div class="btn add-btn" style="background-color: green; margin: auto 0;">Thanh toán</div></a>';
                        } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function deleteItem(id) {
        $.post('api/api-sanpham.php', {
            'action': 'delete',
            'id': id
        }, function(data) {
            location.reload();
        })
    }
</script>


<?php 
    include_once('layouts/footer.php');
?>

