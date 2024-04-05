<?php require "header.php"; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (User::authenticateAdmin($conn, $username, $password)) {
        Auth::loginAdmin();
        echo "<script>alert('Chào mừng $username đến với trang quản trị'); window.location='index.php';</script>";
    } else if (User::authenticate($conn, $username, $password)) {
        $user_id = User::findUserdId($conn, $username);
        Auth::login($user_id);
        echo "<script>alert('Xin chào $username'); window.location='index.php';</script>";
    } else {
        Dialog::show('Sai tên đăng nhập hoặc mật khẩu');
    }
}
?>

<div class="container_login">
    <h2>Đăng nhập</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="username">Tên người dùng:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button class="btn_login" type="submit" name="submit">Đăng nhập</button>
        <div class="move_regsiter">
            <a href="register.php?category=register">Đăng kí tài khoản</a>
        </div>
    </form>
</div>

<?php require "footer.php"; ?>