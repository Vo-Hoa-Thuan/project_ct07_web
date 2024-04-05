<?
require "inc/init.php";
// Kết nối cơ sở dữ liệu và đảm bảo session đã được khởi tạo
$conn = require "inc/db.php";

$productId = $_GET['id'];   
$product = Product::showProduct($conn, $productId);
$productTypes = Product::getProductTypes($conn);

?>

<form method="POST" action="update_process.php">
    <div class="modal-content">
        <div class="modal-body">
            <div class="row_tabel_edit">
                Chỉnh sửa thông tin sản phẩm cơ bản
            </div>
            <div class="row_edit">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                <div class="form-group">
                    <label class="control-label">Tên sản phẩm</label>
                    <input class="form-control" type="text" name="name" value="<?php echo $product['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label class="control-label">Giá bán</label>
                    <input class="form-control" type="text" name="price" value="<?php echo $product['price']; ?>">
                </div>
                <div class="form-group">
                    <label for="exampleSelect1" class="control-label">Danh mục</label>
                    <select class="form-control" id="exampleSelect1" name="type">
                        <? for($i = 0; $i < Product::countProductType($conn); $i++) :?>
                        <option value="<?echo $productTypes[$i]?>" <?php if ($product['type'] == "<?echo $productTypes[$i]?>") echo 'selected'; ?>><?echo $productTypes[$i]?></option>
                        <? endfor ;?>
                
                    </select>
                </div>
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

