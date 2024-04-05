<?php
require "header.php";

// Lấy thông tin của tất cả đơn hàng từ bảng order_table
$stmt = $conn->query("SELECT * FROM order_table");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="app-content">
    <?php if (empty($orders)) : ?>
        <div class="cart_empty">
            <i class="fa-solid fa-check"></i> Hiện chưa có đơn hàng nào được đặt <i class="fa-solid fa-heart-crack"></i>
        </div>
    <?php else : ?>
        <div class="app-title">
            <div class="breadcrumb-item">Danh sách đơn hàng</div>
        </div>
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

<?php
require "footer.php";
?>