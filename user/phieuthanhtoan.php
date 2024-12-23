<?php 
session_start();
require "connet.php";  

if (isset($_POST['submit'])) {
    // Lấy các giá trị từ AJAX
    $idsanpham = $_POST['idsanpham'];
    $user = isset($_SESSION['user']) ? $_SESSION['user']['username'] : null;
    $namesanpham = $_POST['namesanpham'];
    $soluong = $_POST['soluong'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $tongtien = $_POST['tongtien'];
    $price = $_POST['price'];
    $namenguoinhan = $_POST['name'];
    $numberphone = $_POST['phone'];
    $diachhicuthe = $_POST['address'];
    $ghichu = $_POST['notes'];  
    $payment = $_POST['payment'];
    $trangthai = "đang chuẩn bị gói hàng";

    // Kiểm tra nếu bất kỳ giá trị nào bị bỏ trống
    if (empty($namenguoinhan) || empty($numberphone) || empty($diachhicuthe)) {
        echo "Vui lòng điền đầy đủ thông tin!";
    } else {
        if ($soluong == 1) {
            $tongtien = $price;
        }
        
        // Tạo câu lệnh SQL thêm dữ liệu vào bảng 'thongtingiaohang'
        $sql = "INSERT INTO thongtingiaohang (idsanpham, username, tennguoinhan, numberphone, diachicuthe, namesanpham, soluong, color, size, price, phuongthucthanhtoan, ghichu, trangthai) 
                VALUES ('$idsanpham', '$user', '$namenguoinhan', '$numberphone', '$diachhicuthe', '$namesanpham', '$soluong', '$color', '$size', '$tongtien', '$payment', '$ghichu', '$trangthai')";

        // Thực thi câu lệnh và kiểm tra kết quả
        if ($conn->query($sql) === TRUE) {
            // Nếu thanh toán khi nhận hàng
            if ($payment == 'thanh toán khi nhận hàng') {
                echo "<script>alert('Đặt hàng thành công! Xem đơn hàng ngay.')</script>";
                header('location: xemdonhang.php');
            } else {
                // Nếu thanh toán online (PayPal), chuyển hướng theo cách khác hoặc xử lý thêm
                echo "<script>alert('Thanh toán PayPal thành công, xem đơn hàng ngay!');</script>";
                header('location: xemdonhang.php');
            }
        }
        
        // Đóng kết nối
        $conn->close();
    }
} 

?>
