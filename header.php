<?php
ob_start();
require "inc/init.php";
require "classes/define.php";
$conn = require "inc/db.php";

// Gọi hàm để lấy tên người dùng từ id
if (Auth::isLoggedIn()) {
    // Lấy 'user_id' từ session
    $userId = $_SESSION['user_id'];
    // Gọi hàm để lấy tên người dùng từ 'user_id'
    $userName = User::findNameOfUserById($userId, $conn);
}

$productTypes = Product::getProductTypes($conn);
$home = "Trang chủ"; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FTL Jewelry Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" 
    integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/regsiter.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/style_cart.css">
    <link rel="stylesheet" href="css/style_show_product.css">
    <link rel="stylesheet" href="css/manage.css">
    <link rel="stylesheet" href="css/editt.css">
    <link rel="stylesheet" href="css/index.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
</head>

<body>
    <header>
        <div class="header-1">
            <div class="logo">
                <a href="index.php"><img src="img/LOGO.png" width="150px" height="100px"></a>
            </div>
            <div class="other-parent">
                <div class="other">
                        <? if(Auth::isLoggedInAdmin()): ?>
                            <li><a href="#"><i class="fa-solid fa-user-secret"></i> Admin</a></li>
                            <li><a href="manage_user.php?category=manage"><i class="fa-solid fa-users"></i> Quản lý tài khoản </a></li>
                            <li><a href="manage_product.php?category=manage"><i class="fa-solid fa-tag"></i> Quản lý sản phẩm</a></li>
                            <li><a href="manage_voucher.php"><i class="fa-solid fa-ticket"></i> Mã khuyến mãi</a></li> 
                            <li><a href="order.php?category=order"><i class="fas fa-shopping-bag"></i> Xem đơn hàng</a></li>   
                            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>        
                        <? elseif(Auth::isLoggedIn()): ?>
                            <li><a href="#"><i class="fa-solid fa-user-secret"></i> <?php echo $userName; ?></a</li>
                            <li><a href="cart.php?category=cart"><i class="fas fa-shopping-bag"></i> Giỏ hàng</a></li>
                            <li><a href="order_history.php?category=order_history&&id"><i class="fa-solid fa-clock-rotate-left"></i> Lịch sử mua hàng</a></li>
                            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
                        <? else: ?>
                            <li><a href="login.php?category=login"><i class="fas fa-user"></i> Đăng nhập</a></li>
                            <li><a href="register.php?category=regsiter"><i class="fa-solid fa-user-plus"></i> Đăng kí</a></li>
                            <li><a href="cart.php?category=cart"><i class="fas fa-shopping-bag"></i> Giỏ hàng</a></li>
                        <? endif; ?>
                </div>
            </div>
        </div>
        <div class="header-2">
            <div class="menu">
                <li><a href="index.php"><? echo $home ?></a></li>
                <?php for ($i = 0; $i < Product::countProductType($conn); $i++) : ?>
                    <li><a href="list_products.php?category=<? echo changeType($productTypes[$i])?>&page=1"><? echo $productTypes[$i] ?></a></li>
                <?php endfor; ?>
            </div>
            <div class="overlay_search" id="overlay_search_1"></div>
            <div class="overlay_search" id="overlay_search_2"></div>
                <div class="search" id="searchContainer">
                    <input type="text" placeholder="Tìm kiếm nhanh" onfocus="showSearchOverlay()">
                    <button onclick="search()"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>

                <div class="search-overlay_1" id="searchOverlay_1">
                    <div class="searchInput_0" onclick="performSearch_1()"><i class="fa-solid fa-magnifying-glass"></i></div>
                    <input class="searchInput_1" type="text" id="searchInput_1" placeholder="Nhập thông tin cần tìm kiếm">
                    <div class="searchResults" id="searchResults"></div> <!-- Đặt kết quả tìm kiếm vào đây -->
                </div>

            <div class="search-overlay_2" id="searchOverlay_2"></div>

            
        </div>
    </header>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
<script>


    document.getElementById("searchInput_1").addEventListener("keypress", function(event) {
        // Kiểm tra nếu phím Enter được nhấn
        if (event.key === "Enter" || document.getElementById("searchInput_1").value.trim() === "") {
            // Thực hiện tìm kiếm
            performSearch_1();
        }
    });


        $(document).ready(function() {
            $('#searchInput_1').keyup(function() { // Sử dụng sự kiện keyup để tìm kiếm khi nhập liệu vào input
                var query = $(this).val(); // Lấy giá trị từ input
                if (query.trim() != '') { // Kiểm tra xem có giá trị tìm kiếm không
                    $.ajax({
                        url: 'search.php',
                        type: 'GET',
                        data: { query: query },
                        success: function(response) {
                            $('#searchOverlay_2').html(response); // Thêm kết quả tìm kiếm vào phần tử #searchOverlay_2
                            $('#searchOverlay_2').slideDown(); // Hiển thị kết quả tìm kiếm bằng cách slide xuống
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                } else {
                    $('#searchOverlay_2').slideUp(); // Ẩn kết quả tìm kiếm nếu không có giá trị nhập vào
                }
            });
        });


        document.addEventListener('DOMContentLoaded', function () {
        // Lấy ra cả hai lớp mờ đục
            var overlaySearch1 = document.getElementById("overlay_search_1");
            var overlaySearch2 = document.getElementById("overlay_search_2");

            // Thêm sự kiện click cho cả hai lớp mờ đục
            overlaySearch1.addEventListener("click", function () {
                hideSearchOverlays();
            });
        
            overlaySearch2.addEventListener("click", function () {
                hideSearchOverlays();
            });
        
            function hideSearchOverlays() {
                hideSearchOverlay("searchOverlay_1", "overlay_search_1");
                hideSearchOverlay("searchOverlay_2", "overlay_search_2");
            }
        });


        function showSearchOverlay() {
            var overlay1 = document.getElementById("overlay_search_1");
            overlay1.style.display = "block"; // Hiển thị lớp mờ đục 1
            var searchOverlay1 = document.getElementById("searchOverlay_1");
            searchOverlay1.style.display = "block"; // Hiển thị lớp giả bảng tìm kiếm 1
            var searchInput1 = document.getElementById("searchInput_1");
            searchInput1.focus(); // Di chuyển con trỏ chuột vào ô nhập
    }



        function hideSearchOverlay(searchOverlayId, overlayId) {
            var searchOverlay = document.getElementById(searchOverlayId);
            searchOverlay.style.display = "none"; // Ẩn lớp giả bảng tìm kiếm

            var overlay = document.getElementById(overlayId);
            overlay.style.display = "none"; // Ẩn lớp mờ đục
        }
    </script>
</body>

</html>
