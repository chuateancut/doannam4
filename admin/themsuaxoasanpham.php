<?php
require "connet.php";

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

<!-- xóa -->
<?php 
require "connet.php";


if (isset($_POST['xoasanpham'])) {
    $idsanpham = $_POST['idproduct'];

    $sql_huydon = "DELETE FROM sanpham WHERE idproduct = ?";
    $stmt = $conn->prepare($sql_huydon);
    $stmt->bind_param("i", $idsanpham);  // "i" là kiểu dữ liệu integer
    $result = $stmt->execute();
    
    if ($result) {
        echo "<script>alert('xóa sản phẩm thành công!'); window.location.href='themsuaxoa.php';</script>";
        exit;
    } else {
        echo "Lỗi khi xóa sản phẩm: " . $stmt->error;
    }
}



    if(isset($_POST['changesanpham'])){
        $idsanpham = $_POST['idproduct'];


    }
    ?>
    <!-- thêm -->
    <?php
require "connet.php";
$server = "localhost";
$username = "root";
$pass = "";
$database = "thongtinshopdnc";

$conn = new mysqli($server, $username, $pass, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Check if the form is submitted
if (isset($_POST['add_product'])) {
    $type = $_POST['type'];
    $nameproduct = $_POST['nameproduct'];
    $img = $_POST['img'];
    $price = $_POST['price'];
    $mota = $_POST['motasanpham'];

    // Correct SQL query
    $sql = "INSERT INTO sanpham (type, nameproduct, img, price, motasanpham) 
            VALUES ('$type', '$nameproduct', '$img', '$price', '$mota')";

    if (mysqli_query($conn, $sql)) {
       echo "<script>alert('Thêm sản phẩm thành công!'); window.location.href='themsuaxoa.php';</script>";

    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}

$conn->close();
?>
<?php 
require "connet.php"; // Kết nối tới cơ sở dữ liệu

if (isset($_POST['btn--thaydoi'])) {
    $idsanpham = $_POST['idproduct']; // Lấy ID sản phẩm
    $img = $_POST['img'];
    $type = $_POST['type']; // Lấy loại sản phẩm
    $nameproduct = $_POST['nameproduct']; // Lấy tên sản phẩm
    $price = $_POST['price']; // Lấy giá sản phẩm
    $mota = $_POST['motasanpham']; // Lấy mô tả sản phẩm

    // Cập nhật thông tin sản phẩm trong cơ sở dữ liệu
    $sql = "UPDATE sanpham SET type=?, img=? , nameproduct=?, price=?, motasanpham=? WHERE idproduct=?";
    $stmt = $conn->prepare($sql);
    
    // Kiểu dữ liệu cho các tham số: "ssdsii"
    $stmt->bind_param("ssdsii", $type, $img, $nameproduct, $price, $mota, $idsanpham); // Đảm bảo có đúng số tham số

    if ($stmt->execute()) {
        echo "<script>alert('Thay sản phẩm thành công!'); window.location.href='themsuaxoa.php';</script>";
    } else {
        echo "Có lỗi trong quá trình cập nhật sản phẩm: " . $stmt->error;
    }

    $stmt->close(); 
} 


$conn->close(); 
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
.container--update {
    display: flex; /* Sử dụng Flexbox để bố trí */
    justify-content: space-between; /* Căn chỉnh các phần tử */
    margin-top: 20px; /* Khoảng cách trên */
}

.sanphamtruoc, .sauthaydoi {
    flex: 1; /* Chiếm đều không gian */
    margin-right: 20px; /* Khoảng cách giữa 2 cột */
    padding: 20px; /* Khoảng cách bên trong */
    background-color: #ffffff; /* Màu nền trắng */
    border-radius: 8px; /* Bo tròn góc */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Đổ bóng */
}

.sanphamtruoc h2, .sauthaydoi h2 {
    text-align: center; /* Căn giữa tiêu đề */
    margin-bottom: 15px; /* Khoảng cách dưới tiêu đề */
    color: #007BFF; /* Màu chữ cho tiêu đề */
}

table {
    width: 100%; /* Đặt chiều rộng bảng bằng với chiều rộng của cột */
    border-collapse: collapse; /* Xóa khoảng cách giữa các ô */
}

th, td {
    padding: 12px; /* Thêm khoảng cách bên trong cho ô */
    text-align: left; /* Căn trái nội dung ô */
    border-bottom: 1px solid #ddd; /* Đường viền dưới cho ô */
}

th {
    background-color: #007BFF; /* Màu nền cho tiêu đề */
    color: white; /* Màu chữ cho tiêu đề */
}

tr:hover {
    background-color: #f1f1f1; /* Màu nền khi di chuột qua hàng */
}

.container--update input[type="text"], input[type="number"], textarea {
    width: 100%; /* Đặt chiều rộng đầy đủ */
    padding: 10px; /* Thêm khoảng cách bên trong */
    margin-top: 5px; /* Khoảng cách trên */
    margin-bottom: 15px; /* Khoảng cách dưới */
    border: 1px solid #ddd; /* Đường viền */
    border-radius: 4px; /* Bo tròn góc */
}
.container--update select {
    width: 100%; /* Đặt chiều rộng đầy đủ */
    padding: 10px; /* Thêm khoảng cách bên trong */
    margin-top: 5px; /* Khoảng cách trên */
    margin-bottom: 15px; /* Khoảng cách dưới */
    border: 1px solid #ddd; /* Đường viền */
    border-radius: 4px; /* Bo tròn góc */
}
.container--update button {
    background-color: #007BFF; /* Màu nền cho nút */
    color: white; /* Màu chữ */
    padding: 10px 15px; /* Khoảng cách bên trong */
    border: none; /* Bỏ đường viền */
    border-radius: 4px; /* Bo tròn góc */
    cursor: pointer; /* Con trỏ khi di chuột */
    transition: background-color 0.3s; /* Hiệu ứng chuyển màu */
}

button:hover {
    background-color: #0056b3; /* Màu nền khi di chuột qua nút */
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

           
<div class="container--update" >
  <div class="sanphamtruoc" >
    <h2>Trước Thay Đổi</h2>
  <?php 
require "connet.php"; 
$idsanpham = $_POST['idproduct']; 
$sql = "SELECT * FROM sanpham WHERE idproduct=?"; 
$stmt = $conn->prepare($sql); 
$stmt->bind_param("i", $idsanpham); 
$stmt->execute(); 
$result = $stmt->get_result(); 

if ($result->num_rows > 0) { 
    echo '<table>';
    echo '<tr>';
    echo '<th>Ảnh Sản Phẩm</th>'; // Thêm tiêu đề cho cột ảnh
    echo '<th>Tên Sản Phẩm</th>';
    echo '<th>Loại</th>';
    echo '<th>Giá</th>';
    echo '<th>Mô Tả</th>';
    echo '<th>Link Sản Phẩm</th>';
    echo '</tr>';
    
    while ($row = $result->fetch_assoc()) { 
        echo '<tr>';
        echo '<td><img src="' . htmlspecialchars($row['img']) . '" alt="Ảnh sản phẩm" width="100"></td>'; // Sửa lại dấu nháy
        echo '<td>' . htmlspecialchars(substr($row['nameproduct'], 0, 50)) . '</td>'; // Hiển thị tối đa 50 ký tự
        echo '<td>' . htmlspecialchars($row['type']) . '</td>'; // Giả sử bạn có cột 'type'
        echo '<td>' . htmlspecialchars($row['price']) . '</td>'; // Giả sử bạn có cột 'price'
        echo '<td>' . htmlspecialchars(substr($row['motasanpham'], 0, 150)) . '</td>'; 
        echo '<td>' . htmlspecialchars(substr($row['img'], 0, 250)) . '</td>';
       
        echo '</tr>';
    } 

    echo '</table>';
} else {
    echo 'Không tìm thấy sản phẩm.';
}

$stmt->close(); 
$conn->close(); 
?>


  </div>
  <div class="sauthaydoi" >
 <h2>Thay Đổi Thành</h2>
 <form action="" method="POST">
    <?php 
    if(isset($_POST['changesanpham'])){
        $idsanpham = $_POST['idproduct']; 
    }
    ?>
    <input type="hidden" name="idproduct" value="<?php echo "$idsanpham" ?>"  >
    
    <label for="nameproduct">Tên Sản Phẩm:</label>
    <input type="text" id="nameproduct" name="nameproduct" required><br><br>

    <label for="type">Loại:</label>
    <select name="type" id="">
<option value="quannu">quần nữ</option>
<option value="aonu">áo nữ</option>
<option value="quannam">quần nam</option>
<option value="aonam">áo nam</option>
<option value="aokhoac">áo khoác</option>
       </select>

    <label for="price">Giá:</label>
    <input type="number" id="price" name="price" required><br><br>

    <label for="motasanpham">Mô Tả:</label>
    <textarea id="motasanpham" name="motasanpham" rows="4" required></textarea><br><br>

    <label for="img">Ảnh Sản Phẩm:</label>
    <input type="text" id="img" name="img" required><br><br>

    <button name="btn--thaydoi" >
        Cập Nhật
    </button>
</form>

  </div>
</div>
           

        </div>
    </div>

   
</body>
</html>
