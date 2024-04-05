<?
// Kết nối CSDL
require "inc/init.php";
$conn = require "inc/db.php";


// Lấy dữ liệu từ form
$product_id = $_POST['id'];
$product_name = $_POST['name'];
$product_type = $_POST['type'];
$product_price = $_POST['price'];

// Gọi hàm updateProduct để cập nhật thông tin sản phẩm
if (Product::updateProduct($conn, $product_id, $product_name, $product_type, $product_price)) {
    // Nếu cập nhật thành công, điều hướng người dùng đến trang quản lý sản phẩm
    echo "<script>alert('Cập nhật thành công'); window.location='manage_product.php?category=manage';</script>";
    exit;
} else {
    // Nếu cập nhật không thành công, thông báo lỗi
    echo "Cập nhật không thành công.";
}

?>