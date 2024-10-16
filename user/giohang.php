<?php 
session_start();
require "connet.php"; // Kết nối tới database
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

// Kiểm tra nếu người dùng đã đăng nhập
if (isset($_SESSION['user'])) {
    $username = $_SESSION['user']['username']; // Lấy username từ session

    // Truy vấn dữ liệu từ bảng `giohang` dựa vào username
    $query = "SELECT * FROM giohang WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Lỗi truy vấn: " . mysqli_error($conn));
    }
} else {
    // Nếu chưa đăng nhập, chuyển hướng về trang login
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Quần Áo DNC</title>
    <link rel="stylesheet" href="fontawesome-free-5.12.1-web/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        * {
    margin: 0;
    padding: 0;
}

.header {
    background-color: #4d4d4d;
    font-family: 'Arial', sans-serif;
    height: 70px;
    display: flex;
    align-items: center;
    position: fixed;
    width: 100%;
    z-index: 1000;
}

.header--right {
    display: flex;
}

.header--right ul {
    display: flex;
}

.header--right li {
    list-style-type: none;
    padding-left: 60px;
}

.danhsach:hover {
    text-decoration: underline;
    color: #f0f8ff;
}

.header--right li a {
    font-size: 20px;
    color: white;
    text-decoration: none;
}

.header--left {
    padding-right: 20px;
    margin-left: auto;
    display: flex;
}

.header--left--ul {
    display: flex;
    padding-left: 60px;
}

.header--left li {
    list-style-type: none;
    padding-left: 40px;
    position: relative;
}

.header--left li a {
    font-size: 22px;
    color: white;
    text-decoration: none;
}

.product-list {
    padding-top: 50px;
}

.background--content {
    display: flex;
    flex-direction: column;
    justify-content: center; /* Căn giữa theo chiều ngang */
    align-items: center;    /* Căn giữa theo chiều dọc */
    height: 170vh;          /* Chiều cao 100% màn hình */
    padding-top: 0px;
}

.image-container img {
    max-width: 100%;        /* Giới hạn chiều rộng của hình ảnh */
    height: auto;           /* Giữ tỉ lệ hình ảnh */
}

.header--right li:hover .header--menu--ul--child {
    display: block;
    color: black; /* Hiển thị menu con khi hover vào mục cha */
}

.header--menu--li--child:hover a {
    color: black; /* Đổi màu chữ cho liên kết bên trong */
    background-color: #f0f8ff;
    padding: 8px 10px;
}

.header .header--right .header--menu--li--child {
    margin-left: -30px;
    padding-top: 10px;
    font-size: 15px;
    color: white;
    margin-right: 30px;
}

.header .header--right .header--menu--ul--child {
    display: none;
    border-top: 1px solid #ff4040;
    flex-direction: column;
    position: absolute;
    background-color: #4d4d4d;
    z-index: 999;
    margin-left: -20px;
    padding-bottom: 10px; /* Đảm bảo menu con hiển thị trên các phần tử khác */
}

.img--product {
    width: 250px;
    height: 350px;
}

.img--product:hover {
    transform: scale(1.1); /* Phóng to hình ảnh lên 10% */
}

.full--product {
    display: flex;
    justify-content: center;
}

.list--product {
    display: flex;
    flex-wrap: wrap; /* Đảm bảo các sản phẩm tự động xuống hàng */
    gap: 20px; /* Khoảng cách giữa các sản phẩm */
    max-width: 1100px;
    justify-content: center;
}

.product-item {
    text-align: center;
    background: #e0eeee;
    padding: 5px;
    border-radius: 8px;
}

.product-item h3 {
    padding: 7px 0px;
}

.btn--buy {
    background-color: #74C0FC; /* Màu nền */
    color: white; /* Màu chữ */
    border: none; /* Không có viền */
    border-radius: 5px; /* Bo góc */
    padding: 10px 20px; /* Khoảng cách bên trong */
    font-size: 16px; /* Kích thước chữ */
    cursor: pointer; /* Hiệu ứng con trỏ khi di chuột */
    transition: background-color 0.3s, transform 0.3s; /* Hiệu ứng chuyển màu và phóng to */
    margin-right: 20px;
}

.cart-list {
    padding-top: 120px; /* Cách phần trên */
    padding: 20px; /* Thêm padding cho nội dung */
    background-color: white; /* Nền trắng cho nội dung */
    border-radius: 8px; /* Bo góc cho nội dung */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Đổ bóng cho nội dung */
    max-width: 1200px; /* Giới hạn chiều rộng tối đa */
    margin: auto; /* Căn giữa nội dung */
}

h2 {
    text-align: center; /* Căn giữa tiêu đề */
    margin-bottom: 20px; /* Khoảng cách phía dưới tiêu đề */
}

table {
    width: 100%; /* Chiều rộng 100% */
    border-collapse: collapse; /* Gộp viền của ô */
}

th, td {
    padding: 10px; /* Khoảng cách trong các ô */
    text-align: center; /* Căn giữa chữ trong ô */
    border: 1px solid #dee2e6; /* Viền nhẹ cho ô */
}

th {
    background-color: #343a40; /* Màu nền cho tiêu đề */
    color: white; /* Màu chữ tiêu đề */
}

tr:nth-child(even) {
    background-color: #f2f2f2; /* Màu nền cho hàng chẵn */
}

tr:hover {
    background-color: #e9ecef; /* Hiệu ứng hover cho hàng */
}

.btn--buy, .btn--delete {
    background-color: #007bff; /* Màu nền nút */
    color: white; /* Màu chữ nút */
    border: none; /* Không có viền */
    border-radius: 5px; /* Bo góc */
    padding: 10px 15px; /* Khoảng cách bên trong */
    cursor: pointer; /* Hiệu ứng con trỏ khi di chuột */
    transition: background-color 0.3s; /* Hiệu ứng chuyển màu */
}

.btn--buy:hover, .btn--delete:hover {
    background-color: #0056b3; /* Màu nền khi hover */
}

.update--delete {
    display: flex;
    justify-content: center; /* Căn giữa các nút */
    gap: 10px; /* Khoảng cách giữa các nút */
}

.total {
    font-weight: bold; /* Làm đậm chữ tổng tiền */
}

#grandTotal {
    font-size: 20px; /* Kích thước chữ lớn hơn cho tổng tiền */
    color: #007bff; /* Màu chữ cho tổng tiền */
}

.btn--buy:hover {
    background-color: #5ba3d7; /* Màu nền khi hover */
    transform: scale(1.1); /* Phóng to nhẹ khi hover */
}

.btn--add {
    color: #74C0FC; /* Màu sắc mặc định */
    transition: color 0.3s, transform 0.3s; /* Hiệu ứng chuyển màu và phóng to */
}

.btn--add:hover {
    color: #5ba3d7; /* Màu sắc khi hover */
    transform: scale(1.5); /* Phóng to nhẹ khi hover */
}

.header--left .user--li--father {
    position: relative;
    background-color: #4d4d4d;
}

/* Ẩn menu con ban đầu */
.user--ul {
    width: 250px;
    display: none;
    position: absolute;
    background-color: #4d4d4d;
    border-top: 1px solid #ff4040;
    z-index: 999;
    margin-left: -170px;
    margin-top: 10px;
    padding-bottom: 10px;
}

/* Hiển thị menu con khi hover vào thẻ cha */
.header--left li:hover .user--ul {
    display: block;
}

/* Hiệu ứng hover cho các liên kết trong menu người dùng */
.user--li:hover a {
    color: black; /* Đổi màu chữ khi hover */
    background-color: #f0f8ff; /* Màu nền khi hover */
    padding: 6px 10px;
}

.cart-list {
    padding-top: 150px;
}

.update--delete {
    display: flex;
}

.buy--giohang {
    background-color: #007bff; /* Màu nền nút */
    color: white; /* Màu chữ nút */
    border: none; /* Không có viền */
    border-radius: 5px; /* Bo góc */
    padding: 10px 15px; /* Khoảng cách bên trong */
    cursor: pointer; /* Hiệu ứng con trỏ khi di chuột */
    transition: background-color 0.3s; /* Hiệu ứng chuyển màu */
    width: 80%; /* Đặt chiều rộng là 80% */
    display: block; /* Biến nút thành khối */
    margin: 20px auto; }

    .footer {
    background-color: #333; /* Màu nền cho footer */
    padding: 20px 0; /* Khoảng cách bên trong */
    color: white;
}

.footer-container {
    display: flex; /* Để tạo một layout dạng flex */
    justify-content: space-between; /* Căn giữa các phần tử */
    max-width: 1200px; /* Chiều rộng tối đa */
    margin: 0 auto; /* Căn giữa */
    padding: 0 20px; /* Khoảng cách bên trong */
}

.footer-info, .footer-links, .footer-social {
    flex: 1; /* Mỗi phần tử sẽ chiếm 1 phần của container */
    padding: 0 20px; /* Khoảng cách bên trong cho từng phần */
}

.footer-links ul {
    list-style: none; /* Bỏ gạch đầu dòng */
    padding: 0; /* Bỏ padding */
}

.footer-links ul li {
    margin-bottom: 10px; /* Khoảng cách giữa các liên kết */
}

.footer-links a {
    text-decoration: none; /* Bỏ gạch chân */
    color: #007BFF; /* Màu chữ */
}

.footer-links a:hover {
    text-decoration: underline; /* Gạch chân khi hover */
}

.footer-bottom {
    text-align: center; /* Căn giữa nội dung */
    margin-top: 20px; /* Khoảng cách trên */
    font-size: 14px; /* Kích thước chữ */
    color: #6c757d; /* Màu chữ */
}
    </style>
</head>
<body>
<div class="header">
<div class="header--right">
          <ul>
                <li class="danhsach" ><a href="index.php">TRANG CHỦ</a></li>
                <li class="danhsach"><a href="productboy.php">QUẦN ÁO NAM</a></li>
                <li class="danhsach"><a href="productgirl.php">QUẦN ÁO NỮ</a></li>
                <li class="danhsach"><a href="aokhoac.php">ÁO KHOÁC</a></li>
                <li class="danhsach" ><a href="hotro.php">HỖ TRỢ</a></li>
          </ul>
       </div>
       <div class="header--left">
         <ul class="header--left--ul" >
          <?php 
                if ($user) { // Kiểm tra nếu user đã đăng nhập
            ?>
            <li><a href="giohang.php?username=<?php echo urlencode($user['username']); ?>"><i class="fa-solid fa-cart-shopping fa-lg" style="color: #ffffff;"></i></a></li>
            
            <li><a href=""><?php echo htmlspecialchars($user['username']); ?></a></li>
            <li class="user--li--father" ><i class="fa-solid fa-user fa-xl"></i></a>
          <div>
          <ul style="padding-top: 10px;" class="user--ul" >
                    <li style="padding-top: 10px;" class="user--li" > <a href="">Xem  Đơn Hàng </a> </li>
                    <li style="padding-top: 10px;" class="user--li" > <a href="">Đổi Mật Khẩu</a> </li>
                    <li style="padding-top: 10px;" class="user--li" > <a href="logout.php">Đăng Xuất</a></li>
                   </ul>   
          </div>     
        </li>
            <?php } else { ?>
                 <li><a href="register.php">Đăng Ký</a>
                </li>
                <li><a href="login.php">Đăng Nhập</a></li>
            <?php } ?>
         </ul>
       </div>
</div>
<div class="cart-list">
    <h2>Giỏ Hàng của <?php echo htmlspecialchars($username); ?></h2>

    <table>
    <tr>
        <th></th>
        <th>Tên Sản Phẩm</th>
        <th>Giá</th>
        <th>Số Lượng</th>
        <th>Tổng Tiền</th>
        <th>Size</th>
        <th>Màu</th>
        <th>Thao Tác</th>
    </tr>

    <?php
    // Kiểm tra nếu có dữ liệu trong bảng `giohang`
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <form action="updategiohang.php" method="POST">
            <tr>
                <td><img src="<?php echo htmlspecialchars($row['img']); ?>" alt="Ảnh sản phẩm" width="100"></td>
                <td><?php echo htmlspecialchars($row['namesanpham']); ?></td>
                <td><?php echo number_format($row['price'], 0, ',', '.'); ?> đ</td>
                <td>
                    <input type="number" name="quantity" value="<?php echo $row['soluong'] ?>" min="1" onchange="updateTotal(this, <?php echo $row['price']; ?>)">
                </td>
                <td class="total"> <?php echo htmlspecialchars(number_format($row['price'] * $row['soluong'], 0, ',', '.')); ?> đ</td>
                <input type="hidden" name="total" id="hiddenTotal" value="">
                <td>
                    <select name="size">
                        <option value="<?php echo $row['size']; ?>"><?php echo $row['size']; ?></option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                    </select>
                </td>
                <td>
                    <select name="color">
                        <option value="<?php echo $row['color']; ?>"><?php echo $row['color']; ?></option>
                        <option value="TRANG">TRẮNG</option>
                        <option value="DEN">ĐEN</option>
                    </select>
                </td>
                <td>
                    <div class="update--delete">
                        <input type="hidden" name="idgiohang" value="<?php echo $row['idgiohang']; ?>">
                        <button type="submit" name="update" class="btn--buy">Cập Nhật</button>
                    </form>
                    <form method="POST" action="deleteproduct.php">
                        <input type="hidden" name="idgiohang" value="<?php echo $row['idgiohang']; ?>">
                        <button type="submit" class="btn--delete">Xóa</button>
                    </form>
                    </div>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='8'>Giỏ hàng trống.</td></tr>"; // Cập nhật số cột cho đúng
    }
    ?>
    </table>

    <div style="margin-top: 20px;">
        <strong>Tổng tất cả: </strong>
        <span id="grandTotal">0 đ</span>
    </div>

    <div>
    <button class="buy--giohang" onclick="checkSelection()">Mua ngay</button>
</div>

</div>
<div>
     <footer class="footer">
     <footer class="footer">
    <div class="footer-container">
        <div class="footer-info">
            <h3>Thông tin cửa hàng</h3>
            <p>Địa chỉ: 123 Đường XYZ, Quận ABC, Thành phố HCM</p>
            <p>Điện thoại: 0123 456 789</p>
            <p>Email: contact@example.com</p>
        </div>
        <div class="footer-links">
            <h3>Liên kết nhanh</h3>
            <ul>
                <li><a href="#">Giới thiệu</a></li>
                <li><a href="#">Sản phẩm</a></li>
                <li><a href="#">Chính sách</a></li>
                <li><a href="#">Liên hệ</a></li>
            </ul>
        </div>
        <div  class="footer-social">
            <h3 style="padding-bottom: 15px;" >Mạng xã hội</h3>
            <a href="#"><i class="fa-brands fa-facebook fa-2xl" style="color: #74C0FC;"></i></a>
            <a style="padding: 0 15px;" href="#"><i class="fa-brands fa-youtube fa-2xl" style="color: #f45510;"></i></a>
            <a href="#"><i class="fa-brands fa-instagram fa-2xl" style="color: #63E6BE;"></i></a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Cửa hàng thời trang DNC. Bản quyền thuộc về chúng tôi.</p>
    </div>
</footer>

</footer>

     </div>
<script>
function updateTotal(input, price) {
    const quantity = input.value;
    const totalCell = input.parentElement.nextElementSibling; // Ô tổng tiền
    const newTotal = quantity * price;

    // Cập nhật giá trị cho ô tổng tiền
    totalCell.textContent = newTotal.toLocaleString('vi-VN') + " đ";

    // Cập nhật tổng tiền tất cả sản phẩm
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let grandTotal = 0;
    const totalCells = document.querySelectorAll('.total'); // Tìm tất cả các ô tổng tiền

    totalCells.forEach(cell => {
        const total = parseInt(cell.textContent.replace(' đ', '').replace(/\./g, '')); // Bỏ đơn vị tiền tệ và dấu '.'
        grandTotal += total;
    });

    // Cập nhật tổng tiền cuối cùng
    document.getElementById('grandTotal').textContent = grandTotal.toLocaleString('vi-VN') + ' đ';
}

// Tính tổng tiền ngay khi trang được tải
document.addEventListener("DOMContentLoaded", function() {
    calculateGrandTotal();
});
function checkSelection() {
    const sizeSelects = document.querySelectorAll('select[name="size"]');
    const colorSelects = document.querySelectorAll('select[name="color"]');
    let valid = true;

    // Kiểm tra xem có select nào chưa được chọn
    sizeSelects.forEach(select => {
        if (!select.value) {
            valid = false; // Nếu không có size, đặt valid thành false
        }
    });

    colorSelects.forEach(select => {
        if (!select.value) {
            valid = false; // Nếu không có color, đặt valid thành false
        }
    });

    if (!valid) {
        alert("Hãy chọn size và color trước khi mua."); // Hiển thị thông báo
    } else {
        // Nếu tất cả đều hợp lệ, chuyển hướng đến trang thanh toán
        window.location.href = "thanhtoangiohang.php";
    }
}

</script>
</body>
</html>
