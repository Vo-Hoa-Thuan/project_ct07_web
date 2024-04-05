<?php
require "inc/init.php";
$conn = require "inc/db.php";

if(isset($_GET['query'])) {
    $search_query = $_GET['query'];

    // Thực hiện truy vấn cơ sở dữ liệu để tìm kiếm sản phẩm
    $results = Product::searchProducts($search_query, $conn);

    $num_results = count($results);

    echo '<div class="show_results"><i class="fa-solid fa-rotate-left"></i> Kết quả tìm kiếm cho "' . $search_query . '": ' . $num_results . ' KẾT QUẢ</div>';
    echo '<div class="search-results">';
    foreach($results as $result) {
        echo '<a class="search-result" href="show_product.php?id=' . $result['id'] . '">';
        echo '<img src="uploads/' . $result['image'] . '" alt="' . $result['name'] . '">';
        echo '<div class="search-result-details">';
        echo '<h3>' . $result['name'] . '</h3>';
        echo '<p>Giá: ' . number_format($result['price'], 0, ',', '.') . 'đ</p>';
        echo '</div>';
        echo '</a>';
    }
    echo '</div>';
  

    if ($num_results == 0) {
        echo '<div class="not_found">';
        echo '<p>Không tìm thấy kết quả nào cho "' . $search_query . '"</p>';
        echo '</div>';
    }
}
?>

<style>
.show_results{
    padding: 10px;
    color: #003464;
    font-weight: 400;
    font-size: 14px;
}
.show_results i{
    font-size: 12px;
}

.search-results {
    display: flex;
    flex-direction: column; 
    justify-content: flex-start;
}

.search-result {
    width: 100%; 
    padding: 10px;
    box-sizing: border-box;
    display: flex;
    border-bottom: 1px solid gray;
}

.search-result img {
    width: 80px;
    height: 80px; 
    height: auto;
    border-radius: 5px;
    margin-right: 60px;
}

.search-result-details {
    flex-grow: 1; 
}

.search-result h3 {
    margin-top: 15px;
    font-size: 14px;
    color: #333;
}

.search-result p {
    margin: 5px 0;
    font-size: 14px;
    color: #003464;
}

.not_found p{
    margin-bottom: 20px;
    margin-top: 20px;
    padding: 0 100px;
    text-align: center;
    color: red;
}  


</style>
