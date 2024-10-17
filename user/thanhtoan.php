<?php
session_start();
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
                <tr>
                    <td><img src="<?php echo $img ?>" alt="Ảnh sản phẩm" width="100"></td>
                    <td><?php echo $namesanpham ?></td>
                    <td><?php echo $soluong ?></td>
                    <td>
                        <?php 
                        if ($soluong == 1) {
                            echo "<p>" . number_format($price, 0, ',', '.') . " VND</p>";
                        } else {
                            echo number_format($tongtien, 0, ',', '.') . " VND";
                        }
                        ?>
                    </td>
                    <td><?php echo $size ?></td>
                    <td><?php echo $color ?></td>
                    
                    <input type="hidden" name="namesanpham" value="<?php echo $namesanpham ?>">
    <input type="hidden" name="soluong" value="<?php echo $soluong ?>">
    <input type="hidden" name="size" value="<?php echo $size ?>">
    <input type="hidden" name="color" value="<?php echo $color ?>">
    <input type="hidden" name="tongtien" value="<?php echo $tongtien ?>">
    <input type="hidden" name="idsanpham" value="<?php echo $idsanpham ?>">
    <input type="hidden" name="price" value="<?php echo $price; ?>">

    
                </tr>
            </table>

            <!-- Form nhập thông tin nhận hàng -->
            <div class="thongtinuser" >
            <label for="name">Họ và Tên:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="phone">Số Điện Thoại:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="address">Địa Chỉ:</label>
            <input type="text" id="address" name="address" placeholder="Tỉnh - Huyện - Xã - Vị trí cụ thể" required>

            <label for="notes">Ghi Chú:</label>
            <textarea id="notes" name="notes" rows="4"></textarea>

            <div style="margin-top: 20px; font-size: 20px; color: #007bff; ">
            <?php 
                        if ($soluong == 1) {
                            echo "<p> Tổng Tiền : " . number_format($price, 0, ',', '.') . " VND</p>";
                        } else {
                            echo "Tổng Tiền : " .number_format($tongtien, 0, ',', '.') . " VND";
                        }
                        ?>
        
    </div>
     <div style="padding: 10px 0px;">
     <label  for="payment">Phương Thức Thanh Toán : </label><br>
<select style="padding: 7px 0px; margin-top: 10px; " name="payment" id="">
    <option value="thanh toán khi nhận hàng">Thanh Toán Khi Nhận Hàng</option>
    <option value="onl">Thanh Toán Online</option>
</select>

     </div>
            <button type="submit" name="submit" >Xác Nhận Đặt Hàng</button>
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
    totalCell.textContent = newTotal.toLocaleString('vi-VN') + " đ";
    
    calculateGrandTotal(); // Cập nhật tổng tiền tất cả sản phẩm
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
