<?php   
require "classes/Product.php";

$conn = require "inc/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST["name"];
    $type = $_POST["type"];
    $price = $_POST["price"];

    // Xử lý tệp tin ảnh
    $image = $_FILES["image"]["name"];
    $image_tmp = $_FILES["image"]["tmp_name"];

    // Kiểm tra xem các trường bắt buộc có được điền đầy đủ không
    if (!empty($name) && !empty($price) && !empty($image)) {
        // Đoạn mã xử lý tệp tin ảnh ở đây
        $upload_dir = "uploads/"; // Thư mục lưu trữ tệp tin ảnh
        move_uploaded_file($image_tmp, $upload_dir . $image);

        // Tạo đối tượng Product
        $newProduct = new Product(null, $name, $type, $price, $image);

        // Thêm sản phẩm mới vào cơ sở dữ liệu
        if ($newProduct->addProduct($conn, $name,$type,$price,$image)) {
            echo json_encode(array("success" => true, "message" => "Sản phẩm đã được thêm thành công."));
        } else {
            echo json_encode(array("success" => false, "message" => "Đã xảy ra lỗi khi thêm sản phẩm."));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Vui lòng điền đầy đủ thông tin."));
    }
}
?>
