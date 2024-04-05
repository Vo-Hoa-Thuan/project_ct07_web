<?php
class Product
{
    public $id;
    public $name;
    public $type;
    public $price;
    public $image;

    public function __construct($id = null, $name = null, $type = null, $price = null, $image = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->price = $price;
        $this->image = $image;
    }

    private function validate()
    {
        return $this->name != '' &&
            $this->image != '' &&
            $this->type != '' &&
            $this->price != '' &&
            $this->image != '';
    }

    public static function getAll($conn)
    {
        try {
            $sql = 'select * from product';
            $stmt = $conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Product');
            if ($stmt->execute()) {
                $product = $stmt->fetchAll();
                return $product;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public static function getLimitedByType($conn, $type, $startFrom, $productsPerPage)
{
    try {
        $sql = 'select * from product where type = :type limit :startFrom, :productsPerPage';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':startFrom', $startFrom, PDO::PARAM_INT);
        $stmt->bindParam(':productsPerPage', $productsPerPage, PDO::PARAM_INT);
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

public static function getLimite($conn, $startFrom, $productsPerPage)
{
    try {
        $sql = 'SELECT * FROM product LIMIT :startFrom, :productsPerPage';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':startFrom', $startFrom, PDO::PARAM_INT);
        $stmt->bindParam(':productsPerPage', $productsPerPage, PDO::PARAM_INT);
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

public static function getTotal($conn)
{
    try {
        $sql = 'SELECT COUNT(*) AS total FROM product';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'];
    } catch (PDOException $e) {
        echo $e->getMessage();
        return null;
    }
}

public static function getTotalByType($conn, $type)
{
    try {
        $sql = 'SELECT COUNT(*) AS total FROM product WHERE type = :type';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'];
    } catch (PDOException $e) {
        echo $e->getMessage();
        return null;
    }
}


    public function addProduct($conn, $name,$type, $price, $image)
    {
    $sql = "insert into `product` (`name`, `type`, `price`, `image`) values (:name,:type ,:price, :image)";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':price', $price, PDO::PARAM_STR);
        $stmt->bindValue(':image', $image, PDO::PARAM_STR);
        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
    }

    public static function showProduct($conn, $id){
        $sql = "SELECT * FROM product WHERE id = :id";
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

    public static function deleteProduct($conn, $id){
        $sql = "DELETE FROM product WHERE id = :id";
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


public static function updateProduct($conn, $id, $name, $type, $price)
    {
        try {
            // Chuẩn bị câu lệnh SQL UPDATE để cập nhật thông tin sản phẩm
            $sql = "UPDATE product SET name = :name, type = :type, price = :price WHERE id = :id";
    
            // Chuẩn bị và thực thi câu lệnh SQL
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":type", $type, PDO::PARAM_STR);
            $stmt->bindParam(":price", $price, PDO::PARAM_STR);
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
    

public static function getImageNameById($conn, $id)
    {
        try {
            // Chuẩn bị câu lệnh SQL SELECT để lấy tên tệp ảnh từ cơ sở dữ liệu
            $sql = "SELECT image FROM product WHERE id = :id";

            // Chuẩn bị và thực thi câu lệnh SQL
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            // Trả về tên tệp ảnh nếu có kết quả, ngược lại trả về null
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['image'] ?? null;
        } catch (PDOException $e) {
            // Xử lý các ngoại lệ nếu có
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public static function getProductByType($conn, $type)
    {
        try {
            $sql = 'SELECT * FROM product WHERE type = :type';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
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
    
    // Hàm để lấy các loại sản phẩm từ cơ sở dữ liệu
public static function getProductTypes($conn) {
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

//Hàm trả về số lượng loại trong bảng product
public static function countProductType($conn){
    $query = "SELECT COUNT(DISTINCT type) AS type_count FROM product";
    $result = $conn->query($query);

    // Kiểm tra xem truy vấn có thành công không
    if ($result && $result->rowCount() > 0) {
        // Lấy dữ liệu từ kết quả truy vấn
        $row = $result->fetch(PDO::FETCH_ASSOC);
        // Trả về giá trị của type_count
        return $row['type_count'];
    } else {
        // Xử lý khi truy vấn không thành công hoặc không có dòng nào được trả về
        return 0;
    }
}

//Tìm kiếm sản phẩm
public static function searchProducts($keyword, $conn) {
    // Prepare the SQL statement
    $sql = "SELECT * FROM product WHERE name LIKE :keyword OR type LIKE :keyword";
    
    // Prepare the PDO statement
    $stmt = $conn->prepare($sql);

    // Bind the keyword parameter
    $keyword = "%$keyword%";
    $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Fetch all the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}


}
?>
