<?php require "header.php"; ?>
<!--Kết nối cơ sở dữ liệu
    $conn = require "inc/db.php"; header.php đã gọi ra -->
<?php
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    $_SESSION['quantity'] = [];
    $_SESSION['discount_price'] = 0;
};

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    // Chuẩn bị truy vấn SQL
    $stmt = $conn->prepare("SELECT * FROM product WHERE id = :id");
    // Bind tham số
    $stmt->bindParam(":id", $product_id, PDO::PARAM_INT);
    // Thực hiện truy vấn
    $stmt->execute();
    // Lấy kết quả
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['cart'][] = $product;
}

// Kiểm tra nếu có yêu cầu xóa sản phẩm
if (isset($_POST['delete_click']) && isset($_POST['delete_id'])) {
    $product_id_to_delete = $_POST['delete_id'];
    // Tìm và xóa sản phẩm từ giỏ hàng
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id_to_delete) {
            unset($_SESSION['cart'][$key]);
            unset($_SESSION['quantity'][$key]);
            break;
        }
    }
    // Chuyển hướng người dùng trở lại trang giỏ hàng sau khi xóa sản phẩm
    header("Location:cart.php?category=cart");
    exit();
}
?>

<?php
if (isset($_POST['update_click'])) {
    $new_quantity = $_POST['quantity'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if (isset($new_quantity[$key])) {
            $_SESSION['quantity'][$key] = $new_quantity[$key];
        }
    }
}
$total_price = 0;
// Kiểm tra nếu có sản phẩm trong giỏ hàng
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
        $subtotal = $item['price'] * ($_SESSION['quantity'][$key] ?? 1);
        $total_price += $subtotal; // Cập nhật tổng giá trị
    }
}

if (isset($_POST['apply_voucher'])) {
    $discount_price = 0;
    $new_quantity = $_POST['quantity'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if (isset($new_quantity[$key])) {
            $_SESSION['quantity'][$key] = $new_quantity[$key];
        }
    }
    $voucher_code = $_POST['voucher_code'];
    $result = Voucher::applyVoucher($voucher_code, $total_price, $conn);
    $discount_price = $result['discounted_price'];
    $voucher_message = $result['voucher_message'];
    $_SESSION['discount_price'] = $discount_price;
}
// Kiểm tra khi nhấn nút "Đặt hàng"
if (isset($_POST['submit_click'])) {
    try {
        $new_quantity = $_POST['quantity'];
        foreach ($_SESSION['cart'] as $key => $item) {
            if (isset($new_quantity[$key])) {
                $_SESSION['quantity'][$key] = $new_quantity[$key];
            }
        }
        $stmt = $conn->prepare("INSERT INTO order_table (user_id, name, phone, address, total) VALUES (:user_id, :name, :phone, :address, :total)");
        $stmt->bindParam(":user_id", $_SESSION['user_id']);
        $stmt->bindParam(":name", $_POST['name']);
        $stmt->bindParam(":phone", $_POST['phone']);
        $stmt->bindParam(":address", $_POST['address']);
        $total_price = ($_SESSION['discount_price'] != 0) ? $_SESSION['discount_price'] : $total_price;
        $stmt->bindParam(":total", $total_price); // Sử dụng biến total_price đã tính toán
        $stmt->execute();

        // Lấy id của đơn hàng vừa thêm
        $order_id = $conn->lastInsertId();

        // Thêm thông tin chi tiết đơn hàng vào bảng order_detail
        foreach ($_SESSION['cart'] as $key => $item) {
            $stmt = $conn->prepare("INSERT INTO order_detail (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
            $stmt->bindParam(":order_id", $order_id);
            $stmt->bindParam(":product_id", $item['id']);
            $stmt->bindParam(":quantity", $_SESSION['quantity'][$key]);
            $stmt->bindParam(":price", $item['price']);
            $stmt->execute();
        }

        // Xóa thông tin giỏ hàng sau khi đã đặt hàng thành công
        unset($_SESSION['cart']);
        unset($_SESSION['quantity']);

        // Chuyển hướng người dùng đến trang "Thank you" sau khi đặt hàng thành công
        header("Location: thanks.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>


<div class="path">
    <p><a href="index.php"><? echo $home ?></a>
        <!-- Nếu url khác rổng thì mới in ra url, 
    Ngược lại bằng rỗng thì không in ra => "/" cũng sẽ không được in ra vì last-child không có-->
    <p>Giỏ hàng</p>
</div>

<div class="container_cart">
    <?php if (!empty($_SESSION['cart'])) : ?>
        <form id="cart-form" action="cart.php?action=submit" method="POST";>
            <? if (!empty($_SESSION['cart'])) : ?>
                <table>
                    <tr class="cart_item">
                        <th class="product-number">STT</th>
                        <th class="product-name">Tên sản phẩm</th>
                        <th class="product-img">Ảnh sản phẩm</th>
                        <th class="product-price">Đơn giá</th>
                        <th class="product-quantity">Số lượng</th>
                        <th class="total-money">Thành tiền</th>
                        <th class="product-delete">&nbsp;</th>
                    </tr>
                    <? foreach ($_SESSION['cart'] as $key => $item) : ?>
                        <? $total_product = $item['price'] * ($_SESSION['quantity'][$key] ?? 1) ?>
                        <tr>
                            <td class="product-number"><?php echo $key + 1 ?></td>
                            <td class="product-name"><?php echo $item['name'] ?></td>
                            <td class="product-img"><img src="uploads/<?php echo $item['image'] ?>" alt="<?php echo $item['name'] ?>" width="50%"></td>
                            <td class="product-price"><?php echo number_format($item['price'], 0, ',', '.') . 'đ'; ?></td>
                            <td class="product-quantity">
                                <input type="text" name="quantity[<?php echo $key ?>]" value="<?php echo $_SESSION['quantity'][$key] ?? 1 ?>" />
                                <!-- Input ẩn để truyền số lượng mặc định là 1 -->
                                <input type="hidden" name="default_quantity[<?php echo $key ?>]" value="1" />
                            </td>
                            <td class="total-money">
                                <?php echo number_format($total_product, 0, ',', '.') . 'đ';
                                ?>
                            </td>
                            <td class="product-delete">
                                <form action="cart.php" method="POST">
                                    <!-- Thêm trường input ẩn chứa id của sản phẩm -->
                                    <input type="hidden" name="delete_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="delete_click"><i class="fa-solid fa-trash-can"></i> Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <? endforeach; ?>
                    <tr id="row-total">
                        <td class="product-number">&nbsp;</td>
                        <td class="product-name">Tổng tiền</td>
                        <td class="product-img">&nbsp;</td>
                        <td class="product-price">&nbsp;</td>
                        <td class="product-quantity">&nbsp;</td>
                        <td class="total-money">
                            <? echo number_format($total_price = ($_SESSION['discount_price'] != 0)? $_SESSION['discount_price'] : $total_price, 0, ',', '.') . 'đ' ?>
                        </td>
                        <td class="product-delete">&nbsp;</td>
                    </tr>

                    <!-- Thanh nhập mã voucher và nút xác nhận -->
                    <tr id="row-voucher">
                        <td colspan="5">
                            <input type="text" name="voucher_code" placeholder="Nhập mã voucher" />
                            <input class="apply_voucher red-background" type="submit" name="apply_voucher" value="Áp dụng" />
                        </td>
                        <td colspan="2">
                            <div class="voucher-message"> <?php if (isset($voucher_message)) echo $voucher_message; ?></div>                       
                        </td>
                    </tr>

                    <tr class="btn_update_cart">
                        <td colspan="7">
                            <a href="cart.php?category=cart"><input type="submit" name="update_click" value="Cập nhật giỏ hàng" /></a>
                        </td>
                    </tr>
                </table>
                <hr>
                <div><label>Người nhận: </label><input type="text" value="" name="name" /></div>
                <div><label>Điện thoại: </label><input type="text" value="" name="phone" /></div>
                <div><label>Địa chỉ: </label><input type="text" value="" name="address" /></div>

                <div class="btn_order">
                    <input type="submit" name="submit_click" value="Đặt hàng">
                </div>
            <? endif; ?>
        </form>
    <?php else : ?>

        <div class="cart_empty">
            <i class="fa-solid fa-check"></i> Chưa có sản phẩm được thêm vào giỏ hàng. Nhấn vào <a href="index.php">đây</a> để tiếp tục mua hàng.
        </div>
    <?php endif; ?>
</div>

<style>
    .voucher-message {
    color: red;
    font-weight: 600;
}

</style>

<? require "footer.php" ?>