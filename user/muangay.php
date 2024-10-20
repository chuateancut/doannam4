<?php 
session_start();
require "connet.php";

$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

// Thực hiện truy vấn lấy dữ liệu từ bảng 'sanpham'
$query = "SELECT * FROM sanpham";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($conn)); // Nếu truy vấn thất bại, thông báo lỗi
}
// Thực hiện truy vấn lấy 4 sản phẩm ngẫu nhiên từ bảng 'sanpham'
$query = "SELECT * FROM sanpham ORDER BY RAND() LIMIT 4";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($conn)); // Nếu truy vấn thất bại, thông báo lỗi
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add--product'])) {
    $username = $user['username'];
    $idsanpham = $_POST['idsanpham'];
    $img = $_POST['img'];
    $namesanpham = $_POST['namesanpham'];
    $price = $_POST['price'];
    $query = "INSERT INTO giohang (username, idsanpham, img, namesanpham, price) VALUES ('$username', '$idsanpham', '$img', '$namesanpham', '$price')";
    $insert_result = mysqli_query($conn, $query);

    if (!$insert_result) {
        die("Lỗi thêm vào giỏ hàng: " . mysqli_error($conn));
    }
    echo "<script>alert('Sản phẩm đã được thêm vào giỏ hàng!');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Quần Áo DNC</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="fontawesome-free-5.12.1-web/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        body{
            background-color: #dcdcdc;
        }
    </style>
</head>
<body>
    <div class="header">
       <div class="header--right">
          <ul>
            <li class="danhsach" ><a href="index.php">TRANG CHỦ</a></li>
            <li class="" ><a href="">SẢN PHẨM</a>
             <ul class="header--menu--ul--child" >
                <li class="header--menu--li--child" ><a href="productboy.php">Quần Áo Nam</a></li>
                <li class="header--menu--li--child"><a href="productgirl.php">Quần Áo Nữ</a></li>
                <li class="header--menu--li--child"><a href="aokhoac.php">Áo Khoác</a></li>
             </ul>        
        </li>
        <li class="danhsach" ><a href="">GIỚI THIỆU</a></li>
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
            <li class="user--li--father" ><i class="fa-solid fa-user"></i></a>
          <div>
          <ul class="user--ul" >
                    <li class="user--li" > <a href="">Xem  Đơn Hàng </a> </li>
                    <li class="user--li" > <a href="">Đổi Mật Khẩu</a> </li>
                    <li class="user--li" > <a href="logout.php">Đăng Xuất</a></li>
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
           <h2 class="text--fullproduct" >THÔNG TIN SẢN SẢN PHẨM</h2>
           <?php 
           if (isset($_GET['idsanpham']) && isset($_GET['img'])&& isset($_GET['namesanpham'])&& isset($_GET['price'])){
            $idsanpham = htmlspecialchars($_GET['idsanpham']);
            $img = htmlspecialchars($_GET['img']);
            $namesanpham = htmlspecialchars($_GET['namesanpham']);
            $price = htmlspecialchars($_GET['price']);
            $motasanpham = htmlspecialchars($_GET['motasanpham']);
            $motasanpham_array = explode('-', $motasanpham);
           }
           ?>
           <div class="full--product">
            <div class="name--img" >
           <img class="img--product--quannam"  src="<?php echo $img ?>" alt="">
           <p>  <?php echo $namesanpham ?></p>
           <div class="product-description">
    <button id="toggleDetailsBtn" onclick="toggleDetails()">Chi tiết</button>
    <div id="productDetails" style="display: none;">
        <?php echo '<ul>'; ?>
        <?php
        foreach ($motasanpham_array as $item) {
            if (!empty(trim($item))) {
                echo '<li>' . htmlspecialchars(trim($item)) . '</li>';
            }
        }
        ?>
        <?php echo '</ul>'; ?>
    </div>
</div>
            </div>
            <div>
         <h3 style="padding-bottom: 15px;" >Giá Luôn Tốt</h3>
         <p style="padding-bottom: 15px;"><?php echo number_format($price, 0, ',', '.'); ?> VND</p>
   <h3 style="padding-bottom: 15px;">Kích Cỡ</h3>
   <div style="padding-bottom: 15px;">
  <form action="thanhtoan.php" method="post">

  <input type="radio" name="size" value="M" id="sizeM" class="size-radio">
    <label for="sizeM" class="btn--size">M</label>
     
    <!-- Nút radio L -->
    <input type="radio" name="size" value="L" id="sizeL" class="size-radio">
    <label for="sizeL" class="btn--size">L</label>
    
    <!-- Nút radio XL -->
    <input type="radio" name="size" value="XL" id="sizeXL" class="size-radio">
    <label for="sizeXL" class="btn--size">XL</label>
    <h3 style="padding-bottom: 15px;">Màu</h3>
    
    <div style="padding-top: 15px;" >
    <input type="radio" name="color" value="TRẮNG" id="colortrang" class="color--radio">
    <label for="colortrang" class="btn--color" style="background-color: #fff; color: black;">TRẮNG</label>
    
    <!-- Nút radio màu đen -->  
    <input type="radio" name="color" value="ĐEN" id="colorden" class="color--radio">
    <label for="colorden" class="btn--color" style="background-color: black; color: white;">ĐEN</label>

    </div>
    <h3 style="padding-bottom: 15px;">Số Lượng</h3>
 
 <div>
   <input class="input--soluong"  type="number" name="soluong" value="1" min="1" onchange="updateTotal(this, <?php echo $price; ?>)">
   <p style="padding-bottom: 15px; padding-top: 15px; "   >Tổng : <span class="total"><?php echo number_format($price, 0, ',', '.'); ?> VND</span></p>
 </div>
 <div>
   <form action="thanhtoan.php">
   <button class="btn--buynow" style="color: inherit; text-decoration: none;" onclick="checkLoginuser(event);"> 
    MUA NGAY
    <input type="hidden" name="idsanpham" value="<?php echo $idsanpham; ?>">
    <input type="hidden" name="namesanpham" value="<?php echo $namesanpham; ?>">
    <input type="hidden" name="price" value="<?php echo $price; ?>">
    <input type="hidden" name="soluong" id="hiddenQuantity" value="1">
    <input type="hidden" name="total" id="hiddenTotal" value="">
    <input type="hidden" name="img" id="hiddenimg" value="<?php echo $img; ?>">
</button>
    
   </form>
</div>

 </form>
</div>

  
 


            </div>
   
</div>
<div>
    <DIV class="text--sanphamquantam" >
    <h2>CÁC SẢN PHẨM CÓ THỂ BẠN QUAN TÂM</h2>
    </DIV>
<div class="full--product">
   
    <div class="list--product">
        <?php 
        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="product-item">
                    <img class="img--product" src="<?php echo htmlspecialchars($row['img']) ?>" alt="">
                    <h3 class="h3--product"><?php echo htmlspecialchars($row['nameproduct']) ?></h3>
                    <p><?php echo htmlspecialchars(number_format($row['price'], 0, ',', '.')) ?> VND</p>
                    <form method="POST" action="">
                        <input type="hidden" name="idsanpham" value="<?php echo $row['idproduct']; ?>">
                        <input type="hidden" name="motasanpham" value="<?php echo $row['motasanpham']?>" id="">
                        <input type="hidden" name="img" value="<?php echo $row['img']; ?>">
                        <input type="hidden" name="namesanpham" value="<?php echo $row['nameproduct']; ?>">
                        <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                        <div class="btn--buy--add">
                        <button class="btn--buy" style="margin: 10px;">
    <a class="a--buy" href="muangay.php?idsanpham=<?php echo $row['idproduct']; ?>&img=<?php echo urlencode($row['img']); ?>&namesanpham=<?php echo urlencode($row['nameproduct']); ?>&price=<?php echo $row['price']; ?>&motasanpham=<?php echo urldecode($row['motasanpham']); ?>">Mua Ngay</a>
</button>  <!-- lưu thông tin dưới dạng url -->

                            <button style="background-color: transparent; border: none;" type="submit" name="add--product"  onclick="return confirm('Bạn có muốn thêm sản phẩm này vào giỏ hàng?')">
                                <i class="fa-solid fa-cart-shopping fa-2xl btn--add " style="color: #74C0FC;"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>
<div style="padding-top: 50px;" >
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
    </div>
</body>
<script>
function selectButton(button, className) {
  // Xóa lớp 'selected' khỏi tất cả các nút trong cùng nhóm (màu hoặc kích thước)
  const buttons = document.querySelectorAll('.' + className);
  buttons.forEach(btn => btn.classList.remove('selected'));

  // Thêm lớp 'selected' cho nút được nhấn
  button.classList.add('selected');
}

function updateTotal(input, price) {
    const quantity = parseInt(input.value); // Lấy số lượng mới
    const totalCell = input.parentElement.querySelector('.total'); // Tìm phần tử chứa tổng tiền (p.total)

    // Tính tổng tiền mới
    const newTotal = quantity * price;

    // Cập nhật nội dung của phần tử tổng
    totalCell.textContent = newTotal.toLocaleString('vi-VN') + " VND";
    
    calculateGrandTotal(); }// Cập nhật tổng tiền tất cả sản phẩm (nếu có nhiều sản phẩm)

</script>
<script>
function toggleDetails() {
    const details = document.getElementById('productDetails');
    const button = document.getElementById('toggleDetailsBtn');

    // Kiểm tra trạng thái hiện tại của phần mô tả
    if (details.style.display === 'none') {
        details.style.display = 'block'; // Hiển thị phần mô tả
        button.innerText = 'Ẩn Chi tiết'; // Thay đổi văn bản nút
    } else {
        details.style.display = 'none'; // Ẩn phần mô tả
        button.innerText = 'Chi tiết'; // Thay đổi văn bản nút
    }
}

</script>
<script> 
 var checklogin = <?php echo $user ? 'true' : 'false'; ?>;
    function checkLoginuser(event) {
        if (!checklogin) {  
            alert("Bạn phải đăng nhập mới có thể mua hàng.");
            window.location.href = "login.php"; // Chuyển đến trang đăng nhập
            event.preventDefault(); // Ngăn không cho gửi form
        }
        // Nếu đã đăng nhập, không làm gì thêm
    }

</script>
<script>
    function updateTotal(input, price) {
    const quantity = parseInt(input.value); // Lấy số lượng mới
    const totalCell = input.parentElement.querySelector('.total'); // Tìm phần tử chứa tổng tiền (p.total)

    // Tính tổng tiền mới
    const newTotal = quantity * price;

    // Cập nhật nội dung của phần tử tổng
    totalCell.textContent = newTotal.toLocaleString('vi-VN') + " VND";

    // Cập nhật giá trị số lượng vào input ẩn
    document.getElementById('hiddenQuantity').value = quantity;

    calculateGrandTotal(); // Cập nhật tổng tiền tất cả sản phẩm (nếu có nhiều sản phẩm)
}

</script>
<script>
    function updateTotal(input, price) {
    const quantity = parseInt(input.value); // Lấy số lượng mới
    const totalCell = input.parentElement.querySelector('.total'); // Tìm phần tử chứa tổng tiền (p.total)

    // Tính tổng tiền mới
    const newTotal = quantity * price;

    // Cập nhật nội dung của phần tử tổng
    totalCell.textContent = newTotal.toLocaleString('vi-VN') + " VND";

    // Cập nhật giá trị số lượng vào input ẩn
    document.getElementById('hiddenQuantity').value = quantity;

    // Cập nhật giá trị tổng vào input ẩn
    document.getElementById('hiddenTotal').value = newTotal;

    calculateGrandTotal(); // Cập nhật tổng tiền tất cả sản phẩm (nếu có nhiều sản phẩm)
}

</script>
<script>
    function checkLoginuser(event) {
    const sizeSelected = document.querySelector('input[name="size"]:checked');
    const colorSelected = document.querySelector('input[name="color"]:checked');

    if (!checklogin) {  
        alert("Bạn phải đăng nhập mới có thể mua hàng.");
        window.location.href = "login.php"; // Chuyển đến trang đăng nhập
        event.preventDefault(); // Ngăn không cho gửi form
    } else if (!sizeSelected) {
        alert("Vui lòng chọn kích thước trước khi tiếp tục.");
        event.preventDefault(); // Ngăn không cho gửi form
    } else if (!colorSelected) {
        alert("Vui lòng chọn màu sắc trước khi tiếp tục.");
        event.preventDefault(); // Ngăn không cho gửi form
    }
    // Nếu đã đăng nhập và đã chọn kích thước và màu sắc, không làm gì thêm
}

</script>
</html>
