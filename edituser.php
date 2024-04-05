<?
require "inc/init.php";
// Kết nối cơ sở dữ liệu và đảm bảo session đã được khởi tạo
$conn = require "inc/db.php";

$userId = $_GET['id'];   
$user = User::showUser($conn, $userId);

?>

<form method="POST" action="update_user_process.php">
    <div class="modal-content">
        <div class="modal-body">
            <div class="row_tabel_edit_user">
                Chỉnh sửa thông tin
            </div>
            <div class="row_edit">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                <div class="form-group_user">
                    <label class="control-label">Tên đăng nhập</label>
                    <input class="form-control" type="text" name="username" value="<?php echo $user['username']; ?>" required>
                </div>
                <div class="form-group_user">
                    <label class="control-label">Họ và tên</label>
                    <input class="form-control" type="text" name="fullname" value="<?php echo $user['fullname']; ?>">
                </div>
                <div class="form-group_user">
                    <label class="control-label">Địa chỉ</label>
                    <input class="form-control" type="text" name="address" value="<?php echo $user['address']; ?>">
                </div>
                <div class="form-group_user">
                    <label class="control-label">Email</label>
                    <input class="form-control" type="email" name="email" value="<?php echo $user['email']; ?>" required>
                </div>
                <div class="form-group_user">
                    <label class="control-label">Số điện thoại</label>
                    <input class="form-control" type="text" name="phone" value="<?php echo $user['phone']; ?>" required>
                </div>
                <!-- Bạn có thể thêm các trường thông tin người dùng khác ở đây -->
            </div>
            <BR>
                <button class="btn-save" name="save" type="submit">Lưu lại</button>
                <button class="btn-cancel" name="abort" data-dismiss="modal"  id="cancelButton">Hủy bỏ</button>
            <BR>
        </div>
        <div class="modal-footer"></div>
    </div>
</form>

<script>
    
    document.getElementById('cancelButton').addEventListener('click', function(event) {
    event.preventDefault(); // Ngăn chặn hành động mặc định của nút "Hủy bỏ"
    document.getElementById('editFormContainer').style.display = 'none'; // Ẩn phần tử modal
    document.getElementsByClassName('overlay')[0].style.display = 'none'; // Ẩn overlay
    });
</script>
