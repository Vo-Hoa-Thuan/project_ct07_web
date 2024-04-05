<?php   
require "classes/User.php";
$conn = require "inc/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $fullname = $_POST["fullname"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    // Kiểm tra xem các trường bắt buộc có được điền đầy đủ không
    if (!empty($username) && !empty($password) && !empty($fullname) && !empty($address) && !empty($email) && !empty($phone)) {
        // Kiểm tra sự tồn tại của username, email và số điện thoại trước khi thêm người dùng mới
        if (User::isValueExists($conn, 'username', $username)) {
            echo json_encode(array("success" => false, "message" => "Username đã tồn tại. Vui lòng chọn username khác."));
        } elseif (User::isValueExists($conn, 'email', $email)) {
            echo json_encode(array("success" => false, "message" => "Email đã tồn tại. Vui lòng sử dụng email khác."));
        } elseif (User::isValueExists($conn, 'phone', $phone)) {
            echo json_encode(array("success" => false, "message" => "Số điện thoại đã tồn tại. Vui lòng sử dụng số điện thoại khác."));
        } else {
            // Tạo một đối tượng User và thêm người dùng mới vào CSDL
            $newUser = new User();
            $newUser->username = $username;
            $newUser->password = $password;
            $newUser->fullname = $fullname;
            $newUser->address = $address;
            $newUser->email = $email;
            $newUser->phone = $phone;
            $newUser->role = 'admin';


            // Thêm người dùng mới vào cơ sở dữ liệu
            if ($newUser->addUser($conn)) {
                echo json_encode(array("success" => true, "message" => "Người dùng đã được thêm thành công."));
            } else {
                echo json_encode(array("success" => false, "message" => "Đã xảy ra lỗi khi thêm người dùng."));
            }
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Vui lòng điền đầy đủ thông tin."));
    }
}
?>
