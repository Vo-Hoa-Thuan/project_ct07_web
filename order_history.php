<?php
require "header.php";

// Lấy user_id
$user_id = $_SESSION['user_id'];
// Lấy thông tin của tất cả đơn hàng từ bảng order_table
$stmt = $conn->prepare("SELECT * FROM order_table WHERE user_id = :user_id");
$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="path">
    <p><a href="index.php"><? echo $home ?></a>
    <p>Lịch sử mua hàng</p>
</div>

<div class="app-content">
    <?php if (empty($orders)) : ?>
        <div class="cart_empty">
            <i class="fa-solid fa-check"></i> Hiện chưa có đơn hàng nào được đặt. Nhấn vào <a href="index.php">đây</a> để tiếp tục mua hàng.
        </div>
    <?php else : ?>
        <div class="breadcrumb-item">Danh sách đơn hàng bạn đã đặt</div>
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Người nhận</th>
                    <th>Điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Thời gian</th>
                    <th>Tổng tiền</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $index => $order) : ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $order['name']; ?></td>
                        <td><?php echo $order['phone']; ?></td>
                        <td><?php echo $order['address']; ?></td>
                        <td><?php echo $order['order_date']; ?></td>
                        <td><?php echo number_format($order['total'], 0, ',', '.') . 'đ'; ?></td>
                        <td><a class="seeAll" href="order_detail.php?category=order_detail&id=<?php echo $order['id']; ?>">Xem chi tiết</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<style>
    .seeAll {
        color: #1071be;
        text-align: center;
    }

    .title_item {
        text-align: center;
    }
</style>

<?php
require "footer.php";
?>