<?php
session_start();
require "connet.php";

$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Kiểm tra dữ liệu đầu vào
if ($data) {
    $madonhang = uniqid();
    $idsanpham = $data['idsanpham'];
    $user = isset($_SESSION['user']) ? $_SESSION['user']['username'] : 'guest';
    $namesanpham = $data['namesanpham'];
    $tongtien = $data['tongtien'];
    $namenguoinhan = $data['name'];
    $numberphone = $data['phone'];
    $diachhicuthe = $data['address'];
    $ghichu = $data['notes'];
    $payment = $data['payment'];
    $trangthai = ($payment === 'COD') ? "chờ xác nhận COD" : "đang chuẩn bị gói hàng";

    // Lưu vào cơ sở dữ liệu
    $sql = "INSERT INTO thongtingiaohang (idsanpham, username, tennguoinhan, numberphone, diachicuthe, namesanpham, soluong, color, size, price, phuongthucthanhtoan, ghichu, trangthai) 
            VALUES ('$idsanpham', '$user', '$namenguoinhan', '$numberphone', '$diachhicuthe', '$namesanpham', 1, '', '', '$tongtien', '$payment', '$ghichu', '$trangthai')";

    if ($conn->query($sql) === TRUE) {
        echo "Đơn hàng đã được lưu.";
    } else {
        echo "Lỗi: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Dữ liệu không hợp lệ.";
}
?>
