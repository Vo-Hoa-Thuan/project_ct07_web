<?
    // khởi Tạo session
    if(session_id() === '') session_start();

    /*
        Phương thức tự động load các class tương ứng
    */
    spl_autoload_register(
        function($className){
            $fileName = "/classes/" . strtolower($className) . ".php";
            $dirRoot = dirname(__DIR__);
            require $dirRoot . "{$fileName}";
        }
    );

    //hàm quản lý lỗi
   // kiểm tra xem hàm đã được đăng ký chưa
    if (!function_exists('errorHandler')) {
    function errorHandler($level, $message, $file, $line)
    {
        throw new ErrorException($message, 0, $level, $file, $line);
    }
}

//     //hàm quản lý exception
//     // kiểm tra xem hàm đã được đăng ký chưa
// if (!function_exists('exceptionHandler')) {
//     function exceptionHandler($ex)
//     {
//         if (DEBUG) {
//             echo "<h2>Lỗi</h2>";
//             echo "<p>Exception " . get_class($ex) . "</p>";
//             echo "<p>Nội dung: " . $ex->getMessage() . "</p>";
//             echo "<p>Tập tin: " . $ex->getFile() . " dòng thứ: " . $ex->getLine() . "</p>";
//         } else {
//             echo "<h2>Lỗi. Vui lòng thử lại</h2>";
//             // sau này sẽ gọi trang 404.php ở đây
//             header('Location: 404.php');
//         }
//         exit();
//     }
// }

    // //đăng ký với php
    // set_error_handler('errorHandler');
    // set_exception_handler('exceptionHandler');
?>