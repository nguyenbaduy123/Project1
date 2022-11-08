<?php
session_start();
$title = "Đặt hàng";
require_once ("./utils/utility.php");
require_once ("./database/dbhelper.php");
if(isLogin() == false) {
    header("Location: index.php");
    die();
}
include_once ('layouts/header.php');
if(!empty($_POST)) {
    $full_name = getPost('full_name');
    $phone_number = getPost('phone_number');
    $email = getPost('email');
    $address = getPost('address');
    $order_date = date('Y-m-d H:i:s');

    $cart = [];
    if(isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    }
    if($cart == null || count($cart) == 0) {
        header('Location: index.php');
        die(); 
    }

    $sql = "INSERT INTO orders (full_name, phone_number, email, address, order_date)
            values ('$full_name', '$phone_number', '$email', '$address', '$order_date')";
    execute($sql);

    $sql = "SELECT * FROM orders WHERE order_date = '$order_date'";
    $order = executeResult($sql, true);
    $order_id = $order['id'];

    foreach($cart as $item) {
        $product_id = $item['id'];
        $num = $item['num'];
        $price = $item['price'];

        $sql = "INSERT INTO order_details(order_id, product_id, num, price)
                VALUES ('$order_id', '$product_id', '$num', '$price')";
        execute($sql);
    }
    unset($_SESSION['cart']);
}
?>

<div class="container">
    <div class="grid">
        <div class="grid__row">
        <div class="checkout-form" style="width: 30%;">
                    <form action = "" method = "POST">
                                <h4 class="input-name">Họ và tên:</h4>
                                <input required="true" type="text" name="full_name" class="form-checkout" placeholder="Không quá 150 ký tự">
                                <h4 class="input-name">Số điện thoại:</h4>
                                <input required="true" type="text" maxlength="11" name="phone_number" class="form-checkout" placeholder="">
                                <h4 class="input-name">Email:</h4>
                                <input required="true" type="email" name="email" class="form-checkout" placeholder="">
                                <h4 class="input-name">Địa chỉ:</h4>
                                <input required="true" type="text" name="address" class="form-checkout" placeholder="">
                        <div>
                            <button type="submit" class="btn add-btn" style="margin-left: 0; 
                            background-color: green; width: 300px; margin: 1.5rem 0; ">Hoàn thành</button>
                        </div>
                    </form>
        </div>
            <div class="cart" style="width: 70%;">
            <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Giá tiền</th>
                                    <th>Số lượng</th>
                                    <th>Tổng</th>
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
    </tr>';
    }
}
?>    
                            </tbody>
                        </table>
                        <h2 style="color: red;">Tổng: <?=currency_format($total)?></h2>
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
