<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cảm ơn bạn đã đặt hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            max-width: 500px;
            width: 100%;
            box-sizing: border-box;
        }

        h1, h3, h4 {
            margin: 20px 0;
            color: #ff0000;
        }

        .icon-truck {
            color: #2ecc71; /* Màu xanh lá cây */
            font-size: 28px;
        }

        .btn {
            display: inline-block;
            margin: 30px;
            padding: 10px 30px;
            background-color: #ff73ad; /* Màu xanh dương */
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #2980b9; /* Màu xanh dương nhạt khi hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cảm ơn bạn đã đặt hàng</h1>
        <h3>Đơn hàng của bạn sẽ được giao sớm nhất <i class="fas fa-truck-fast icon-truck"></i></h3>
        <h4><a href="order_history.php">Xem chi tiết đơn hàng</a></h4>
        <h4><a class="btn" href="index.php">Tiếp tục mua hàng</a></h4>
    </div>
</body>
</html>
