<?php
session_start();
$title = 'Chỉnh sửa';
require_once './utils/utility.php';
require_once './database/dbhelper.php';
if (isLogin() == false || empty($_GET)) {
    header('Location: index.php');
    die();
}
include_once 'layouts/header.php';
$userId = $_SESSION['user']['id'];
$id = getGet('id');
$sql = "SELECT * FROM products WHERE id = '$id'";
$product = executeResult($sql, true);
if ($product['seller_id'] != $userId) {
    echo "<script>
    window.location.href = 'banhang.php';
    alert ('Không thể chỉnh sửa sản phẩm của người khác') </script>";
    die();
}

$productImg = $product['image'];

$explodedOld = explode('/', $productImg);
$last_element = end($explodedOld);
$oldName = strtolower($last_element);

if (isset($_FILES['image']) && $_FILES['image']['tmp_name'] != '') {
    var_dump($_FILES['image']);
    $errors = [];
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $exploded = explode('.', $_FILES['image']['name']);
    $last_element = end($exploded);
    $file_ext = strtolower($last_element);
    $expensions = ['jpeg', 'jpg', 'png'];

    if (in_array($file_ext, $expensions) == false) {
        $errors[] = 'Chỉ hỗ trợ upload file JPEG hoặc PNG.';
    }
    if ($file_size > 2097152) {
        $errors[] = 'Kích thước file không được lớn hơn 2MB';
    }
    if (empty($errors) == true) {
        move_uploaded_file($file_tmp, 'images/' . $oldName);
        $productImg = './images/' . $oldName;
    } else {
        echo '<script>alert("Chỉ hỗ trợ upload file JPEG hoặc PNG.")</script>';
    }
}
$date = date('Y/m/d');
$productName = getPost('name');
$productDes = getPost('description');
$productPrice = getPost('price');
$productPrice = str_ireplace([','], '', $productPrice);
$productPrice = (int) $productPrice;

if ($productName != null && $productPrice != null) {
    $sql = "UPDATE PRODUCTS SET 
        name = '$productName', price = '$productPrice', description = '$productDes',
        image = '$productImg', updated_date = '$date' WHERE id = '$id'";
    execute($sql);
    header('Location: banhang.php');
}
?>
    <div class="container">
        <div class="grid">
            <div class="grid__row">
                <div class="sell-form">
                    <form action = "" method = "POST" accept=".jpg, .png" enctype = "multipart/form-data">
                         <div>
                            <h4 class="input-name">Ảnh:</h4>
                            <img src="<?= $product[
                                'image'
                            ] ?>" width="300px" height="auto" alt="">
                             <input type = "file" name = "image" >
                         </div>
                            <div>
                                <h4 class="input-name">Tên sản phẩm:</h4>
                                <input required="true" type="text" name="name" placeholder="Không quá 100 ký tự" 
                                value="<?= $product[
                                    'name'
                                ] ?>" style="width: 100%">
                            </div>
                            <div class="sell-category">
                                <h4 class="input-name">Danh mục:</h4>
                                <select name="category">
                                      <option value="">---Lựa chọn danh mục---</option>
                                <?php
                                $sql = 'SELECT * FROM category';
                                $categoryList = executeResult($sql);
                                foreach ($categoryList as $categoryItem) {
                                    echo '
                                            <option value="' .
                                        $categoryItem['id'] .
                                        '">' .
                                        $categoryItem['name'] .
                                        '</option>
                                        ';
                                }
                                ?>

                                </select>
                                </div>
                            <div class="description">
                                <h4 class="input-name">Mô tả sản phẩm:</h4>
                                <!-- <input type="text" class="description-text" name="description"> -->
                             <textarea name="description" cols="120" rows="8"><?= $product[
                                 'description'
                             ] ?></textarea>
                            </div>
                            <div>
                                <h4 class="input-name">Giá bán:</h4>
                                <input required="true" type="text" name="price" id="priceId" data-type="currency"
                                value=<?= currency_format(
                                    $product['price'],
                                    '',
                                    ','
                                ) ?> maxlength="11">
                        </div>
                        <button type="submit" class="btn" style="margin-left: 0;">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php include_once 'layouts/footer.php'; ?>

<script type="text/javascript">
        $("input[data-type='currency']").on({
    keyup: function() {
      formatCurrency($(this));
    }, });
</script>

