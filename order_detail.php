<?php require "header.php"; ?>

<?php
// Lấy ID đơn hàng từ tham số truyền vào
$order_id = $_GET['id'];

// Lấy thông tin của đơn hàng từ bảng order_table
$stmt = $conn->prepare("SELECT * FROM order_table WHERE id = :id");
$stmt->bindParam(":id", $order_id, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra xem đơn hàng có tồn tại không
if (!$order) {
    // Nếu không tồn tại, chuyển hướng người dùng đến trang không tìm thấy
    header("Location: not_found.php");
    exit();
}

// Lấy thông tin chi tiết sản phẩm từ bảng order_detail và kết hợp với thông tin từ bảng product
$stmt = $conn->prepare("
    SELECT od.quantity, od.price, p.name AS product_name, p.image
    FROM order_detail od
    JOIN product p ON od.product_id = p.id
    WHERE od.order_id = :order_id
");
$stmt->bindParam(":order_id", $order_id, PDO::PARAM_INT);
$stmt->execute();
$order_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="app-content">
    <?php if (empty($order_details)) : ?>
        <div class="cart_empty">
            <i class="fa-solid fa-check"></i> Hiện chưa có đơn hàng nào được đặt <i class="fa-solid fa-heart-crack"></i>
        </div>
    <?php else : ?>
            <div class="breadcrumb-item">Danh sách sản phẩm</div>
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Ảnh sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_details as $index => $detail) : ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><img src="uploads/<?php echo $detail['image']; ?>" alt="<?php echo $detail['product_name']; ?>" width="100"></td>
                            <td><?php echo $detail['product_name']; ?></td>
                            <td><?php echo number_format($detail['price'], 0, ',', '.') . 'đ'; ?></td>
                            <td><?php echo $detail['quantity']; ?></td>
                            <td><?php echo number_format($detail['price'] * $detail['quantity'], 0, ',', '.') . 'đ'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <div class="return-container">
            <? if (Auth::isLoggedIn()): ?>
                <a href="order_history.php?category=order" class="return">Trở lại</a>
            <? elseif (Auth::isLoggedInAdmin()): ?>
                <a href="order.php?category=order" class="return">Trở lại</a>
            <? endif;?>
        </div>
    <?php endif; ?>
</div>


<?php require "footer.php"; ?>