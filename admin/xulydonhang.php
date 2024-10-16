<?php
require "connet.php";

// Hủy đơn hàng
if (isset($_POST['huydon'])) {
    $madonhang = $_POST['madonhang'];
    $sql_huydon = "DELETE FROM thongtingiaohang WHERE madonhang = ?";
    $stmt = $conn->prepare($sql_huydon);
    $stmt->bind_param("i", $madonhang);  // "i" là kiểu dữ liệu integer
    $result = $stmt->execute();
    if ($result) {
        header("location: index.php");
        exit;
    } else {
        echo "Lỗi khi hủy đơn: " . $stmt->error;
    }
}

// Cập nhật trạng thái đơn hàng
if (isset($_POST['capnhattrangthai'])) {
    $trangthai = $_POST['trangthai'];
    $madonhang = $_POST['madonhang'];
    $sql_trangthai = "UPDATE thongtingiaohang SET trangthai = ? WHERE madonhang = ?";
    $stmt = $conn->prepare($sql_trangthai);
    $stmt->bind_param("si", $trangthai, $madonhang);  // "s" là string, "i" là integer
    if ($stmt->execute()) {
        header("location: index.php");
        exit;
    } else {
        echo "Lỗi khi cập nhật: " . $stmt->error;
    }
}

?>
