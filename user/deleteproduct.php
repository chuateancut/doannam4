<?php 
session_start();
require "connet.php"; // Kết nối tới database

// Kiểm tra nếu người dùng đã đăng nhập
if (isset($_SESSION['user']) && isset($_POST['idgiohang'])) {

    $username = $_SESSION['user']['username']; // Lấy username từ session
    $idgiohang = $_POST['idgiohang']; // Lấy id sản phẩm từ form

    // Xóa sản phẩm khỏi giỏ hàng dựa vào username và id sản phẩm
    $query = "DELETE FROM giohang WHERE username = '$username' AND idgiohang = '$idgiohang'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Chuyển hướng trở lại trang giỏ hàng sau khi xóa thành công
        header("Location: giohang.php");
        exit;
    } else {
        die("Lỗi xóa sản phẩm: " . mysqli_error($conn));
    }
} else {
    // Nếu không có dữ liệu, chuyển hướng về trang giỏ hàng
    header("Location: giohang.php");
    exit;
}
