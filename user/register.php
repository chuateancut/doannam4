<?php 
require "connet.php"; // Chú ý kiểm tra chính xác tên tệp kết nối cơ sở dữ liệu
$error_message = "";

if (isset($_POST['submit'])){
    $username = $_POST["username"];
    $pass = $_POST["password"];
    $numberphone = $_POST["numberphone"];
    $confirm_password = $_POST["confirm_password"];
    
    if(empty($pass) || empty($confirm_password) ){
        $error_message = "Không được để trống mật khẩu";
    } elseif($pass !== $confirm_password){
        $error_message = "Mật khẩu xác nhận không đúng";
    } else {
        // Kiểm tra nếu username hoặc số điện thoại đã tồn tại
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ? OR numberphone = ?");
        $stmt->bind_param("ss", $username, $numberphone);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0){
            $error_message = "Tên đăng nhập hoặc số điện thoại đã tồn tại";
        } else {
            // Mã hóa mật khẩu trước khi lưu
          

            // Thêm người dùng mới vào cơ sở dữ liệu
            $stmt = $conn->prepare("INSERT INTO user (username, password, numberphone) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $pass, $numberphone);

            if($stmt->execute()){
                header("Location: login.php");
                exit;
            } else {
                $error_message = "Đã xảy ra lỗi trong quá trình đăng ký";
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
    <title>Form Đăng Ký - Shop Quần Áo</title>
    
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
        .signup-container input[type="numberphone"], 
        .signup-container input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .signup-container input[type="text"]:focus, 
        .signup-container input[type="numberphone"]:focus, 
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
        <h1>Đăng Ký</h1>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="text" name="numberphone" placeholder="Số Điện Thoại" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu" required>
            <?php if (!empty($error_message)): ?>
                <div style="color: red; margin: 14px;"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <input type="submit" name="submit" value="Đăng Ký">
        </form>
        <a href="login.php">Đã có tài khoản? Đăng nhập ngay</a>
    </div>
</body>
</html>
