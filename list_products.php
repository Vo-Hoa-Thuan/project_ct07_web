<?php require "header.php"; ?>

<?php
// Lấy các tham số từ URL
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$productsPerPage = PRODUCT_PER_PAGE;
$startFrom = ($page - 1) * $productsPerPage;

// Lấy loại sản phẩm
$productTypes = Product::getProductTypes($conn);

foreach ($productTypes as $productType) {
    if (isset($_GET['category']) && $_GET['category'] == changeType($productType)) {
        // Lấy loại sản phẩm tương ứng với category
        $products = Product::getLimitedByType($conn, $productType, $startFrom, $productsPerPage);
        $totalProducts = Product::getTotalByType($conn, $productType);
        
        $currentProductType = $productType; 
        // Hiển thị danh sách sản phẩm
        ?>
        <div class="path">
            <p><a href="index.php"><?php echo $home ?></a></p>
            <p><?php echo $productType ?></p>
        </div>

        <div class="ads">
            <img src="img/poster_<?php echo changeType($productType) ?>.jpg" alt="" width="100%">
            <div class="type"><?php echo mb_strtoupper($productType, 'UTF-8'); ?></div>
        </div>

    
        <form action="sort_product.php" method="GET" class="filter-form">
            <div class="inline-group">
                <div class="form-group-price">
                    <p>Mức giá:</p>
                    <!-- Input hidden để gửi loại sản phẩm -->
                    <input type="hidden" name="product_type" value="<?php echo $currentProductType; ?>">
                    
                    <!-- Input để nhập giá trị tối thiểu của phạm vi giá -->
                    <input type="number" name="min_price" id="min_price" placeholder="Giá tối thiểu" min="0">
                    
                    <!-- Input để nhập giá trị tối đa của phạm vi giá -->
                    <input type="number" name="max_price" id="max_price" placeholder="Giá tối đa" min="0">
                </div>
                
                <!-- Thanh lọc theo giá -->
                <div class="form-group_price">
                    <label for="price_order">Sắp xếp theo giá:</label>
                    <select name="price_order" id="price_order">
                        <option value="">Không sắp xếp</option>
                        <option value="asc">Giá: Thấp đến cao</option>
                        <option value="desc">Giá: Cao đến thấp</option>
                    </select>
                </div>
                
                <!-- Thanh lọc theo tên -->
                <div class="form-group_price">
                    <label for="name_order">Sắp xếp theo tên:</label>
                    <select name="name_order" id="name_order">
                        <option value="">Không sắp xếp</option>
                        <option value="asc">Tên: A-Z</option>
                        <option value="desc">Tên: Z-A</option>
                    </select>
                </div>
            </div>

            <!-- Button để gửi biểu mẫu -->
            <button type="submit">Lọc</button>
        </form>



        <div class="container">
            <?php foreach ($products as $v) : ?>
                <a href="show_product.php?category=<?php echo changeType($productType) . '_product'; ?>&id=<?php echo $v->id; ?>">
                    <div class="ps-product__content">
                        <div class="ps-product__variants">
                            <div class="ps-product__variant">
                                <img src="uploads/<?php echo $v->image ?>" alt="<?php echo $v->name ?>">
                            </div>
                        </div>
                        <div class="ps-product__detail">
                            <div class="ps-product__name">
                                <a href="#"><?php echo $v->name ?></a>
                            </div>
                            <span class="ps-product__price">
                                <span class="discount"><?php echo number_format($v->price, 0, ',', '.') . 'đ'; ?></span>
                            </span>
                        </div>
                        <?php if (Auth::isLoggedIn()) : ?>
                            <a href="cart.php?category=cart&id=<?php echo $v->id; ?>"><button class="ps-product__add-to-cart">Thêm giỏ hàng</button></a>
                        <?php elseif (Auth::isLoggedInAdmin()) : ?>
                            <a href="show_product.php?category=<?php changeType($productType) . '_product'; ?>&id=<?php echo $v->id; ?>"><button class="ps-product__add-to-cart">Xem chi tiết</button></a>
                        <?php else : ?>
                            <a href="login.php?category=login"><button class="ps-product__add-to-cart">Thêm giỏ hàng</button></a>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="pagination">
            <?php
            $urlPrefix = 'list_products.php?category=' . changeType($productType);
            Pagination::generatePagination($totalProducts, $productsPerPage, $page, $urlPrefix);
            ?>
        </div>
        <?php
    }
}
?>

<?php require "footer.php"; ?>
