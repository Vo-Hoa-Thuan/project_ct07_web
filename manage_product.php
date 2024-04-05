<?php require "header.php"; ?>

<?php
// Xác định đường dẫn đến thư mục chứa ảnh sản phẩm
$imageDirectory = "uploads/";

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$startFrom = ($page - 1) * SUBJECT_PER_PAGE;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $product_id = $_POST['delete_product'];

    // Lấy tên tệp ảnh từ cơ sở dữ liệu
    $imageName = Product::getImageNameById($conn, $product_id);

    // Kiểm tra xem có tên tệp ảnh không
    if ($imageName) {
        $imagePath = $imageDirectory . $imageName; // Đường dẫn đến tệp ảnh

        // Kiểm tra xem tệp ảnh tồn tại trong thư mục không
        if (file_exists($imagePath)) {
            // Nếu tồn tại, thì xóa tệp ảnh trước
            if (unlink($imagePath)) {
                // Tiếp tục xóa bản ghi trong cơ sở dữ liệu nếu xóa tệp ảnh thành công
                $productDelete = Product::deleteProduct($conn, $product_id);
                if ($productDelete) {
                    // Bản ghi và tệp ảnh đã được xóa thành công
                    // Tiếp tục với các bước khác nếu cần
                } else {
                    echo "Xảy ra lỗi khi xóa sản phẩm.";
                }
            } else {
                echo "Không thể xóa tệp ảnh.";
            }
        } else {
            echo "Không tìm thấy tệp ảnh.";
        }
    }
}


$product = Product::getLimite($conn, $startFrom, SUBJECT_PER_PAGE);
$totalProducts = Product::getTotal($conn);
?>


<main class="app-content">
    <div class="app-title">
        <div class="breadcrumb-item">Danh sách sản phẩm</div>
    </div>
    <div class="row_1">
        <div class="element-button">
            <button class="btnn btn-add" title="Thêm"><i class="fas fa-plus"></i>
                Tạo mới sản phẩm</button>
            <div>
                <button class="btnn btn-delete btn-sm" type="button" title="Xóa" onclick="myFunction(this)"><i class="fas fa-trash-alt"></i> Xóa tất cả </button>
            </div>
        </div>
        <table class="table table-hover table-bordered" id="sampleTable">
            <thead>
                <tr>
                    <th width="10"><input type="checkbox" id="all"></th>
                    <th>Mã sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Ảnh</th>
                    <th>Giá tiền</th>
                    <th>Danh mục</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($product as $r => $v) : ?>
                    <tr>
                        <td><input type="checkbox" name="check1" value="1"></td>
                        <td><?php echo $v->id ?></td>
                        <td><?php echo $v->name ?></td>
                        <td><img src="uploads/<?php echo $v->image ?>" alt="" width="100px;"></td>
                        <td><?php echo number_format($v->price, 0, ',', '.') . 'đ'; ?></td>
                        <td><?php echo $v->type ?></td>
                        <td>
                            <div class="button-container">
                                <form method="post" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                    <input type="hidden" name="delete_product" value="<?php echo $v->id; ?>">
                                    <button class="btnn btn-primary btn-sm trash" type="submit" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                                </form>
                                <button class="btnn btn-primary btn-sm edit" type="button" title="Sửa" onclick="loadEditForm(<?php echo $v->id; ?>)"><i class="fas fa-edit"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<div id="editFormContainer"></div>
<div class="overlay"></div>

<?
$productTypes = Product::getProductTypes($conn);

?>

<!-- Modal hoặc form thêm sản phẩm mới -->
<div id="addProductModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-body">
            <div class="row_tabel_edit">
                Thêm sản phẩm mới
            </div>
            <form id="addProductForm" method="POST" enctype="multipart/form-data">
                <div class="row_edit">
                    <div class="form-group">
                        <label class="control-label">Tên sản phẩm</label>
                        <input class="form-control" type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleSelect1" class="control-label">Loại sản phẩm</label>
                        <select class="form-control" id="exampleSelect1" name="type" onchange="checkOtherType(this)" required>
                            <?php for ($i = 0; $i < Product::countProductType($conn); $i++) : ?>
                                <option value="<?php echo $productTypes[$i] ?>"><?php echo $productTypes[$i] ?></option>
                            <?php endfor; ?>
                            <option value="Other">Khác...</option>
                        </select>
                        <input type="text" id="newTypeInput" name="newType" style="display: none;" required placeholder="Nhập loại sản phẩm">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Giá sản phẩm</label>
                        <input class="form-control" type="number" name="price" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Hình ảnh</label>
                        <input class="form-control" type="file" name="image" required accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-save" name="save" type="submit">Lưu lại</button>
                    <button class="btn-cancel" type="button" onclick="$('#addProductModal').hide(); $('.overlay').hide();">Hủy bỏ</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="pagination">
    <?php Pagination::generatePagination($totalProducts, SUBJECT_PER_PAGE, $page, 'manage_product.php?category=manage'); ?>
</div>


<script>
    $(document).ready(function() {
        // Bắt sự kiện click bên ngoài modal để đóng modal
        $(document).click(function(event) {
            var modal = $('#editFormContainer');
            if (!modal.is(event.target) && modal.has(event.target).length === 0) {
                modal.hide();
            }
        });

        function loadEditForm(productId) {
            // Gửi yêu cầu AJAX để lấy biểu mẫu sửa sản phẩm từ máy chủ
            $.ajax({
                url: 'editproduct.php', // Đường dẫn đến tập tin xử lý lấy biểu mẫu sửa sản phẩm
                type: 'GET',
                data: {
                    id: productId
                },
                success: function(response) {
                    // Hiển thị biểu mẫu sửa sản phẩm trong phần tử editFormContainer
                    $('.overlay').show();
                    $('#editFormContainer').html(response);
                    $('#editFormContainer').show(); // Hiển thị modal sau khi nội dung đã được tải
                },
                error: function() {
                    alert('Đã xảy ra lỗi khi tải biểu mẫu sửa sản phẩm.');
                }
            });
        }

        window.loadEditForm = loadEditForm; // Đặt hàm loadEditForm thành toàn cục để có thể gọi từ bên ngoài
    });


    $(document).ready(function() {
        // Hiển thị modal hoặc form khi nhấp vào nút "Thêm sản phẩm mới"
        $(".btn-add").click(function() {
            $("#addProductModal").show();
            $(".overlay").show();
        });

        // Đóng modal hoặc form khi nhấp vào nút "Hủy bỏ" hoặc overlay
        $(".btn-cancel, .overlay").click(function() {
            $("#addProductModal").hide();
            $(".overlay").hide();
        });

        // Xử lý sự kiện khi nhấn nút "Lưu lại" trên modal hoặc form
        $("#addProductForm").submit(function(event) {
            event.preventDefault(); // Ngăn chặn gửi yêu cầu mặc định
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: "addproducts.php", // Đường dẫn đến tập tin xử lý thêm sản phẩm mới
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        alert(result.message);
                        location.reload(); // Tải lại trang sau khi thêm sản phẩm mới thành công
                    } else {
                        alert(result.message);
                    }
                },
                error: function() {
                    alert("Đã xảy ra lỗi khi thêm sản phẩm mới.");
                }
            });
        });
    });

    function checkOtherType(select) {
        var inputField = document.getElementById("newTypeInput");
        if (select.value === "Other") {
            inputField.style.display = "block";
            inputField.required = true;
        } else {
            inputField.style.display = "none";
            inputField.required = false;
        }
    }

      // Thêm vào để cập nhật giá trị của tùy chọn "Khác..." trong dropdown menu
      document.getElementById("newTypeInput").addEventListener("input", function() {
        var selectElement = document.getElementById("exampleSelect1");
        var otherOption = selectElement.options[selectElement.options.length - 1];
        otherOption.value = this.value; // Cập nhật giá trị của tùy chọn "Khác..."
        otherOption.selected = true; // Chọn tùy chọn "Khác..." trong dropdown menu
    });x
</script>

<?php require "footer.php"; ?>