<?php 
session_start();
require "connet.php"; // Kết nối cơ sở dữ liệu

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username = '$username'";
    $query = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($query);
    $checkuser = mysqli_num_rows($query);

    if ($checkuser == 1) {
        // So sánh mật khẩu
        if ($data['password'] === $password) {
            $_SESSION['user']= $data ;
            header("Location: index.php?username=" . urlencode($username));
            exit;
        } else {
            $error_message = "Mật khẩu không giống nhau.";
        }
    } else {
        $error_message = "Tên Đăng Nhập sai.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Shop Quần Áo</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .signup-container {
            width: 400px;
            background-color: white;
            padding: 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }
        .signup-container h1 {
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: bold;
        }
        .signup-container input[type="text"], 
        .signup-container input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .signup-container input[type="text"]:focus, 
        .signup-container input[type="password"]:focus {
            border-color: #4d4dff;
            outline: none;
        }
        .signup-container input[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #4d4dff;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .signup-container input[type="submit"]:hover {
            background-color: #3333ff;
        }
        .signup-container a {
            display: block;
            margin-top: 20px;
            font-size: 14px;
            color: #3333ff;
            text-decoration: none;
        }
        .signup-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    
    <div class="signup-container">
        <h1>Đăng Nhập</h1>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>

            <?php if (!empty($error_message)): ?>
                <div style="color: red; margin: 14px;"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <input type="submit" name="submit" value="Đăng Nhập">
        </form>
        <a href="register.php">Chưa có tài khoản? Đăng ký ngay</a>
    </div>
</body>
</html>
