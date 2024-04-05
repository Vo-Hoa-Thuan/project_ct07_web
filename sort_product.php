<?php
require "header.php";

// Hàm lọc và sắp xếp sản phẩm theo giá và loại sản phẩm
function filterAndSortProducts($conn, $productType, $minPrice, $maxPrice, $priceOrder, $nameOrder) {
    // Prepare SQL query
    $sql = "SELECT * FROM product WHERE type = :product_type";

    // Add price condition if minPrice and maxPrice are provided
    if ($minPrice !== null && $maxPrice !== null) {
        $sql .= " AND price BETWEEN :min_price AND :max_price";
    }

    // Add price ordering condition
    if ($priceOrder === 'asc') {
        $sql .= " ORDER BY price ASC";
    } elseif ($priceOrder === 'desc') {
        $sql .= " ORDER BY price DESC";
    } elseif ($nameOrder === 'asc') {
        $sql .= " ORDER BY name ASC";
    } elseif ($nameOrder === 'desc') {
        $sql .= " ORDER BY name DESC";
    }

    // Prepare and execute the SQL query
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":product_type", $productType, PDO::PARAM_STR);
    if ($minPrice !== null && $maxPrice !== null) {
        $stmt->bindParam(":min_price", $minPrice, PDO::PARAM_INT);
        $stmt->bindParam(":max_price", $maxPrice, PDO::PARAM_INT);
    }
    $stmt->execute();

    // Fetch and return the results
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Kiểm tra xem có dữ liệu được gửi từ form không
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Lấy các tham số từ form
    $currentProductType = isset($_GET['product_type']) ? $_GET['product_type'] : null;
    $minPrice = (isset($_GET['min_price']) && is_numeric($_GET['min_price']) && $_GET['min_price'] >= 0) ? $_GET['min_price'] : null;
    $maxPrice = (isset($_GET['max_price']) && is_numeric($_GET['max_price']) && $_GET['max_price'] >= 0) ? $_GET['max_price'] : null;
    $priceOrder = isset($_GET['price_order']) ? $_GET['price_order'] : '';
    $nameOrder = isset($_GET['name_order']) ? $_GET['name_order'] : '';

    // Thực hiện lọc và sắp xếp nếu cần
    if (!empty($currentProductType)) {
        // Lọc và sắp xếp sản phẩm theo giá và loại
        $filteredProducts = filterAndSortProducts($conn, $currentProductType, $minPrice, $maxPrice, $priceOrder, $nameOrder);
        
        // Tính toán thông tin phân trang
        $totalItems = count($filteredProducts); // Tổng số sản phẩm được lọc
        $itemsPerPage = PRODUCT_PER_PAGE; // Số sản phẩm trên mỗi trang
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1; // Trang hiện tại, mặc định là 1
        $totalPages = ceil($totalItems / $itemsPerPage); // Tổng số trang
        $startIndex = ($currentPage - 1) * $itemsPerPage; // Chỉ mục bắt đầu cho phân trang
        $urlPrefix = 'sort_product.php?product_type=' . $currentProductType . '&min_price=' . $minPrice . '&max_price=' . $maxPrice . '&price_order=' . $priceOrder . '&name_order=' . $nameOrder . '&page='; // Tiền tố URL cho các liên kết phân trang

        // Lấy sản phẩm cho trang hiện tại
        $productsPerPage = array_slice($filteredProducts, $startIndex, $itemsPerPage);
    }
}
?>

<div class="path">
    <p><a href="index.php"><?php echo $home ?></a></p>
    <p><?php echo $currentProductType ?></p>
</div>

<div class="ads">
    <img src="img/poster_<?php echo changeType($currentProductType) ?>.jpg" alt="" width="100%">
    <div class="type"><?php echo mb_strtoupper($currentProductType, 'UTF-8'); ?></div>
</div>

<form action="sort_product.php" method="get" class="filter-form">
    <div class="inline-group">
        <div class="form-group-price">
            <p>Mức giá:</p>
            <input type="hidden" name="product_type" value="<?php echo $currentProductType; ?>">
            <input type="number" name="min_price" id="min_price" placeholder="Giá tối thiểu" min="0">
            <input type="number" name="max_price" id="max_price" placeholder="Giá tối đa" min="0">
        </div>
        
        <div class="form-group_price">
            <label for="price_order">Sắp xếp theo giá:</label>
            <select name="price_order" id="price_order">
                <option value="">Không sắp xếp</option>
                <option value="asc">Giá: Thấp đến cao</option>
                <option value="desc">Giá: Cao đến thấp</option>
            </select>
        </div>
        
        <div class="form-group_price">
            <label for="name_order">Sắp xếp theo tên:</label>
            <select name="name_order" id="name_order">
                <option value="">Không sắp xếp</option>
                <option value="asc">Tên: A-Z</option>
                <option value="desc">Tên: Z-A</option>
            </select>
        </div>
    </div>

    <button type="submit">Lọc</button>
</form>

<?php 
// Hiển thị sản phẩm nếu có dữ liệu lọc
if (!empty($productsPerPage)) {
    ?>
    <div class="container">
        <?php foreach ($productsPerPage as $row) : ?>
            <a href="show_product.php?category=<?php echo changeType($currentProductType) . '_product'; ?>&id=<?php echo $row['id']; ?>">
                <div class="ps-product__content">
                    <div class="ps-product__variants">
                        <div class="ps-product__variant">
                            <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                        </div>
                    </div>
                    <div class="ps-product__detail">
                        <div class="ps-product__name">
                            <a href="#"><?php echo $row['name']; ?></a>
                        </div>
                        <span class="ps-product__price">
                            <span class="discount"><?php echo number_format($row['price'], 0, ',', '.') . 'đ'; ?></span>
                        </span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="pagination">
        <?php
        // Tạo các liên kết phân trang
        Pagination::generatePagination($totalItems, $itemsPerPage, $currentPage, $urlPrefix);
        ?>
    </div>

    <?php
} else {
    echo '<div class="not_found">';
    echo '<p>Không có sản phẩm nào phù hợp.</p>';
    echo '</div>';
}

require "footer.php";
?>

<style>
    .not_found p{
    margin-bottom: 20px;
    margin-top: 20px;
    padding: 0 100px;
    text-align: center;
    color: red;
}  

</style>