<?php require "header.php"; ?>

<?php
// Xác định đường dẫn đến thư mục chứa ảnh voucher
$imageDirectory = "vouchers/";

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$startFrom = ($page - 1) * SUBJECT_PER_PAGE;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_voucher'])) {
    $voucher_code = $_POST['delete_voucher'];
    $voucherDelete = Voucher::deleteVoucher($conn, $voucher_code);
    if ($voucherDelete) {
        // Xóa tệp ảnh tương ứng
        $imagePath = $imageDirectory . $voucher_code . ".jpg";
        if (file_exists($imagePath)) {
            unlink($imagePath); // Xóa tệp ảnh từ thư mục
        }
        // Chuyển hướng lại cùng trang để cập nhật danh sách voucher
        $remainingVouchers = Voucher::getTotal($conn);
        if ($remainingVouchers == 0 && $page > 1) {
            // Nếu không có voucher nào và trang hiện tại không phải là trang đầu tiên, chuyển hướng về trang trước đó
            $page--;
        }
        // Chuyển hướng lại cùng trang hoặc trang trước đó để cập nhật danh sách voucher
        header("Location: manage_voucher.php?page=$page");
        exit();
    } else {
        echo "Xảy ra lỗi khi xóa voucher.";
    }
}

$vouchers = Voucher::getLimite($conn, $startFrom, SUBJECT_PER_PAGE);
$totalVouchers = Voucher::getTotal($conn);
?>


<main class="app-content">
    <div class="app-title">
        <div class="breadcrumb-item">Danh sách voucher</div>
    </div>
    <div class="row_1">
        <div class="element-button">
            <button class="btnn btn-add" title="Thêm"><i class="fas fa-plus"></i>
                Tạo mới voucher</button>
            <div>
                <button class="btnn btn-delete btn-sm" type="button" title="Xóa" onclick="myFunction(this)"><i class="fas fa-trash-alt"></i> Xóa tất cả </button>
            </div>
        </div>
        <table class="table table-hover table-bordered" id="sampleTable">
            <thead>
                <tr>
                    <th width="10"><input type="checkbox" id="all"></th>
                    <th>Mã voucher</th>
                    <th>Khuyến mãi</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vouchers as $voucher) : ?>
                    <tr>
                        <td><input type="checkbox" name="check1" value="1"></td>
                        <td><?php echo $voucher['voucher_code'] ?></td>
                        <td><?php echo $voucher['discount'] ?>%</td>
                        <td>
                            <div class="button-container">
                                <form method="post" onsubmit="return confirm('Bạn có chắc chắn muốn xóa voucher này?');">
                                    <input type="hidden" name="delete_voucher" value="<?php echo $voucher['voucher_code']; ?>">
                                    <button class="btnn btn-primary btn-sm trash" type="submit" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                                </form>
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

<!-- Modal hoặc form thêm voucher mới -->
<div id="addVoucherModal" class="modal" style="display: none;">
    <div class="modal-content-voucher">
        <div class="modal-body">
            <div class="row_tabel_edit_user">
                Thêm voucher mới
            </div>
            <form id="addVoucherForm" method="POST" enctype="multipart/form-data">
                <div class="row_edit">
                    <div class="form-group_addvoucher">
                        <label class="control-label">Mã voucher</label>
                        <input class="form-control" type="text" name="voucher_code" required>
                        <label class="control-label">Khuyến mãi</label>
                        <input class="form-control" type="text" name="discount" required placeholder="Số phần trăm (%)" >
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-save" name="save_voucher" type="submit">Lưu lại</button>
                    <button class="btn-cancel" type="button" onclick="$('#addVoucherModal').hide(); $('.overlay').hide();">Hủy bỏ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="pagination">
    <?php Pagination::generatePagination($totalVouchers, SUBJECT_PER_PAGE, $page, 'manage_voucher.php'); ?>
</div>

<script>

    $(document).ready(function() {
        // Hiển thị modal hoặc form khi nhấp
        $(".btn-add").click(function() {
            $("#addVoucherModal").show();
            $(".overlay").show();
        });

        // Đóng modal hoặc form khi nhấp vào nút "Hủy bỏ" hoặc overlay
        $(".btn-cancel, .overlay").click(function() {
            $("#addVoucherModal").hide();
            $(".overlay").hide();
        });

        // Xử lý sự kiện khi nhấn nút "Lưu lại" trên modal hoặc form
        $("#addVoucherForm").submit(function(event) {
            event.preventDefault(); // Ngăn chặn gửi yêu cầu mặc định
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: "addvoucher.php", // Đường dẫn đến tập tin xử lý thêm voucher mới
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        alert(result.message);
                        location.reload(); // Tải lại trang sau khi thêm voucher mới thành công
                    } else {
                        alert(result.message);
                    }
                },
                error: function() {
                    alert("Đã xảy ra lỗi khi thêm voucher mới.");
                }
            });
        });
    });
</script>

<?php require "footer.php"; ?>