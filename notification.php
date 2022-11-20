<?php
$title = "Thông báo";
require_once('./database/dbhelper.php');
require_once('./utils/utility.php');
include_once('./layouts/header.php');

if(!isLogin()) {
    header("Location: index.php");
    die();
}

$user = $_SESSION['user'];
$user_id = $user['id'];

$sql = "SELECT o.full_name as customer_name, o.phone_number, o.address, o.email,
        p.name as product_name, od.num
 FROM order_details AS od
    INNER JOIN products as p ON p.id = od.product_id
    INNER JOIN orders as o ON o.id = od.order_id
    WHERE p.seller_id = '$user_id'
";
$sell_notifications = executeResult($sql);
// var_dump($notification);

?>

<div class="container">
    <div class="grid">
<?php   
    foreach($sell_notifications as $notification) {
        echo "
            Thằng $notification[customer_name] muốn mua $notification[num] chiếc $notification[product_name] của bạn, bán không? </br>
        ";
    }
?>
    </div>
</div>


<?php
include_once('./layouts/footer.php');
?>