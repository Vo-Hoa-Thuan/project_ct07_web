<?php  require "header.php"; ?>


<?php
function getImagesFromDirectory($directoryPath) {
    
    $imagePaths = array();

    $images = glob($directoryPath . "/*.{jpg,jpeg,png,gif}", GLOB_BRACE);

    foreach ($images as $image) {
        
        if (is_file($image) && getimagesize($image)) {
            $imagePaths[] = $image;
        }
    }    
    return $imagePaths;
}


// Hàm để lấy các loại sản phẩm từ cơ sở dữ liệu
function getProductTypes($conn) {
    $productTypes = array();

    $query = "SELECT DISTINCT type FROM Product";

    $result = $conn->query($query);

    if ($result && $result->rowCount() > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $productTypes[] = $row['type'];
    }
}

    return $productTypes;
}
?>

<?php
$imageSlide= getImagesFromDirectory("img/imageSlide");
$imageBelow= getImagesFromDirectory("img/imgbelow");
$imageBanner= getImagesFromDirectory("img/imageBanner");

// Mảng chứa các loại sản phẩm
$productTypes = getProductTypes($conn);

?>

<div class="image-slider">
    <?php foreach ($imageSlide as $image): ?>
        <div>
            <img src="<?php echo $image; ?>" alt="Slide Image">
        </div>
    <?php endforeach; ?>
</div>

<div class="image-below">
    <?php foreach ($imageBelow as $image): ?>
        <img src="<?php echo $image; ?>" alt="Slide Image">
    <?php endforeach; ?>
</div>

<?php foreach ($productTypes as $index => $productType): ?>
    <div class="container">
        <div class="banner-container">
            <?php foreach ($imageBanner as $bannerIndex => $banner): ?>
                <?php if ($bannerIndex === $index): ?>
                    <img src="<?php echo $banner; ?>" alt="Banner Image" class="banner-image">
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="content-container">
            <div class="page-header">
                <h2><?php echo $productType; ?></h2>
                <?php 
    // Tạo một biến để lưu đường dẫn xem thêm
    $viewMoreLink = "";
    ?>  
        <?php  $viewMoreLink='list_products.php?category=' . changeType($productType) ?>
    
    <?php if (!empty($viewMoreLink)): ?>
        <a href="<?php echo $viewMoreLink; ?>" class="view-more-button">Xem Thêm</a>
    <?php endif; ?>
            </div>

            <div class="slider-container" id="<?php echo strtolower(str_replace(' ', '', $productType)); ?>_slider">
                <?php
                $products = Product::getProductByType($conn, $productType);
                foreach ($products as $product):
                    ?>
                    <a href="show_product.php?id=<?php echo $product->id; ?>">
                        <div class="product-item">
                            <img src="uploads/<?php echo $product->image; ?>" alt="<?php echo $product->name; ?>">
                            <div class="product-item_name"><?php echo $product->name; ?></div>
                            <div class="product-item_price"><?php echo number_format($product->price, 0, ',', '.') . 'đ'; ?></div>
                        </div>
                    </a>
                    <?php
                endforeach;
                ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
    $(document).ready(function(){
    $('.image-slider').slick({
        dots: true,
        autoplay: true,
        autoplaySpeed: 3000,
    });
});

$(document).ready(function(){
    <?php foreach ($productTypes as $index => $productType): ?>
        $('#<?php echo strtolower(str_replace(' ', '', $productType)); ?>_slider').slick({
            slidesToShow: 4, 
            slidesToScroll: 1, 
            autoplay: true,
            autoplaySpeed: 3000, 
        
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    <?php endforeach; ?>
});
</script>

<?php require "footer.php"; ?>
