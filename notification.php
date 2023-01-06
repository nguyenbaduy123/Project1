<?php
$title = "Thông báo";
require_once('./database/dbhelper.php');
require_once('./utils/utility.php');
if(!isLogin()) {
    header("Location: index.php");
    die();
}

include_once('./layouts/header.php');

$user = getSession('user');
$user_id = $user['id'];

$sql = "SELECT od.id, o.full_name as customer_name, o.phone_number, o.address, o.email,
        p.name as product_name, od.num
 FROM order_details AS od
    INNER JOIN products as p ON p.id = od.product_id
    INNER JOIN orders as o ON o.id = od.order_id
    WHERE p.seller_id = '$user_id' AND od.status is null
";
$sell_notifications = executeResult($sql);

$sql = "SELECT od.id, od.product_id, p.name as product_name, od.price, 
    od.num, p.image, od.status
    FROM order_details AS od
    INNER JOIN products as p ON p.id = od.product_id
    INNER JOIN orders as o ON o.id = od.order_id
    WHERE o.customer_id = '$user_id'
";
$buy_notifications = executeResult($sql);
// var_dump($notification);

?>

<div class="container">
    <div class="grid">
       <div class="sell-notification">
       <h3 class="sell-notification-header" style="text-align: center; 
        margin: 0; color: white;
       height: 2.5rem; line-height: 2.5rem">Đơn hàng đang chờ</h3>
<?php   
    foreach($sell_notifications as $notification) {
        echo '<div class="notification-item" id="id'.$notification['id'].'">';
        echo "
            Người dùng <a href=''>$notification[customer_name]</a> muốn mua $notification[num] 
            sản phẩm <a href=''>$notification[product_name]</a> của bạn, Đồng ý bán?</br>
        ";
        echo '<button class="btn btn-success btn-access-sell" style="height: 2.5rem; 
        display: inline-block; background-color: green; width: 25%; margin-left: 0;"
        onclick="acceptSell('.$notification["id"].')">
        Đồng ý
        </button> 
        <button class="btn btn-danger" style="display: inline-block; width: 25%;"
        onclick="refuseSell('.$notification["id"].')">
        Từ chối
        </button></div>';
    }
?>      </div> 
    <div class="sell-notification buy-notification">
       <h3 class="sell-notification-header" style="text-align: center; 
        margin: 0; color: white; background-color: var(--primary-color);
       height: 2.5rem; line-height: 2.5rem">Đơn hàng đã đặt</h3>
       <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Ảnh</th>
                                    <th style="width: 450px">Tên sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
<?php 
    $stt = 1;
    foreach($buy_notifications as $bNotification) {
        echo '
            <tr>
            <td>'.$stt.'</td>
            <td><img height="100" width="auto" src="'.$bNotification["image"].'"</td>
            <td>'.$bNotification["product_name"].'</td>
            <td>'.$bNotification['num'].'</td>
            <td>'.
            currency_format($bNotification['price']*$bNotification['num'])
            .'</td>
        ';
        if($bNotification['status'] == null) {
            echo '<td>Đang chờ</td>';
        }
        else if ($bNotification['status'] == "accept") {
            echo '<td>Đang vận chuyển</td>';
        }
        else if ($bNotification['status'] == "refuse") {
            echo '<td>Bị từ chối</td>';
        }

        echo'
            <td><button class="btn btn-danger">Hủy đơn hàng</button></td>';
        $stt++;
    }
?>
                            </tbody>
       </table>
        </div> 
    
    </div>

    </div>
</div>

<script type="text/javascript">
    function acceptSell(id) {
        if (confirm("Chấp nhận bán?") == true) {
            $.post("api/api-sell.php", {
            'action': 'accept',
            'id': id
            }, function(data) {
                alert(data);
            })
            let text = "#id" + id;
            $(text).remove();
            location.reload;
        } else {
            return;
        }
    }
    function refuseSell(id) {
        if (confirm("Bạn muốn từ chối đơn hàng này?") == true) {
            $.post("api/api-sell.php", {
                'action': 'refuse',
                'id': id
            }, function(data) {
               alert(data);
            })
            let text = "#id" + id;
            $(text).remove();
            location.reload;
        }
        else {
            return;
        }
    }
</script>


<?php
include_once('./layouts/footer.php');
?>

<style>
    .notification-item {
        padding: 12px 12px;
        padding-bottom: 0;
        background-color: #fff;
        margin-bottom: 12px;
    }
    .sell-notification {
        padding: 1rem 1rem;  
        background-color:var(--primary-color);
        margin-bottom: 2rem;
    }
    .buy-notification {
        background-color: #fff;
    }
</style>