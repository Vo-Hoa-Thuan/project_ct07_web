<?php require "header.php"; ?>

<?php
if(isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $product = Product::showProduct($conn, $product_id);
}  
?>
<!-- Hiển thị thông tin chi tiết của sản phẩm -->
<div class="path">
        <p><a href="index.php"><? echo $home ?></a></p>
    <!-- Nếu url khác rổng thì mới in ra url, 
    Ngược lại bằng rỗng thì không in ra => "/" cũng sẽ không được in ra vì last-child không có-->
        <p><a href="list_products.php?category=<? echo changeType($product['type'])?>&page=1"><? echo $product['type'] ?></a></p>
        <p><? echo $product['name'] ?></p>
</div>

<div class="container__show_product">
    <div class="product__image">
        <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
        <div class="icon_image"><i class="fa-regular fa-images"></i></div>
            <div class="overlay"></div>
            <div class="close-icon" onclick="closeOverlay()">
                <i class="fa-solid fa-xmark"></i>
            </div>
        <p>Hình ảnh</p>
    </div>
    <div class="product__info">
        <div class="product_name"><?php echo $product['name']; ?></div>
        <div class="product_type">Loại: <?php echo $product['type']; ?></div>
        <div class="product_price">Giá: <?php echo number_format($product['price'], 0, ',', '.'); ?>đ</div>
        <div class="stocking">
            Còn hàng - 
            <span class="highlight">Gọi Hotline 1800545457 (Free)</span>
            Ưu đãi độc quyền
        </div>

        <div class="product_endow">
            <div class="product_endow__header">Ưu đãi:</div>
            <div class="product_endow__footer">
                <img src="img/Logo_KMA.png" alt="">Giảm tới 20% khi là sinh viên 
                <a href="https://actvn.edu.vn/">
                    <span class="highlight">Học Viện Kĩ Thuật Mật Mã</span>
                </a>
            </div>
        </div>

       
        <?  if (Auth::isLoggedIn()) : ?> 
            <a href="cart.php?category=cart&&id=<?php echo $product['id']; ?>">
                <div class="buy_product">
                    <h3>Mua ngay</h3>
                    <h4>Miễn phí giao hàng tận nhà</h4>
                </div>
            </a>
            <? elseif (!Auth::isLoggedIn() && !Auth::isLoggedInAdmin()):?>
                <a href="login.php?category=login">
                <div class="buy_product">
                    <h3>Mua ngay</h3>
                    <h4>Miễn phí giao hàng tận nhà</h4>
                </div>
                </a>
            <? endif; ?>

        <div class="product_policy">
            <p><i class="fa-solid fa-circle-check"></i> Giá sản phẩm thay đổi tuỳ trọng lượng vàng và đá</p>
            <p><i class="fa-solid fa-circle-check"></i> Đổi sản phẩm trong 48h tại hệ thống cửa hàng FTL</p>
            <p><i class="fa-solid fa-circle-check"></i> Miễn phí giao siêu tốc 3H FTLFast, trễ tặng voucher 100K, xem thêm Chính sách giao hàng</p>
        </div>
    </div>
</div>

<div class="description">
    <div class="btn_show_description" onclick="toggleDescription(this)">
        <span class="description-text">Thông số và mô tả</span>
        <i class="fa-solid fa-chevron-down"></i>
    </div>
    
    <div class="description-content">
        <div class="description-label">Trọng lượng tham khảo: 6.24872 phân</div> 
        <div class="description-label">Hàm lượng chất liệu: 4160</div> 
        <div class="description-label">Loại đá chính: Synthetic</div> 
        <div class="description-label">Loại đá phụ: Synthetic</div>
        <div class="description-label">Bộ sưu tập: You're the apple of my eye</div> 
        <div class="description-label">Thương hiệu: SANRIO</div> 
        <div class="description-label_end"><br><?php echo $product['name']; ?> từ BST You are The Apple of My Eye chính là biểu tượng hoàn hảo cho tình cảm chân thành và sự trân trọng.
        <br>
        <br>
        Nét đẹp tinh tế cùng thông điệp ý nghĩa sẽ khiến món quà này trở thành điểm nhấn khó phai trong lòng người nhận. <?php echo $product['name']; ?> từ BST The Apple of My Eye thay lời bạn nói lời yêu thương và trân trọng đến người quan trọng!
        </div>
    </div>
</div>

<div class="description">
    <div class="btn_show_description" onclick="toggleDescription(this)">
        <span class="description-text">Dịch vụ sau mua</span>
        <i class="fa-solid fa-chevron-down"></i>
    </div>
    
    <div class="description-content">
        <ul>Bảo hành miễn phí 6 tháng
            <li>Bảo hành 6 tháng lỗi kỹ thuật, nước xi.</li>
        </ul>
        <ul>Miễn phí siêu âm và đánh bóng bằng máy chuyên dụng trọn đời
            <li><i class="fa-solid fa-circle"></i>Đối với sản phẩm bị oxy hóa, xuống màu, sẽ được siêu âm làm sạch bằng máy chuyên dụng (siêu âm, không xi) miễn phí trọn đời tại cửa hàng.</li>
            <li><i class="fa-solid fa-circle"></i>Miễn phí đánh bóng trọn đời. Nhẫn cưới sẽ được bảo hành, làm mới, đánh bóng, xi miễn phí trọn đời.</li>
        </ul>

        <ul>Miễn phí thay đá CZ và đá tổng hợp trong suốt thời gian bảo hành.
            <li>* Không áp dụng bảo hành cho các trường hợp sau:</li>
            <ul>
                <li>- Dây chuyền, lắc chế tác bị đứt gãy; bị biến dạng hoặc hư hỏng nặng.</li>
                <li>- Khách hàng cung cấp thông tin truy lục hóa đơn không chính xác.</li>
            </ul>
        </ul>
        
        <ul class="note">
        <p><strong>Lưu ý:</strong></p>
            <li><i class="fa-solid fa-circle"></i>  FTL bảo hành các sản phẩm thuộc hệ thống cửa hàng kênh lẻ và online của FTL.</li>
            <li><i class="fa-solid fa-circle"></i>  Chế độ bảo hành sản phẩm có thể thay đổi theo chính sách của FTL đối với các dòng hàng và chương trình khuyến mãi vào từng thời điểm.</li>
        </ul>
    </div>
</div>

<div class="description">
    <div class="btn_show_description" onclick="toggleDescription(this)">
        <span class="description-text">Câu hỏi thường gặp</span>
        <i class="fa-solid fa-chevron-down"></i>
    </div>
    
    <div class="description-content">
        <ul>Mua Online có ưu đãi gì đặc biệt cho tôi?
            <li>FTL mang đến nhiều trải nghiệm mua sắm hiện đại khi mua Online:</li>
            <li>- Ưu đãi độc quyền Online với hình thức thanh toán đa dạng.</li>
            <li>- Đặt giữ hàng Online, nhận tại cửa hàng.</li>
            <li>- Miễn phí giao hàng từ 1-7 ngày trên toàn quốc và giao hàng trong 3 giờ tại một số khu vực trung tâm với các sản phẩm có gắn nhãn.</li>
            <li>- Trả góp 0% lãi suất với đơn hàng từ 3 triệu.</li>
            <li>- Làm sạch trang sức trọn đời, khắc tên miễn phí theo yêu cầu (tùy kết cấu sản phẩm) và chính sách bảo hành, đổi trả dễ dàng tại hệ thống FTL trên toàn quốc.</li>
            <li>FTL hân hạnh phục vụ quý khách qua Hotline 1800 5454 57 (08:00-21:00, miễn phí cuộc gọi).</li>
        </ul>
        <br>
        <ul>FTL có thu mua lại trang sức không?
            <li>FTL có dịch vụ thu đổi trang sức FTL tại hệ thống cửa hàng trên toàn quốc.
        </ul>
        <br>
        <ul>Nếu đặt mua Online mà sản phẩm không đeo vừa thì có được đổi không?
            <li>FTL có chính sách thu đổi trang sức vàng trong vòng 48 giờ, đổi ni/ size trang sức bạc trong vòng 72 giờ. Quý khách sẽ được áp dụng đổi trên hệ thống FTL toàn quốc.</li>
        </ul>
        <br>
        <ul>Sản phẩm đeo lâu có xỉn màu không, bảo hành như thế nào?
            <li>Do tính chất hóa học, sản phẩm có khả năng oxy hóa, xuống màu. FTL có chính sách bảo hành miễn phí về lỗi kỹ thuật, nước xi:</li>
            <li>- Trang sức vàng: 6 tháng.</li>
            <li>- Trang sức bạc: 3 tháng.</li>
            <li>Ngoài ra, FTL cũng cung cấp dịch vụ siêu âm làm sạch bằng máy chuyên dụng (siêu âm, không xi) miễn phí trọn đời tại hệ thống cửa hàng.</li>
        </ul>
        <br>
        <ul>Tôi muốn xem trực tiếp, cửa hàng nào còn hàng?
            <li>Với hệ thống cửa hàng trải rộng khắp toàn quốc, quý khách vui lòng liên hệ Hotline 1800 5454 57 (08:00-21:00, miễn phí cuộc gọi) để kiểm tra cửa hàng còn hàng và tư vấn chương trình khuyến mãi Online trước khi đến cửa hàng.</li>
        </ul>
    </div>
</div>




<script>
    function toggleDescription(button) {
        var description = button.nextElementSibling;
        var icon = button.querySelector('i');

        if (description.style.display === 'none' || description.style.display === '') {
            description.style.display = 'block';
            icon.className = 'fa-solid fa-xmark'; // Thay đổi class của icon
            button.querySelector('.description-text').classList.add('bold-text'); // Thêm kiểu in đậm cho chữ
        } else {
            description.style.display = 'none';
            icon.className = 'fa-solid fa-chevron-down'; // Chuyển lại icon ban đầu
            button.querySelector('.description-text').classList.remove('bold-text'); // Loại bỏ kiểu in đậm cho chữ
        }
    }

    // Lấy phần tử overlay và ảnh lớn
    var overlay = document.querySelector('.overlay');
    var largeImage = document.createElement('img');

    // Thêm sự kiện click cho ảnh và icon ảnh
    var productImage = document.querySelector('.product__image img');
    var imageIcon = document.querySelector('.icon_image');

    productImage.addEventListener('click', function() {
        showLargeImage(productImage.src);
    });

    imageIcon.addEventListener('click', function() {
        showLargeImage(productImage.src);
    });

    // Hàm hiển thị ảnh lớn
    function showLargeImage(src) {
        // Đặt src cho ảnh lớn
        largeImage.src = src;

        // Thêm ảnh lớn vào overlay
        overlay.innerHTML = ''; // Xóa nội dung cũ
        overlay.appendChild(largeImage);

        // Hiển thị overlay
        overlay.style.display = 'flex';
    }

    // Sự kiện click để đóng overlay khi click ra ngoài
    overlay.addEventListener('click', function(event) {
        if (event.target === overlay) {
            // Ẩn overlay
            overlay.style.display = 'none';
        }
    });

    function toggleOverlay() {
    var overlay = document.querySelector('.overlay');
    overlay.classList.toggle('show');
    var closeIcon = document.querySelector('.close-icon');
    closeIcon.style.display = overlay.classList.contains('show') ? 'block' : 'none';
}


    function closeOverlay() {
        overlay.style.display = 'none'; // Ẩn overlay
        var closeIcon = document.querySelector('.close-icon');
        closeIcon.style.display = 'none';
    }

</script>

<? require "footer.php"; ?>