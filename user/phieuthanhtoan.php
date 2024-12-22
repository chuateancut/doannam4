<?php 
session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
require "connet.php";  

if (isset($_POST['submit'])) {
    // Lấy các giá trị từ form
    $madonhang = uniqid(); // Tạo mã đơn hàng (bạn có thể thay đổi cách tạo mã này)
    $idsanpham = $_POST['idsanpham'];
    $user = isset($_SESSION['user']) ? $_SESSION['user']['username'] : null;
    $namesanpham = $_POST['namesanpham'];
    $soluong = $_POST['soluong'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $tongtien = $_POST['tongtien'];
    $price = isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '';
    $namenguoinhan = $_POST['name'];
    $numberphone = $_POST['phone'];
    $diachhicuthe = $_POST['address'];
    $ghichu = $_POST['notes'];  
    $payment = $_POST['payment'];
    $trangthai ="đang chuẩn bị gói hàng" ;
    // Kiểm tra nếu bất kỳ giá trị nào bị bỏ trống
    if (empty($namenguoinhan) || empty($numberphone) || empty($diachhicuthe)     ) {
        echo "Vui lòng điền đầy đủ thông tin!";
    } else {
        if ($soluong == 1) {
            $tongtien = $price;
        }
        // Tạo câu lệnh SQL thêm dữ liệu vào bảng 'thongtingiaohang'
        $sql = "INSERT INTO thongtingiaohang (idsanpham, username, tennguoinhan, numberphone, diachicuthe, namesanpham, soluong, color, size, price,phuongthucthanhtoan, ghichu ,trangthai) 
                VALUES ('$idsanpham', '$user', '$namenguoinhan', '$numberphone', '$diachhicuthe', '$namesanpham', '$soluong', '$color', '$size', '$tongtien','$payment', '$ghichu','$trangthai')";

        // Thực thi câu lệnh và kiểm tra kết quả
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('mua hàng thành công ! xem ngay');window.location.href='xemdonhang.php';</script>";
        } else {
            echo "Lỗi: " . $conn->error;
        }
        // Đóng kết nối
        $conn->close();
    }
} 
?>
