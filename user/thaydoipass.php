<?php 
session_start();
require "connet.php"; // Kết nối tới database

$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

// Kiểm tra nếu người dùng đã đăng nhập
if (!$user) {
    header("Location: login.php");
    exit();
}

$username = is_array($user) ? $user['username'] : $user;

// Xử lý thay đổi mật khẩu
$error_message = $success_message = "";
if (isset($_POST['submit'])) {
    $passwordOld = $_POST['passwordold'];
    $passwordNew = $_POST['password'];
    $passwordConfirm = $_POST['password1'];

    // Kiểm tra mật khẩu mới và xác nhận mật khẩu
    if ($passwordNew !== $passwordConfirm) {
        $error_message = "Mật khẩu mới và xác nhận không khớp!";
    } else {
        // Kiểm tra mật khẩu cũ
        $checkQuery = $conn->prepare("SELECT password FROM user WHERE username = ?");
        if ($checkQuery === false) {
            die("Lỗi khi chuẩn bị câu lệnh: " . mysqli_error($conn));  // In ra lỗi nếu không thể chuẩn bị câu lệnh
        }
        $checkQuery->bind_param("s", $username);
        $checkQuery->execute();
        $result = $checkQuery->get_result();

        if (!$result) {
            die("Lỗi truy vấn: " . mysqli_error($conn)); // In ra lỗi nếu truy vấn thất bại
        }

        $row = $result->fetch_assoc();

        if ($row['password'] !== $passwordOld) {
            $error_message = "Mật khẩu cũ không đúng!";
        } else {
            // Cập nhật mật khẩu mới
            $updateQuery = $conn->prepare("UPDATE user SET password = ? WHERE username = ?");
            if ($updateQuery === false) {
                die("Lỗi khi chuẩn bị câu lệnh: " . mysqli_error($conn));  // In ra lỗi nếu không thể chuẩn bị câu lệnh
            }
            $updateQuery->bind_param("ss", $passwordNew, $username);
            if ($updateQuery->execute()) {
                $success_message = "Thay đổi mật khẩu thành công!";
            } else {
                $error_message = "Lỗi khi cập nhật mật khẩu: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thay Đổi Mật Khẩu</title>
    <style>
        /* Reset margin và padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Căn chỉnh và tạo hiệu ứng cho background */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Định dạng phần container */
        .signup-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        /* Tiêu đề */
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* Định dạng các label và input */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="password"],
        input[type="hidden"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }

        /* Nút submit */
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Thông báo lỗi hoặc thành công */
        .message {
            margin: 14px 0;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        /* Link quay lại */
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h1>Thay Đổi Mật Khẩu</h1>
        <form action="" method="POST">
            <label for="passwordold">Mật Khẩu Cũ</label>
            <input type="text" name="passwordold" placeholder="Mật Khẩu Cũ" required>
            <label for="password">Mật Khẩu Mới</label>
            <input type="password" name="password" placeholder="Mật Khẩu Mới" required>
            <label for="password1">Xác Nhận Mật Khẩu Mới</label>
            <input type="password" name="password1" placeholder="Xác Nhận Mật Khẩu Mới" required>

            <!-- Thông báo lỗi hoặc thành công -->
            <?php if (!empty($error_message)): ?>
                <div class="message error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div class="message success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <!-- Hiển thị username trong ô input, không chỉnh sửa được -->
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>" readonly>

            <input type="submit" name="submit" value="Cập Nhật">
        </form>
        <a href="index.php">Quay Lại Trang Chủ</a>
    </div>
</body>
</html>

