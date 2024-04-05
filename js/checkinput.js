// Lấy tất cả các ô input và thông báo lỗi
var inputGroups = document.querySelectorAll('.input-group');

// Duyệt qua từng ô input và thêm sự kiện 'input' thay vì 'blur'
inputGroups.forEach(function(inputGroup) {
    var input = inputGroup.querySelector('input');
    var errorMessage = inputGroup.querySelector('.error-message');

    // Thêm sự kiện 'input' cho mỗi ô input
    input.addEventListener('input', function() {
        // Kiểm tra dữ liệu và hiển thị thông báo lỗi nếu có
        if (input.checkValidity()) {
            input.classList.remove('error'); // Xóa lớp 'error' nếu dữ liệu hợp lệ
            errorMessage.textContent = ''; // Xóa thông báo lỗi nếu dữ liệu hợp lệ
        } else {
            // Kiểm tra nếu có khoảng trắng trong username
            if (input.getAttribute('name') === 'username' && input.value.includes(' ')) {
                errorMessage.textContent = 'Username không được chứa khoảng trắng'; // Hiển thị thông báo lỗi
            } else {
                errorMessage.textContent = input.validationMessage; // Hiển thị thông báo lỗi mặc định của trình duyệt
            }
            input.classList.add('error'); // Thêm lớp 'error' nếu dữ liệu không hợp lệ
        }
    });
});


