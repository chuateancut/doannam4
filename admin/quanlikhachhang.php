<?php
require "connet.php";
$user = isset($_SESSION['username']) ? $_SESSION['username'] : null;
session_start();

// Kiểm tra nếu chưa đăng nhập, chuyển hướng về trang login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
// Lấy tổng số thành viên
$sql_user = "SELECT COUNT(*) AS tong_user FROM user";
$result_user = $conn->query($sql_user);

// Lấy tổng số đơn hàng
$sql_tongdonhang = "SELECT COUNT(*) AS tongdonhang FROM thongtingiaohang";
$result_tongdonhang = $conn->query($sql_tongdonhang);

// Lấy tổng tiền bán hàng
$sql_tongtien = "SELECT SUM(price) AS tongtien FROM thongtingiaohang";
$result_tongtien = $conn->query($sql_tongtien);

// Lấy tổng số sản phẩm
$sql_tongsanpham = "SELECT COUNT(*) AS tongsanpham FROM sanpham";
$result_tongsanpham = $conn->query($sql_tongsanpham);

// Lấy thông tin đơn hàng
$sql_donhang = "SELECT * FROM thongtingiaohang ORDER BY thoigian DESC";
$result_donhang = $conn->query($sql_donhang);


$sql = "
  SELECT 
    u.username, 
    DATE_FORMAT(u.thoigian, '%d-%m-%Y') AS thoigian, -- Định dạng ngày tháng
    SUM(tg.soluong) AS tong_soluong,  -- Tổng số lượng sản phẩm
    SUM(tg.price) AS tong_tien  -- Tổng tiền (price * soluong)
FROM 
    user u
LEFT JOIN 
    thongtingiaohang tg ON u.username = tg.username
GROUP BY 
    u.username, u.thoigian;  -- Nhóm theo username và ngày đăng ký


";


$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="fontawesome-free-5.12.1-web/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
         .sidebar a:hover{
            background-color: #838b8b;
            padding: 10px 0px;
        }
        .danhsachuser{
            text-align: center;
        }
        .danhsachuser h1{
            padding-top: 20px;
        }
        .danhsachuser table{
            border-collapse: collapse;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
        }


th, td {
    border: 1px solid #ddd; /* Đường viền cho các ô */
    padding: 12px; /* Khoảng cách bên trong các ô */
    text-align: left; /* Căn trái văn bản */
}

th {
    background-color: #74C0FC; /* Màu nền cho tiêu đề bảng */
    color: white; /* Màu chữ cho tiêu đề bảng */
}

tr:nth-child(even) {
    background-color: #f2f2f2; /* Màu nền cho các dòng chẵn */
}

tr:hover {
    background-color: #ddd; /* Màu nền khi hover trên dòng */
}

td {
    vertical-align: top; /* Căn chỉnh nội dung trong ô */
}

    </style>
</head>
<body>
    <div class="container">
    <div class="sidebar">
            <img class="logo" src="https://tse1.mm.bing.net/th?id=OIP.WuhFKY7a0IpWwM-HWueyhQHaHI&pid=Api&P=0&h=180" alt="">
            <div style="padding: 10px;"><a href="index.php"><i class="fa-solid fa-house"></i> Trang Chủ</a></div>
            <div style="padding: 10px;"><a href="themsuaxoa.php"><i class="fa-solid fa-wrench"></i> Thêm, Sửa, Xóa</a></div>
            <div style="padding: 10px;"><a href="quanlikhachhang.php"><i class="fa-solid fa-user"></i> Quản Lí Khách Hàng</a></div>
            <div style="padding: 10px;"><a href="http://localhost:3000/user/index.php"><i class="fa-solid fa-angle-left fa-xl"></i>   Quay Lai Trang Chinh</a></div>
            <div style="padding: 10px;"><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng Xuất</a></div>
            
            
        </div>
        <div class="main-content">
            <div class="header">
                <form action="" method="GET">
                    <input type="text" name="timidsanpham" id="" placeholder="Tìm ID sản phẩm">
                    <button name="btn--timsanpham" type="submit" onclick="hideDiv()">Tìm</button>
                </form>
            </div>

            <div class="container--tong">
                <div class="tong--user user">
                    <a href="quanlikhachhang.php">
                    <div>
                        <?php 
                        if ($result_user->num_rows > 0) {
                            $rowuser = $result_user->fetch_assoc();
                            echo "<p>Tổng Thành Viên</p>" . $rowuser['tong_user'];
                        }
                        ?>
                    </div>
                    </a>
                    <div class="tong--i"><i class="fa-solid fa-user"></i></div>
                </div>
                <div class="tong--user donhang">
                   <a href="index.php">
                   <div>
                        <?php 
                        if ($result_tongdonhang->num_rows > 0) {
                            $rowtongdonhang = $result_tongdonhang->fetch_assoc();
                            echo "<p>Tổng Đơn Hàng</p>" . $rowtongdonhang['tongdonhang'];
                        }
                        ?>
                    </div>
                   </a>
                    <div class="tong--i"><i class="fa-solid fa-cart-shopping"></i></div>
                </div>
                <div class="tong--user tongtien">
                    <a href="quanlikhachhang.php">
                    <div>
                        <?php 
                        if ($result_tongtien->num_rows > 0) {
                            $rowtongtien = $result_tongtien->fetch_assoc();
                            echo "<p>Tổng Tiền Bán</p>" . number_format($rowtongtien['tongtien'], 0, ',', '.') . " VND";
                        }
                        ?>
                    </div>
                    </a>
                    <div class="tong--i"><i class="fa-solid fa-money-bill"></i></div>
                </div>
                <div class="tong--user tongsanpham">
                    <a href="themsuaxoa.php">
                    <div>
                        <?php 
                        if ($result_tongsanpham->num_rows > 0) {
                            $rowtongsanpham = $result_tongsanpham->fetch_assoc();
                            echo "<p>Tổng Sản Phẩm</p>" . $rowtongsanpham['tongsanpham'];
                        }
                        ?>
                    </div>
                    </a>
                    <div class="tong--i"><i class="fa-solid fa-wrench"></i></div>
                </div>
            </div>

         
            <div class="danhsachuser" >
    <h1>Danh Sách User Mua Hàng</h1>
    <table>
        <tr>
            <th>UserName</th>
            <th>Ngày Lập Tài Khoản</th>
            <th>Tổng Sản Phẩm Đã Mua</th>
            <th>Tổng Tiền</th>
            
        </tr>
        <?php 
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['thoigian'])?></td>
                    <td><?= htmlspecialchars($row['tong_soluong'] !== null ? $row['tong_soluong'] : 'Chưa có') ?></td>
                    <td><?= htmlspecialchars($row['tong_tien'] !== null ? number_format($row['tong_tien'], 0, ',', '.') .' VND': 'Chưa có') ?> </td>
                  
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="4">Không có kết quả nào.</td>
            </tr>
            <?php
        }
        $conn->close();
        ?>
    </table>
</div>

        </div>
       
    </div>
  

</body>
</html>
