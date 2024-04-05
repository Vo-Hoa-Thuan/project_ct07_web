<?php require "header.php"; ?>

<?php
// Xác định đường dẫn đến thư mục chứa ảnh người dùng
$imageDirectory = "avatars/";

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$startFrom = ($page - 1) * SUBJECT_PER_PAGE;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['delete_user'];
    $userDelete = User::deleteUser($conn, $user_id);
    if ($userDelete) {
        // Xóa tệp ảnh tương ứng
        $imagePath = $imageDirectory . $user_id . ".jpg";
        if (file_exists($imagePath)) {
            unlink($imagePath); // Xóa tệp ảnh từ thư mục
        }
        // Chuyển hướng lại cùng trang để cập nhật danh sách người dùng
        $remainingUsers = User::getTotal($conn);
        if ($remainingUsers == 0 && $page > 1) {
            // Nếu không có người dùng nào và trang hiện tại không phải là trang đầu tiên, chuyển hướng về trang trước đó
            $page--;
        }
        // Chuyển hướng lại cùng trang hoặc trang trước đó để cập nhật danh sách người dùng
        header("Location: manage_user.php?page=$page");
        exit();
    } else {
        echo "Xảy ra lỗi khi xóa người dùng.";
    }
}

$users = User::getLimite($conn, $startFrom, SUBJECT_PER_PAGE);
$totalUsers = User::getTotal($conn);
?>


<main class="app-content">
    <div class="app-title">
        <div class="breadcrumb-item">Danh sách tài khoản</div>
    </div>
    <div class="row_1">
        <div class="element-button">
            <button class="btnn btn-add" title="Thêm"><i class="fas fa-plus"></i>
                Tạo mới quản lý</button>
            <div>
                <button class="btnn btn-delete btn-sm" type="button" title="Xóa" onclick="myFunction(this)"><i class="fas fa-trash-alt"></i> Xóa tất cả </button>
            </div>
        </div>
        <table class="table table-hover table-bordered" id="sampleTable">
            <thead>
                <tr>
                    <th width="10"><input type="checkbox" id="all"></th>
                    <th>Mã người dùng</th>
                    <th>Tên đăng nhập</th>
                    <th>Tên người quản lý</th>
                    <th>Địa chỉ</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Quyền</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><input type="checkbox" name="check1" value="1"></td>
                        <td><?php echo $user->id ?></td>
                        <td><?php echo $user->username ?></td>
                        <td><?php echo $user->fullname ?></td>
                        <td><?php echo $user->address ?></td>
                        <td><?php echo $user->email ?></td>
                        <td><?php echo $user->phone ?></td>
                        <? $role = ($user->role == 'admin') ? 'Quản lý' : 'Người dùng'; ?>
                        <td><?php echo $role ?></td>
                        <td>
                            <div class="button-container">
                                <form method="post" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                                    <input type="hidden" name="delete_user" value="<?php echo $user->id; ?>">
                                    <button class="btnn btn-primary btn-sm trash" type="submit" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                                </form>
                                <button class="btnn btn-primary btn-sm edit" type="button" title="Sửa" onclick="loadEditForm(<?php echo $user->id; ?>)"><i class="fas fa-edit"></i></button>
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

<!-- Modal hoặc form thêm người dùng mới -->
<div id="addUserModal" class="modal" style="display: none;">
    <div class="modal-content-user">
        <div class="modal-body">
            <div class="row_tabel_edit_user">
                Thêm quản lý mới
            </div>
            <form id="addUserForm" method="POST" enctype="multipart/form-data">
                <div class="row_edit">
                    <div class="form-group_adduser">
                        <label class="control-label">Tên đăng nhập</label>
                        <input class="form-control" type="text" name="username" required>
                    </div>
                    <div class="form-group_adduser">
                        <label class="control-label">Mật khẩu</label>
                        <input class="form-control" type="password" name="password" required>
                    </div>
                    <div class="form-group_adduser">
                        <label class="control-label">Tên quản lý</label>
                        <input class="form-control" type="text" name="fullname" required>
                    </div>
                    <div class="form-group_adduser">
                        <label class="control-label">Địa chỉ</label>
                        <input class="form-control" type="text" name="address" required>
                    </div>
                    <div class="form-group_adduser">
                        <label class="control-label">Email</label>
                        <input class="form-control" type="email" name="email" required>
                    </div>
                    <div class="form-group_adduser">
                        <label class="control-label">Số điện thoại</label>
                        <input class="form-control" type="tel" name="phone" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-save" name="save" type="submit">Lưu lại</button>
                    <button class="btn-cancel" type="button" onclick="$('#addUserModal').hide(); $('.overlay').hide();">Hủy bỏ</button>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="pagination">
    <?php Pagination::generatePagination($totalUsers, SUBJECT_PER_PAGE, $page, 'manage_user.php'); ?>
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

        function loadEditForm(userId) {
            // Gửi yêu cầu AJAX để lấy biểu mẫu sửa người dùng từ máy chủ
            $.ajax({
                url: 'edituser.php', // Đường dẫn đến tập tin xử lý lấy biểu mẫu sửa người dùng
                type: 'GET',
                data: {
                    id: userId
                },
                success: function(response) {
                    // Hiển thị biểu mẫu sửa người dùng trong phần tử editFormContainer
                    $('.overlay').show();
                    $('#editFormContainer').html(response);
                    $('#editFormContainer').show(); // Hiển thị modal sau khi nội dung đã được tải
                },
                error: function() {
                    alert('Đã xảy ra lỗi khi tải biểu mẫu sửa người dùng.');
                }
            });
        }

        window.loadEditForm = loadEditForm; // Đặt hàm loadEditForm thành toàn cục để có thể gọi từ bên ngoài
    });


    $(document).ready(function() {
        // Hiển thị modal hoặc form khi nhấp vào nút "Thêm người dùng mới"
        $(".btn-add").click(function() {
            $("#addUserModal").show();
            $(".overlay").show();
        });

        // Đóng modal hoặc form khi nhấp vào nút "Hủy bỏ" hoặc overlay
        $(".btn-cancel, .overlay").click(function() {
            $("#addUserModal").hide();
            $(".overlay").hide();
        });

        // Xử lý sự kiện khi nhấn nút "Lưu lại" trên modal hoặc form
        $("#addUserForm").submit(function(event) {
            event.preventDefault(); // Ngăn chặn gửi yêu cầu mặc định
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: "adduser.php", // Đường dẫn đến tập tin xử lý thêm người dùng mới
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        alert(result.message);
                        location.reload(); // Tải lại trang sau khi thêm người dùng mới thành công
                    } else {
                        alert(result.message);
                    }
                },
                error: function() {
                    alert("Đã xảy ra lỗi khi thêm người dùng mới.");
                }
            });
        });
    });
</script>

<?php require "footer.php"; ?>