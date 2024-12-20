<?php 
if (isset($_POST['submit--giohang'])) {
    // Lấy các giá trị từ form
    $madonhang = uniqid(); // Tạo mã đơn hàng (có thể thay đổi cách tạo mã này)
    $user = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : null;
    $namenguoinhan = $_POST['name'];
    $numberphone = $_POST['phone'];
    $diachhicuthe = $_POST['address'];
    $ghichu = $_POST['notes'];
    $trangthai ="đang chuẩn bị gói hàng" ;
    // Kiểm tra nếu bất kỳ giá trị nào bị bỏ trống
    if (empty($namenguoinhan) || empty($numberphone) || empty($diachhicuthe)) {
        echo "Vui lòng điền đầy đủ thông tin!";
    } else {
        // Lấy thông tin từ giỏ hàng (lưu trong mảng)
        $idsanpham_array = isset($_POST['idsanpham']) ? $_POST['idsanpham'] : [];
        $namesanpham_array = isset($_POST['namesanpham']) ? $_POST['namesanpham'] : [];
        $soluong_array = isset($_POST['soluong']) ? $_POST['soluong'] : [];
        $size_array = isset($_POST['size']) ? $_POST['size'] : [];
        $color_array = isset($_POST['color']) ? $_POST['color'] : [];
        $tongtien_array = isset($_POST['tongtien']) ? $_POST['tongtien'] : [];
        $price_array = isset($_POST['price']) ? $_POST['price'] : [];

        // Kiểm tra nếu giỏ hàng có sản phẩm
        if (count($idsanpham_array) > 0) {
            // Duyệt qua từng sản phẩm và thêm vào cơ sở dữ liệu
            for ($i = 0; $i < count($idsanpham_array); $i++) {
                $idsanpham = htmlspecialchars($idsanpham_array[$i]);
                $namesanpham = htmlspecialchars($namesanpham_array[$i]);
                $soluong = htmlspecialchars($soluong_array[$i]);
                $size = htmlspecialchars($size_array[$i]);
                $color = htmlspecialchars($color_array[$i]);
                $tongtien = htmlspecialchars($tongtien_array[$i]);
                $price = htmlspecialchars($price_array[$i]);
                $trangthai ="đang chuẩn bị gói hàng" ;

                // Tạo câu lệnh SQL thêm dữ liệu vào bảng 'thongtingiaohang'
                $sql = "INSERT INTO thongtingiaohang (idsanpham, username, tennguoinhan, numberphone, diachicuthe, namesanpham, soluong, color, size, price, ghichu,trangthai) 
                        VALUES ('$idsanpham', '$user', '$namenguoinhan', '$numberphone', '$diachhicuthe', '$namesanpham', '$soluong', '$color', '$size', '$price', '$ghichu','$trangthai')";

                // Thực thi câu lệnh và kiểm tra kết quả
                if ($conn->query($sql) === TRUE) {
                    echo "Sản phẩm '$namesanpham' đã được thêm thành công.<br>";
                } else {
                    echo "Lỗi: " . $conn->error . "<br>";
                }
            }
        } else {
            echo "Không có sản phẩm nào trong giỏ hàng để thanh toán.";
        }

        // Đóng kết nối sau khi tất cả các sản phẩm đã được thêm
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
</head>
<body>
</body>
</html>
