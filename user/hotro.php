<?php 
session_start();
require "connet.php";

$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

// Số sản phẩm hiển thị trên mỗi trang
$limit = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Truy vấn sản phẩm với giới hạn và vị trí bắt đầu
$query = "SELECT * FROM sanpham LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Tính tổng số sản phẩm
$total_query = "SELECT COUNT(*) AS total FROM sanpham";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total']; // Tổng số sản phẩm

$total_pages = ceil($total_products / $limit); // Tổng số trang

// Xử lý khi nhấn nút thêm sản phẩm vào giỏ hàng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add--product'])) {
    $username = $user['username'];
    $idsanpham = $_POST['idsanpham'];
    $img = $_POST['img'];
    $namesanpham = $_POST['namesanpham'];
    $price = $_POST['price'];
    $soluong = 1;
    $query = "INSERT INTO giohang (username, idsanpham, img, namesanpham, price,soluong) VALUES ('$username', '$idsanpham', '$img', '$namesanpham', '$price','$soluong')";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <style>
        .footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #333;
    color: #fff;
    padding: 20px 0;
    text-align: center;
}
.footer-container {
    display: flex;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
}
.footer-bottom {
    text-align: center;
    padding-top: 10px;
}
.container--hotro{
    display: flex;
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
                    <li style="padding-top: 10px;" class="user--li" > <a href="xemdonhang.php">Xem  Đơn Hàng </a> </li>
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

           <div class="container--hotro" >
<div style="padding-top: 80px;" class="telegram" > <h2><i class="fa-brands fa-telegram fa-2xl" style="color: #74C0FC;"></i> @tranne2003</h2>  <h2><i class="fa-brands fa-facebook fa-2xl" style="color: #74C0FC;"></i> facebook/shopdnc.com</h2><h2><i class="fa-brands fa-square-instagram fa-2xl" style="color: #e465e6;"></i> @heoconlaquai</h2></div>



<div style="padding-top: 80px; padding-left: 200px; " class="container--video" >
<iframe width="1200" height="600" src="https://www.youtube.com/embed/8drH-ufLpyg" frameborder="0" allowfullscreen></iframe>

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


</body>
<script>
    // Lấy tất cả các nút li
    const buttons = document.querySelectorAll('.ul--list--page li');

    // Kiểm tra nếu có giá trị trong localStorage
    const activeButtonIndex = localStorage.getItem('activeButtonIndex');

    // Nếu có, thêm lớp active cho nút tương ứng
    if (activeButtonIndex !== null) {
        buttons[activeButtonIndex].classList.add('active');
    }

    buttons.forEach((button, index) => {
        button.addEventListener('click', function(event) {
            // Ngăn chặn hành động mặc định của thẻ a
            event.preventDefault();

            // Xóa lớp active từ tất cả các nút
            buttons.forEach(btn => btn.classList.remove('active'));

            // Thêm lớp active vào nút đang được nhấn
            this.classList.add('active');

            // Lưu index của nút đã chọn vào localStorage
            localStorage.setItem('activeButtonIndex', index);

            // Chuyển đến trang tương ứng
            window.location.href = this.querySelector('a').href; // Chuyển đến trang mới
        });
    });
</script>

<script>
        var checklogin = <?php echo $user ? 'true' : 'false'; ?>;
</script>

<script>
    

    function checkLoginuser(event) {
        if (!checklogin) {  
            alert("Bạn phải đăng nhập mới có thể thêm vào giỏ hàng.");
            window.location.href = "login.php"; // Chuyển đến trang đăng nhập
            event.preventDefault(); // Ngăn không cho gửi form
        }
        // Nếu đã đăng nhập, không làm gì thêm
    }
</script>

</html>
