<?php
session_start(); // Khởi động session

// Xóa tất cả các biến session
$_SESSION = [];

// Hủy session
session_destroy();

// Chuyển hướng về trang đăng nhập hoặc trang chủ
header("Location: index.php"); // Thay đổi đến "index.php" nếu bạn muốn quay lại trang chính
exit;
?>
