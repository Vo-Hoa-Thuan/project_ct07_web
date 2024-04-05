<?php

class Voucher
{
    public $voucher_code;
    public $discount;

    public function addVoucher($conn)
    {
        $sql = "INSERT INTO `voucher` (`voucher_code`, `discount`) VALUES (:voucher_code, :discount)";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':voucher_code', $this->voucher_code, PDO::PARAM_STR);
            $stmt->bindValue(':discount', $this->discount, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public static function deleteVoucher($conn, $voucher_code)
    {
        $sql = "DELETE FROM voucher WHERE voucher_code = :voucher_code";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':voucher_code', $voucher_code, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public static function findVoucherByCode($conn, $voucher_code)
    {
        $sql = "SELECT * FROM voucher WHERE voucher_code = :voucher_code";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':voucher_code', $voucher_code, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
    public static function getLimite($conn, $startFrom, $vouchersPerPage)
    {
        try {
            $sql = "SELECT * FROM voucher LIMIT :startFrom, :vouchersPerPage";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':startFrom', $startFrom, PDO::PARAM_INT);
            $stmt->bindParam(':vouchersPerPage', $vouchersPerPage, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            if ($stmt->execute()) {
                return $stmt->fetchAll();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public static function getTotal($conn)
    {
        try {
            $sql = "SELECT COUNT(*) AS total FROM voucher";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public static function applyVoucher($voucher_code, $total_price, $conn)
    {
        $stmt = $conn->prepare("SELECT * FROM voucher WHERE voucher_code = :voucher_code");
        $stmt->bindParam(":voucher_code", $voucher_code);
        $stmt->execute();
        $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($voucher) {
            $discount = $voucher['discount'];
            $discounted_price = $total_price - ($total_price * $discount / 100);
            $voucher_message = "Mã voucher đã được áp dụng. Bạn đã được giảm $discount%";
        } else {
            $discounted_price = $total_price;
            $voucher_message = "Mã voucher không hợp lệ.";
        }

        return array(
            'discounted_price' => $discounted_price,
            'voucher_message' => $voucher_message
        );
    }
}
