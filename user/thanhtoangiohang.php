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

<?php
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idsanpham = isset($_POST['idsanpham']) ? htmlspecialchars($_POST['idsanpham']) : '';
    $namesanpham = isset($_POST['namesanpham']) ? htmlspecialchars($_POST['namesanpham']) : '';
    $img = isset($_POST['img']) ? htmlspecialchars($_POST['img']) : '';
    $price = isset($_POST['namesanpham'])? htmlspecialchars($_POST['price']) : '';
    $size = isset($_POST['size']) ? $_POST['size'] : '';
    $color = isset($_POST['color']) ? $_POST['color'] : '';
    $soluong = $_POST['soluong'];
    $tongtien = $_POST['total'];

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="fontawesome-free-5.12.1-web/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <style>
     .container--thanhtoan{
      padding-top: 200px;
     }
      .cart-list{
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
        max-width: 1200px; 
        margin: auto;
      }
      table{
        width: 100%;
        border-collapse: collapse;
      }
      th, td {
        padding: 10px;
        text-align: center;
        border: 1px solid #dee2e6;
      }
      tr:nth-child(even) {
        background-color: #f2f2f2;
      }
      tr:hover {
        background-color: #e9ecef;
      }
      input, textarea {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%; /* Chiếm hết chiều rộng của form */
    box-sizing: border-box; /* Đảm bảo padding không làm tăng chiều rộng */
}
      .thongtinuser{
        display: flex;
        flex-direction: column
      }
      button {
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
button:hover {
    background-color: #0056b3;
}
    </style>
</head>
<body>
<div class="header">
    <div class="header--right">
        <ul>
            <li class="danhsach"><a href="index.php">TRANG CHỦ</a></li>
            <li class="danhsach"><a href="productboy.php">QUẦN ÁO NAM</a></li>
            <li class="danhsach"><a href="productgirl.php">QUẦN ÁO NỮ</a></li>
            <li class="danhsach"><a href="aokhoac.php">ÁO KHOÁC</a></li>
            <li class="danhsach"><a href="">HỖ TRỢ</a></li>
        </ul>
    </div>
    <div class="header--left">
        <ul class="header--left--ul">
        <?php 
            if ($user) {
        ?>
            <li><a href="giohang.php?username=<?php echo urlencode($user['username']); ?>"><i class="fa-solid fa-cart-shopping fa-lg" style="color: #ffffff;"></i></a></li>
            <li><a href=""><?php echo htmlspecialchars($user['username']); ?></a></li>
            <li class="user--li--father"><i class="fa-solid fa-user fa-xl"></i></li>
            <div>
                <ul style="padding-top: 10px;" class="user--ul">
                    <li style="padding-top: 10px;" class="user--li"><a href="">Xem Đơn Hàng</a></li>
                    <li style="padding-top: 10px;" class="user--li"><a href="">Đổi Mật Khẩu</a></li>
                    <li style="padding-top: 10px;" class="user--li"><a href="logout.php">Đăng Xuất</a></li>
                </ul>
            </div>
        <?php } else { ?>
            <li><a href="register.php">Đăng Ký</a></li>
            <li><a href="login.php">Đăng Nhập</a></li>
        <?php } ?>
        </ul>
    </div>
</div>

<div class="container--thanhtoan">
    <div class="cart-list">
        <form action="phieuthanhtoan.php" method="post">
            <table>
            <tr>
    <th>Ảnh Sản Phẩm</th>
    <th>Tên Sản Phẩm</th>
    <th>Số Lượng</th>
    <th>Tổng Tiền</th>
    <th>Size</th>
    <th>Màu</th>
</tr>

<?php 
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>"; // Mở thẻ <tr> cho mỗi sản phẩm
        echo "<td><img src='{$row['img']}' alt='Ảnh sản phẩm' width='100'></td>";
        echo "<td>{$row['namesanpham']}</td>";
        echo "<td>
            <input type='number' name='quantity[]' value='{$row['soluong']}' min='1' onchange='updateTotal(this, {$row['price']})'>
        </td>"; 
        
        // Tính tổng tiền cho sản phẩm
        $tongtien = $row['soluong'] * $row['price']; 
        echo "<td class='total'>" . number_format($tongtien, 0, ',', '.') . " VND</td>";
        
        echo "<td>{$row['size']}</td>";
        echo "<td>{$row['color']}</td>";

        // Các input hidden phải nằm trong vòng lặp
        echo "<input type='hidden' name='idsanpham[]' value='{$row['idsanpham']}'>";
        echo "<input type='hidden' name='namesanpham[]' value='{$row['namesanpham']}'>";
        echo "<input type='hidden' name='size[]' value='{$row['size']}'>";
        echo "<input type='hidden' name='color[]' value='{$row['color']}'>";
        echo "<input type='hidden' name='tongtien[]' value='{$tongtien}'>";

        echo "</tr>";
    }
}
?>

   
    
                </tr>
            </table>

            <!-- Form nhập thông tin nhận hàng -->
            <div class="thongtinuser" >
            <label for="name">Họ và Tên:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="phone">Số Điện Thoại:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="address">Địa Chỉ:</label>
            <input type="text" id="address" name="address" required>

            <label for="notes">Ghi Chú:</label>
            <textarea id="notes" name="notes" rows="4"></textarea>

            <div style="margin-top: 20px;">
        <strong>Tổng tất cả: </strong>
        <span id="grandTotal">0 đ</span>
</div>
<div style="padding: 10px 0px;">
     <label  for="payment">Phương Thức Thanh Toán : </label><br>
<select style="padding: 7px 0px; margin-top: 10px; " name="payment" id="">
    <option value="thanh toán khi nhận hàng">Thanh Toán Khi Nhận Hàng</option>
    <option value="onl">Thanh Toán Online</option>
</select>

     </div>
     

            <button type="submit" name="submit--giohang" >Xác Nhận Đặt Hàng</button>
            </div>
        </form>
    </div>
</div>
</body>

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
</script>
</html>
