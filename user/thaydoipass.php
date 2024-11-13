<?php 
session_start();
require "connet.php"; // Kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng có đăng nhập không
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
if ($user == "") {
    header("location: login.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Shop Quần Áo</title>
    <style>
        /* Các kiểu đã định dạng sẵn của bạn */
    </style>
</head>
<body>
    <div class="signup-container">
        <h1>Thay Đổi Mật Khẩu</h1>
        <form action="" method="POST">
            <label for="passwordold">Mật Khẩu Cũ</label>
            <input type="text" name="passwordold" placeholder="Mật Khẩu Cũ" required>
            <label for="password">Mật Khẩu Mới</label>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <label for="password1">Xác Nhận Mật Khẩu Mới</label>
            <input type="password" name="password1" placeholder="Xác Nhận Mật khẩu" required>

            <?php if (!empty($error_message)): ?>
                <div style="color: red; margin: 14px;"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div style="color: green; margin: 14px;"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <input type="submit" name="submit" value="Cập Nhật">
            
            <!-- Hiển thị username trong ô input, không chỉnh sửa được -->
            <input type="text" name="username" value="<?php echo htmlspecialchars($user); ?>" readonly>
        </form>
        <a href="index.php">Quay Lại Trang Chủ</a>
    </div>
</body>
</html>
