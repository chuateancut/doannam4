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
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <img class="logo" src="https://tse1.mm.bing.net/th?id=OIP.WuhFKY7a0IpWwM-HWueyhQHaHI&pid=Api&P=0&h=180" alt="">
            <div style="padding: 10px;"><a href=""><i class="fa-solid fa-house"></i> Trang Chủ</a></div>
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

            <div class="table--donhang">
           
            <div class="container--tim" style="display: <?php echo isset($_GET['btn--timsanpham']) ? 'block' : 'none'; ?>;">
            <h1 style="margin: 20px;">Tìm Kiếm Sản Phẩm:</h1>
                <table class="table--tim">
                    <tr>
                        <th>ID Sản Phẩm</th>
                       
                        <th>Tên Sản Phẩm</th>
                        <th>Phân Loại</th>
                        <th>Giá</th>
                    </tr>
                    <?php
                    if (isset($_GET['btn--timsanpham'])) {
                        $idsanpham = $_GET['timidsanpham'];

                        // Truy vấn để tìm sản phẩm theo ID
                        $sql_timsanpham = "SELECT * FROM sanpham WHERE idproduct = ?";
                        $stmt = $conn->prepare($sql_timsanpham);
                        $stmt->bind_param("i", $idsanpham);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Hiển thị kết quả
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['idproduct'] . "</td>";
                                echo "<td>" . $row['nameproduct'] . "</td>";
                                echo "<td>" . $row['type'] . "</td>";
                                echo "<td>" . number_format($row['price'], 0, ',', '.') . " VND</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Không tìm thấy sản phẩm nào với ID này.</td></tr>";
                        }

                        $stmt->close();
                    }
                    $conn->close();
                    ?>
                </table>
            </div>
                <h1 style="margin: 20px;">Đơn Hàng Hiện Tại:</h1>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>ID SP</th>
                        <!-- <th>User</th> -->
                        <th>Tên Người Mua</th>
                        <th>Số Điện Thoại</th>
                        <th>Địa Chỉ</th>
                        <th>Sản Phẩm</th>
                        <th>Số Lượng, Size, Màu</th>
                        <th>Giá</th>
                        <th>Thời Gian</th>
                        <th>PTTT</th>
                        <th>Trạng Thái</th>
                    </tr>

                    <?php 
                    if ($result_donhang->num_rows > 0) {
                        while ($rowdonhang = $result_donhang->fetch_assoc()) { ?>
                            <tr>
                               <td><?php echo $rowdonhang['madonhang']; ?></td>
                                <td><?php echo $rowdonhang['idsanpham']; ?></td>
                               
                                <td><?php echo $rowdonhang['tennguoinhan']; ?></td>
                                <td><?php echo $rowdonhang['numberphone']; ?></td>
                                <td><?php echo $rowdonhang['diachicuthe']; ?></td>
                                <td><?php echo $rowdonhang['namesanpham']; ?></td>
                                <td><?php echo $rowdonhang['soluong'] . " " . $rowdonhang['size'] . " " . $rowdonhang['color']; ?></td>
                                <td><?php echo number_format($rowdonhang['price'], 0, ',', '.') . " VND"; ?></td>
                                <td><?php echo date("d-m-Y", strtotime($rowdonhang['thoigian'])); ?></td>
                                <td><?php echo $rowdonhang['phuongthucthanhtoan']; ?></td>
                                <td>
                                    <form action="xulydonhang.php" method="post">
                                        <select name="trangthai" id="">
                                            <option value=""><?php echo $rowdonhang['trangthai']; ?></option>
                                            <option value="Chưa Giao">Chưa Giao</option>
                                            <option value="Đã Giao Shipper">Đã Giao Shipper</option>
                                            <option value="Hoàn Thành">Hoàn Thành</option>
                                            <option value="Đã Hủy">Đã Hủy</option>
                                        </select>
                                        <input type="hidden" name="madonhang" value="<?php echo $rowdonhang['madonhang']; ?>">
                                        <div style="padding-top: 5px;" >
                                            <button name="huydon">Hủy Đơn</button>
                                            <button name="capnhattrangthai">Cập nhật</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        <?php }
                    }
                    ?>
                </table>
            </div>

           
        </div>
    </div>

    <script>
    function hideDiv() {
        var div = document.querySelector('.table--donhang');
        var divdonhang = document.querySelector('.container--tim');

        div.style.display = 'none'; 
        divdonhang.style.display = 'block'; 
    }
    </script>
</body>
</html>
