<?php 
require "connet.php"; 

if (isset($_POST['update'])) {
    $color = $_POST['color'];
    $size = $_POST['size'];
    $idgiohang = $_POST['idgiohang'];
    $quantity = $_POST['quantity']; // Sử dụng 'quantity'

    // Câu lệnh SQL để cập nhật color, size và quantity
    $sql = "UPDATE giohang SET color=?, size=?, soluong=? WHERE idgiohang=?";
    
    // Sử dụng prepared statements để bảo mật
    $stmt = $conn->prepare($sql);
    
    // Chỉ định kiểu dữ liệu
    $stmt->bind_param("ssii", $color, $size, $quantity, $idgiohang);
    
    if ($stmt->execute()) {
       header("location: giohang.php");
    } else {
        echo "Lỗi khi cập nhật: " . $stmt->error;
    }
}
?>
