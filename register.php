<?php require "header.php"; ?>

<?php
// Khởi tạo biến lưu trữ thông báo lỗi mặc định
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Kiểm tra username và password có trống không
    if (empty($username)) {
        $error_message = "Username không được để trống";
    } elseif (empty($password)) {
        $error_message = "Password không được để trống";
    } elseif (strpos($username, ' ') !== false) {
        $error_message = "Username không được chứa khoảng trắng";
    } elseif (strpos($password, ' ') !== false) {
        $error_message = "Password không được chứa khoảng trắng";
    } else {
        // Kiểm tra và thực hiện kết nối CSDL
        require "classes/user.php";
        
        // Kiểm tra sự tồn tại của username, email và số điện thoại trước khi thêm người dùng mới
        if (User::isValueExists($conn, 'username', $username)) {
            $error_message = "Username đã tồn tại. Vui lòng chọn username khác.";
        } elseif (User::isValueExists($conn, 'email', $email)) {
            $error_message = "Email đã tồn tại. Vui lòng sử dụng email khác.";
        } elseif (User::isValueExists($conn, 'phone', $phone)) {
            $error_message = "Số điện thoại đã tồn tại. Vui lòng sử dụng số điện thoại khác.";
        } else {
            // Kiểm tra định dạng của username và email
            $username_error = User::validateUsername($username);
            $email_error = User::validateEmail($email);
            if (!empty($username_error)) {
                $error_message = $username_error;
            } elseif (!empty($email_error)) {
                $error_message = $email_error;
            } else {
                // Tạo một đối tượng User và thêm người dùng mới vào CSDL
                $user = new User();
                $user->username = $username;
                $user->password = $password;
                $user->fullname = $fullname;
                $user->address = $address;
                $user->email = $email;
                $user->phone = $phone;
                $user->role = 'user';

                if ($user->addUser($conn)) {
                    // Hiển thị thông báo thành công và chuyển hướng người dùng đến trang đăng nhập
                    echo "<script>alert('Đăng kí thành công'); window.location='login.php?category=login';</script>";
                    exit;
                } else {
                    echo "<script>alert('Đăng kí thành công'); window.location='register.php?category=register';</script>";
                }
            }
        }
    }
}
?>

<!-- Form đăng kí -->
<div class="bg-gra-02 font-poppins">
    <div class="wrapper wrapper--w680">
        <div class="card card-4">
            <div class="card-body">
                <h2 class="title">Đăng kí</h2>
                <?php if (!empty($error_message)): ?>
                <div id="error-message" style="color: red; font_size: 14px; font-weight: bold;"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <form id="register-form" method="POST" onsubmit="return validateForm()">
                    <div class="row row-space">
                        <div class="col-2">
                            <div class="input-group">
                                <label class="label">Username</label>
                                <input minlength="5" maxlength="20" class="input--style-4" type="text" name="username">
                                <div class="error-message"></div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="input-group">
                                <label class="label">Password</label>
                                <input  minlength="8" maxlength="50" class="input--style-4" type="password" name="password">
                                <div class="error-message"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-space">
                        <div class="col-2">
                            <div class="input-group">
                                <label class="label">Fullname</label>
                                <input  minlength="5" maxlength="100" class="input--style-4" type="text" name="fullname">
                                <div class="error-message"></div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="input-group">
                                <label class="label">Address</label>
                                <input  minlength="5" maxlength="100" class="input--style-4" type="text" name="address">
                                <div class="error-message"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-space">
                        <div class="col-2">
                            <div class="input-group">
                                <label class="label">Email</label>
                                <input  minlength="10" maxlength="50" class="input--style-4" type="email" name="email">
                                <div class="error-message"></div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="input-group">
                                <label class="label">Phone Number</label>
                                <input  minlength="10" maxlength="10" class="input--style-4" type="text" name="phone">
                                <div class="error-message"></div>
                            </div>
                        </div>
                    </div>
                    <div class="p-t-15">
                        <button name='submit' class="btn btn--radius-2 btn--blue" type="submit">Đăng Ký</button>
                    </div>
                    <div class="return_login">
                        <a href="login.php?category=login">Bạn đã có tài khoản ?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="js/checkinput.js"></script>

<?php require "footer.php";?>
