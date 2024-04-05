<?php
class User
{
    public $id;
    public $username;
    public $password;
    public $fullname;
    public $address;
    public $email;
    public $phone;
    public $role;

    /*
        Chứng thực người dùng
    */
    public static function authenticate($conn, $username, $password)
    {
        $sql = "SELECT * FROM `jewelry_user` WHERE `username`=:username";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        $stmt->execute();
        $user = $stmt->fetch();
        if ($user) {
            $hash = $user->password;
            return password_verify($password, $hash) && $user->role == 'user';
        }
    }

    public static function authenticateAdmin($conn, $username, $password)
    {
        $sql = "SELECT * FROM `jewelry_user` WHERE `username`=:username";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        $stmt->execute();
        $user = $stmt->fetch();
        if ($user) {
            $hash = $user->password;
            return password_verify($password, $hash) && $user->role == 'admin';
        }
    }

    /* Kiểm tra thông tin nhập */
    private function validate()
    {
        return $this->username != '' && $this->password != '' && $this->fullname != '' && $this->address != '' && $this->email != '' && $this->phone != '';
    }

    /* Thêm mới người dùng */
    public function addUser($conn)
    {
        if ($this->validate()) {
            $sql = "INSERT INTO `jewelry_user` (`username`, `password`, `fullname`, `address`, `email`, `phone`, `role`) VALUES (:username, :password, :fullname, :address, :email, :phone, :role)";
            try {
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
                $hash = password_hash($this->password, PASSWORD_DEFAULT);
                $stmt->bindValue(':password', $hash, PDO::PARAM_STR);
                $stmt->bindValue(':fullname', $this->fullname, PDO::PARAM_STR);
                $stmt->bindValue(':address', $this->address, PDO::PARAM_STR);
                $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
                $stmt->bindValue(':phone', $this->phone, PDO::PARAM_STR);
                $stmt->bindValue(':role', $this->role, PDO::PARAM_STR);
                return $stmt->execute();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return false;
            }
        } else {
            return false;
        }
    }

    public static function isValueExists($conn, $columnName, $value)
    {
        $sql = "SELECT COUNT(*) FROM `jewelry_user` WHERE `$columnName` = :value";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':value', $value, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    // Hàm kiểm tra username
    public  static function validateUsername($username) {
        // Kiểm tra xem username có đúng định dạng không
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            return "Username chỉ được chứa ký tự chữ, số và dấu gạch dưới";
        }
        return ""; // Trả về chuỗi rỗng nếu username hợp lệ
    }

    // Hàm kiểm tra email
    public  static function validateEmail($email) {
        // Kiểm tra xem email có đúng định dạng không
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Địa chỉ email không hợp lệ";
        }
        return ""; // Trả về chuỗi rỗng nếu email hợp lệ
    }
    // Lấy id của user ra
    public static function findUserdId($conn, $username)
    {
        $sql = "SELECT * FROM `jewelry_user` WHERE `username` = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        $stmt->execute();
        $user = $stmt->fetch();
        $user_id = $user->id;
        return $user_id;
    }

    public static function findNameOfUserById($user_id, $conn)
    {
        // Chuẩn bị câu truy vấn SQL
        $sql = "SELECT fullname FROM jewelry_user WHERE id = :id";
    
        // Chuẩn bị và thực thi truy vấn SQL
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Lấy kết quả từ truy vấn
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Trả về tên người dùng nếu có kết quả, ngược lại trả về null
        return $user ? $user['fullname'] : null;
    }
    
    public static function getLimite($conn, $startFrom, $productsPerPage)
{
    try {
        $sql = 'SELECT * FROM jewelry_user LIMIT :startFrom, :productsPerPage';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':startFrom', $startFrom, PDO::PARAM_INT);
        $stmt->bindParam(':productsPerPage', $productsPerPage, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');

        if ($stmt->execute()) {
            $products = $stmt->fetchAll();
            return $products;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
        return null;
    }
}

public static function getTotal($conn)
{
    try {
        $sql = 'SELECT COUNT(*) AS total FROM jewelry_user';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'];
    } catch (PDOException $e) {
        echo $e->getMessage();
        return null;
    }
}

public static function deleteUser($conn, $id){
    $sql = "DELETE FROM jewelry_user WHERE id = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        // Kiểm tra số bản ghi bị ảnh hưởng bởi câu lệnh DELETE
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            // Nếu có ít nhất một bản ghi đã bị xóa, trả về true
            return true;
        } else {
            // Nếu không có bản ghi nào bị ảnh hưởng, trả về false
            return false;
        }
    } catch (PDOException $e) {
        // Nếu có lỗi xảy ra trong quá trình xóa, in ra thông báo lỗi
        echo "Error: " . $e->getMessage();
        return false;
    }
}

public static function getUsersByType($conn, $type, $limit)
{
try {
    $sql = 'SELECT * FROM jewelry_user WHERE type = :type LIMIT :limit';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Product');
    
    if ($stmt->execute()) {
        $products = $stmt->fetchAll();
        return $products;
    }
} catch (PDOException $e) {
    echo $e->getMessage();
    return null;
}
}

public static function showUser($conn, $id){
    $sql = "SELECT * FROM jewelry_user WHERE id = :id";
    try{
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        return $product; // Trả về dữ liệu sản phẩm
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return null;
    }
}

public static function updateUser($conn, $id, $username, $fullname, $address, $email, $phone)
{
    try {
        // Chuẩn bị câu lệnh SQL UPDATE để cập nhật thông tin người dùng
        $sql = "UPDATE jewelry_user SET username = :username, fullname = :fullname, address = :address, email = :email, phone = :phone WHERE id = :id";

        // Chuẩn bị và thực thi câu lệnh SQL
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":fullname", $fullname, PDO::PARAM_STR);
        $stmt->bindParam(":address", $address, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":phone", $phone, PDO::PARAM_STR);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();

        // Trả về true nếu có ít nhất một hàng đã được cập nhật, ngược lại trả về false
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        // Nếu có lỗi xảy ra, in ra thông báo lỗi và trả về false
        echo "Error: " . $e->getMessage();
        return false;
    }
}


}
?>
