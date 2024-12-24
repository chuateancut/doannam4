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
.input_paypal{
    width: 1%;
    margin-right: 5px;
}
#paypal-button-container{
    display: none;

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
        <form action="phieuthanhtoangiohang.php" method="post">
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
        <span  id="grandTotal">0 đ</span>
        <input type="hidden" id="grandTotalValue" name="grandTotalValue" value="0">
     <?php 
     $tygia = 23500;
     ?>
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
<script>
    function calculateGrandTotal() {
    let grandTotal = 0;
    const totalCells = document.querySelectorAll('.total'); // Tìm tất cả các ô tổng tiền

    totalCells.forEach(cell => {
        const total = parseInt(cell.textContent.replace(' đ', '').replace(/\./g, '')); // Bỏ đơn vị tiền tệ và dấu '.'
        grandTotal += total;
    });

    // Cập nhật tổng tiền cuối cùng hiển thị
    document.getElementById('grandTotal').textContent = grandTotal.toLocaleString('vi-VN') + ' đ';

    // Cập nhật giá trị thô cho input ẩn (không định dạng)
    document.getElementById('grandTotalValue').value = grandTotal;
}

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
    const grandTotalUSD = (document.getElementById('grandTotalValue').value / <?php echo $tygia; ?>).toFixed(2); // Chuyển đổi sang USD
    return actions.order.create({
        purchase_units: [{
            amount: {
                value: grandTotalUSD // Giá trị số USD
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
    idsanpham: [],
    namesanpham: [],
    soluong: [],
    size: [],
    color: [],
    tongtien: [],
    price: [],
    name: document.querySelector('input[name="name"]').value,
    phone: document.querySelector('input[name="phone"]').value,
    address: document.querySelector('input[name="address"]').value,
    notes: document.querySelector('textarea[name="notes"]').value,
    payment: document.querySelector('select[name="payment"]').value,
};

document.querySelectorAll('input[name="idsanpham[]"]').forEach((input) => {
    orderData.idsanpham.push(input.value);
});

document.querySelectorAll('input[name="namesanpham[]"]').forEach((input) => {
    orderData.namesanpham.push(input.value);
});

document.querySelectorAll('input[name="quantity[]"]').forEach((input) => {
    orderData.soluong.push(input.value);
});

document.querySelectorAll('input[name="size[]"]').forEach((input) => {
    orderData.size.push(input.value);
});

document.querySelectorAll('input[name="color[]"]').forEach((input) => {
    orderData.color.push(input.value);
});

document.querySelectorAll('input[name="tongtien[]"]').forEach((input) => {
    orderData.tongtien.push(input.value);
});

document.querySelectorAll('input[name="price[]"]').forEach((input) => {
    orderData.price.push(input.value);
});



            // Gửi dữ liệu đến server qua AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'phieuthanhtoangiohang.php', true);
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