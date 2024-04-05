<?
    // tự động gọi các class cần thiết
    require "inc/init.php";
    Auth::logout();
    header("Location: index.php");
?>