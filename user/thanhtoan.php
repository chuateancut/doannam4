<?php
session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
if($user ==""){
    header("location: login.php");
}
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

#paypal-button-container{
    display: none;

}
.cmmpaypal {
    width: 150%;
}
#paypal-button-container{
    width: 150%;
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
// Tỷ giá USD/VND (cập nhật tùy thực tế)
$tygia = 23500;

// Tính tổng tiền VND
if ($soluong == 1) {
    $tongtien_vnd = $price;
} else {
    $tongtien_vnd = $tongtien;
}

// Tính tổng tiền USD
$tongtien_usd = $tongtien_vnd / $tygia;

// Hiển thị thông tin
echo "<p> Tổng Tiền: " . number_format($tongtien_vnd, 0, ',', '.') . " VND</p>";

?>
<input type="hidden" name="tongtien_usd" value="<?php echo round($tongtien_usd, 2); ?>" id="tongtien">
        
    </div>
     <div style="padding: 10px 0px;">
     <label  for="payment">Phương Thức Thanh Toán : </label><br>
<select style="padding: 7px 0px; margin-top: 10px; " name="payment" id="payment-select">
    <option value="thanh toán khi nhận hàng">Thanh Toán Khi Nhận Hàng</option>
    <option class="onl" value="thanh toán bằng paypal">Thanh Toán Online</option>
</select>

     </div>
     <div class="cmmpaypal" >
     <div id="paypal-button-container" ></div>
     </div>
    
     <button type="submit" name="submit" id="submit-order">Xác Nhận Đặt Hàng</button>

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
<script
        src="https://www.paypal.com/sdk/js?client-id=AZguRLQRcQLTR1mO6LYJGeUY6u6StGLTXwTy4L2A2D9XC4OCBdNVlwiyHF_HwAq5Ot86bpHlVpNDV6_R&currency=USD">
    </script>

        <script>
   paypal.Buttons({
    style: {
        layout: 'vertical', 
        color: 'gold',       
        shape: 'rect',       
        label: 'paypal'      
    },
    createOrder: function(data, actions) {
        var tongtien_usd = document.getElementById('tongtien').value;
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: tongtien_usd 
                }
            }]
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            // Thực hiện hành động sau khi thanh toán thành công
            alert('Thanh toán thành công, cảm ơn bạn ' + details.payer.name.given_name + '!');
            
            // Lấy dữ liệu từ form
            var orderData = {
                idsanpham: document.querySelector('input[name="idsanpham"]').value,
                namesanpham: document.querySelector('input[name="namesanpham"]').value,
                soluong: document.querySelector('input[name="soluong"]').value,
                size: document.querySelector('input[name="size"]').value,
                color: document.querySelector('input[name="color"]').value,
                tongtien: document.querySelector('input[name="tongtien"]').value,
                price: document.querySelector('input[name="price"]').value,
                name: document.querySelector('input[name="name"]').value,
                phone: document.querySelector('input[name="phone"]').value,
                address: document.querySelector('input[name="address"]').value,
                notes: document.querySelector('textarea[name="notes"]').value,
                payment: 'thanh toán bằng paypal', // Phương thức thanh toán
            };

            // Gửi dữ liệu đến server qua AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'phieuthanhtoan.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
    // Hiển thị thông báo thành công trước khi chuyển hướng
    alert("Thanh toán PayPal thành công, xem đơn hàng ngay!");

    // Sau khi hiển thị thông báo, chuyển hướng tới trang xem đơn hàng
    window.location.replace('xemdonhang.php');
} else {
    alert('Đã xảy ra lỗi khi lưu thông tin.');
}
            };

            // Chuyển đổi dữ liệu thành chuỗi query string và gửi đi
            var params = 'submit=true&' + Object.keys(orderData).map(function(key) {
                return key + '=' + encodeURIComponent(orderData[key]);
            }).join('&');

            xhr.send(params);
        });
    }
}).render('#paypal-button-container');

</script>

<script>
  // Lấy phần tử select và div
const paymentSelect = document.getElementById('payment-select'); // Thẻ <select>
const paypalContainer = document.getElementById('paypal-button-container'); // Div chứa PayPal

// Lắng nghe sự kiện thay đổi giá trị của select
paymentSelect.addEventListener('change', (event) => {
    const selectedValue = event.target.value; // Lấy giá trị được chọn

    if (selectedValue === 'thanh toán bằng paypal') {
        paypalContainer.style.display = 'block'; // Hiển thị div PayPal
    } else {
        paypalContainer.style.display = 'none'; // Ẩn div PayPal
    }
});

</script>
</html>
