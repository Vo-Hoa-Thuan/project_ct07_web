<?php   
require "classes/Voucher.php";
$conn = require "inc/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voucher_code = $_POST["voucher_code"];
    $discount = $_POST["discount"];

    // Kiểm tra xem trường voucher_code có được điền đầy đủ không
    if (!empty($voucher_code)) {
        // Kiểm tra sự tồn tại của mã voucher trước khi thêm voucher mới
        if (Voucher::findVoucherByCode($conn, $voucher_code)) {
            echo json_encode(array("success" => false, "message" => "Mã voucher đã tồn tại. Vui lòng chọn mã voucher khác."));
        } else {
            // Tạo một đối tượng Voucher và thêm voucher mới vào CSDL
            $newVoucher = new Voucher();
            $newVoucher->voucher_code = $voucher_code;
            $newVoucher->discount = $discount;

            // Thêm voucher mới vào cơ sở dữ liệu
            if ($newVoucher->addVoucher($conn)) {
                echo json_encode(array("success" => true, "message" => "Voucher đã được thêm thành công."));
            } else {
                echo json_encode(array("success" => false, "message" => "Đã xảy ra lỗi khi thêm voucher."));
            }
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Vui lòng điền đầy đủ thông tin."));
    }
}
?>