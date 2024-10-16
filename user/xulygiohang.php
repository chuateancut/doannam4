<?php
session_start();
require "connet.php"; // Kết nối đến cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btnaddproduct'])) {
    // Lấy dữ liệu từ form
    $idsanpham = $_POST['idsanpham'];
    $tensanpham = $_POST['tensanpham'];
    $giaban = $_POST['giaban'];
    $soluong = 1; // Số lượng sản phẩm, bạn có thể thay đổi theo nhu cầu

    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (isset($_SESSION['user'])) {
        $username = $_SESSION['user']['username'];

        // Thêm sản phẩm vào giỏ hàng
        $sql = "INSERT INTO giohang (username, idsanpham, tensanpham, giaban, soluong) VALUES ('$username', '$idsanpham', '$tensanpham', '$giaban', '$soluong')";

        if (mysqli_query($conn, $sql)) {
            echo "Thêm sản phẩm vào giỏ hàng thành công!";
        } else {
            echo "Lỗi: " . mysqli_error($conn);
        }
    } else {
        echo "Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.";
    }
}

// Đóng kết nối
mysqli_close($conn);

// Chuyển hướng trở lại trang sản phẩm (có thể thay đổi URL theo yêu cầu)
header("Location: giohang.php");
exit();
?>
