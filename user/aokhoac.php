
<?php 
session_start();
require "connet.php"; // Kết nối tới database
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
// Kiểm tra nếu người dùng đã đăng nhập
if (isset($_SESSION['user'])) {
    $username = $_SESSION['user']['username']; // Lấy username từ session

    // Truy vấn dữ liệu từ bảng `giohang` dựa vào username
    $query = "SELECT * FROM sanpham WHERE type = 'quannam' or type = 'aonam'";
    $result = $conn->query($query);

    if (!$result) {
        die("Lỗi truy vấn: " . mysqli_error($conn));
    }
}   
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


$limit = 4; // Số sản phẩm trên mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Trang hiện tại
$offset = ($page - 1) * $limit; // Tính toán offset cho truy vấn SQL

// Truy vấn sản phẩm với giới hạn và vị trí bắt đầu
$query = "SELECT * FROM sanpham WHERE type = 'aokhoac'  LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Tính tổng số sản phẩm theo loại
$total_query = "SELECT COUNT(*) AS total FROM sanpham WHERE type = 'aokhoac' ";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total']; // Tổng số sản phẩm

$total_pages = ceil($total_products / $limit);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Quần Áo DNC</title>
    <link rel="stylesheet" href="stylenamnu.css">
    <link rel="stylesheet" href="fontawesome-free-5.12.1-web/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<style>
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
.background--content1 {
    display: flex;
    flex-direction: column;
    justify-content: center; /* Căn giữa theo chiều ngang */
    align-items: center;    /* Căn giữa theo chiều dọc */
   padding-top: 70px;
}

</style>
</head>
<body>
<div class="header">
<div class="header--right">
          <ul>
                <li class="danhsach" ><a href="index.php">TRANG CHỦ</a></li>
                <li class="danhsach" ><a href="productboy.php">QUẦN ÁO NAM</a></li>
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
    <div class="background--content1" >
               <H2 class="text--hello" style="padding: 30px 0 " >KHÔNG CÓ GẤU THÌ CÓ ÁO KHOÁC</H2>
                <img class="img--content" src="https://cmsv2.yame.vn/uploads/8f5f13ba-9939-4fd2-bb33-813b93b18288/BST_COOL_TOUCH_TRANG_CH%e1%bb%a6.jpg?quality=80&w=1280&h=0" alt="">

           </div>
    <h2 style="padding-top: 50px; text-align: center;" >TẤT CẢ SẢN PHẨM</h2>
    <div class="full--product">
    <div class="list--product">
    <?php 
    if($result -> num_rows > 0 ){
        while($row = $result  -> fetch_assoc()){ ?>
            <div class="product-item">
                    <img class="img--product" src="<?php echo htmlspecialchars($row['img']) ?>" alt="">
                    <h3 class="h3--product"><?php echo htmlspecialchars($row['nameproduct']) ?></h3>
                    <p><?php echo htmlspecialchars(number_format($row['price'], 0, ',', '.')) ?> VND</p>
                    <form method="POST" action="">
                        <input type="hidden" name="idsanpham" value="<?php echo $row['idproduct']; ?>">
                        <input type="hidden" name="img" value="<?php echo $row['img']; ?>">
                        <input type="hidden" name="namesanpham" value="<?php echo $row['nameproduct']; ?>">
                        <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                        <div class="btn--buy--add">
                        <button class="btn--buy" style="margin: 10px;">
    <a class="a--buy" href="muangay.php?idsanpham=<?php echo $row['idproduct']; ?>&img=<?php echo urlencode($row['img']); ?>&namesanpham=<?php echo urlencode($row['nameproduct']); ?>&price=<?php echo $row['price']; ?>&motasanpham=<?php echo urldecode($row['motasanpham']); ?>">Mua Ngay</a>
</button>
<button style="background-color: transparent; border: none;" type="submit" name="add--product" onclick="checkLoginuser(event); return confirm('Bạn có muốn thêm sản phẩm này vào giỏ hàng?')">
    <i class="fa-solid fa-cart-shopping fa-2xl btn--add" style="color: #74C0FC;"></i>
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
  <div class="list--page">
        <ul class="ul--list--page" >
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <!-- chức năng phân trang bằng php <3 -->
            <?php } ?>
        </ul>
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
