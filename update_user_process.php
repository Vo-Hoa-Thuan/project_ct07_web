<?php
// Kết nối CSDL
require "inc/init.php";
$conn = require "inc/db.php";

// Lấy dữ liệu từ form
$user_id = $_POST['id'];
$username = $_POST['username'];
$fullname = $_POST['fullname'];
$address = $_POST['address'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Gọi hàm updateUser để cập nhật thông tin người dùng
if (User::updateUser($conn, $user_id, $username, $fullname, $address, $email, $phone)) {
    // Nếu cập nhật thành công, điều hướng người dùng đến trang quản lý người dùng hoặc trang thông báo thành công
    echo "<script>alert('Cập nhật thành công'); window.location='manage_user.php?category=manage';</script>";
    exit;
} else {
    // Nếu cập nhật không thành công, thông báo lỗi
    echo "Cập nhật không thành công.";
}
?>
