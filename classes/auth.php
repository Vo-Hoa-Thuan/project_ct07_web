<?php
class Auth
{
    /* Kiểm Tra đã đăng nhập chưa */
    public static function isLoggedIn()
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
    }
    /* Kiểm tra có phải admin không */
    public static function isLoggedInAdmin()
    {
        return isset($_SESSION['admin']) && $_SESSION['admin'];
    }

    /* Bắt buộc phải đăng nhập */
    public static function requireLogin()
    {
        if (!static::isLoggedIn()) {
            // Chuyển hướng người dùng đến trang đăng nhập
            header('Location: login.php?category=login');
            exit(); // Đảm bảo dừng thực thi kịch bản sau khi chuyển hướng 
        }
    }

    /* Tạo sesstion sau khi đăng nhập */
    public static function login($user_id)
    {
        session_regenerate_id(true);
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user_id;
    }
    /* Tạo sesstion dưới quyền admin */
    public static function loginAdmin()
    {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
    }

    /* Xóa session, cookie sau khi đăng suất */
    public static function logout()
    {
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }

    /* Lấy tên người dùng */
   /* Lấy tên người dùng */
   public static function getUserName()
   {
       return isset($_SESSION['username']) ? $_SESSION['username'] : '';
   }


}
?>
