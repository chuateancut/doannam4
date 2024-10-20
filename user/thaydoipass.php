<?php 
session_start();
require "connet.php"; // Kết nối cơ sở dữ liệu
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
if(isset($_POST['submit'])){
    $user = $_POST['username'];
    $password = $_POST['password'];
    $password1 = $_POST['password1'];

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
    background-color: #f2f2f2;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.signup-container {
    width: 400px;
    background-color: white;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    text-align: center;
}
.signup-container h1 {
    color: #333;
    margin-bottom: 30px;
    font-size: 26px;
    font-weight: bold;
}
.signup-container label {
    width: 100%;
    text-align: left;
    display: block;
    font-size: 14px;
    margin-bottom: 5px;
    color: #333;
}
.signup-container input[type="text"], 
.signup-container input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0 20px 0;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}
.signup-container input[type="text"]:focus, 
.signup-container input[type="password"]:focus {
    border-color: #4d4dff;
    outline: none;
    box-shadow: 0 0 8px rgba(77, 77, 255, 0.2);
}
.signup-container input[type="submit"] {
    width: 100%;
    padding: 14px;
    background-color: #4d4dff;
    color: white;
    font-size: 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}
.signup-container input[type="submit"]:hover {
    background-color: #3333ff;
    transform: translateY(-2px);
}
.signup-container input[type="submit"]:active {
    background-color: #1a1aff;
    transform: translateY(0);
}
.signup-container a {
    display: block;
    margin-top: 20px;
    font-size: 14px;
    color: #4d4dff;
    text-decoration: none;
}
.signup-container a:hover {
    text-decoration: underline;
}

    </style>
</head>

<body>
    
    <div class="signup-container">
        <h1>Thay Doi Mat Khau</h1>
        <form action="" method="POST">
            <label  for="">Mat Khau Cu</label>
            <input type="text" name="passwordold" placeholder="Mat Khau Cu" required>
            <label  for="">Mat Khau Moi</label>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <label  for="">Xac Nhan Mat Khau Moi</label>
            <input type="password" name="password1" placeholder="Mật khẩu" required>

            <?php if (!empty($error_message)): ?>
                <div style="color: red; margin: 14px;"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <input type="submit" name="submit" value="Cap Nhat">
            
            <input type="hidden" name="username" value="<?php $user ?>" >
        </form>
        <a href="index.php">ko doi nua -> quay lai trang chu</a>
    </div>
</body>
</html>
