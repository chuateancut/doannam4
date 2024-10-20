<?php
session_start();
require "connet.php";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Kiểm tra thông tin đăng nhập cố định
    if ($user === "admin" && $pass === "123456") {
        $_SESSION['username'] = $user;
        // Đăng nhập thành công
        header("Location: index.php?username=" .urldecode($user)); // Chuyển đến trang chào mừng
        exit;
    } else {
        // Sai tài khoản hoặc mật khẩu
        $error = "Sai tên đăng nhập hoặc mật khẩu!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    width: 400px;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
}

input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #5cb85c;
    border: none;
    color: white;
    border-radius: 4px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #4cae4c;
}

.error {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Đăng Nhập</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post" action="login.php" autocomplete="off">
    <input type="text" name="username" placeholder="Tên đăng nhập" required autocomplete="off">
    <input type="password" name="password" placeholder="Mật khẩu" required autocomplete="new-password">
    <input type="submit" value="Đăng nhập">
</form>

    </div>
</body>
</html>
