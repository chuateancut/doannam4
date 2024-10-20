<?php 
session_start();
require "connet.php";

$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

// Số sản phẩm hiển thị trên mỗi trang
$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Truy vấn sản phẩm với giới hạn và vị trí bắt đầu
$query = "SELECT * FROM sanpham LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

$query1 = "SELECT * FROM sanpham ORDER BY RAND() LIMIT 12";
$result1 = mysqli_query($conn, $query1);
$query2 = "SELECT * FROM sanpham ORDER BY RAND() LIMIT 12";
$result2 = mysqli_query($conn, $query2);

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
    <link
        rel="stylesheet"
        type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"
      />
    
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
            <li class="user--li--father" ><i class="fa-solid fa-user fa-2xl" style="color: #0d0910;"></i></a>
          <div>
          <ul  class="user--ul" >
                    <li  class="user--li" > <a href="xemdonhang.php">Xem  Đơn Hàng </a> </li>
                    <li style="padding-top: 10px;" class="user--li" > <a href="thaydoipass.php">Đổi Mật Khẩu</a> </li>
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

           <div class="background--content" >
               <p class="text--hello" style="padding: 20px 0 " >THỜI TRANG GIÁ TỐT</p>
               <div> <img class="img--content" src="https://cmsv2.yame.vn/uploads/dbf45ca3-baa4-4c3b-a082-5e0c9d39e06f/Th%e1%bb%9di_trang_gi%c3%a1_t%e1%bb%91t_trang_ch%e1%bb%a7.jpg?quality=80&w=1280&h=0" alt=""> </div>

               <p class="text--hello"  style="padding: 20px 0 " >PHONG CÁCH QUÝ ÔNG</p>
               <div> <img class="img--content" src="https://cmsv2.yame.vn/uploads/9126cda4-f813-4131-b416-8801d485a5d5/BST_NO_STYLE_TRANG_CH%e1%bb%a6.jpg?quality=80&w=1280&h=0" alt=""></div>
               <p class="text--hello" style="padding: 20px 0 " >CHƯƠNG TRÌNH KHUYẾN MÃI</p>
           </div>
           
           <div style="margin-top: 100px;"  class="container">
           
       <div class="container-nav">
         <img class="img--sale" src="https://cdnv2.tgdd.vn/webmwg/2024/tz/images/icon-fs.png" alt="">
         <div style="margin-top: 25px;font-size: 30px; " id="countdown"></div>
        
        </div>
       <div class="container--head">
    
       <?php 
        if(mysqli_num_rows($result1)> 0){
            while($row = mysqli_fetch_assoc($result1)){
                ?>
                <div class="background--product">
                    
                    <img class="img--product" src="<?php echo htmlspecialchars($row['img']) ?>" alt="">
                    <h3 class="h3--product"><?php echo htmlspecialchars($row['nameproduct']) ?></h3>
                    <p><?php echo htmlspecialchars(number_format($row['price'], 0, ',', '.')) ?> VND</p>
                    <form method="POST" action="">
                        <input type="hidden" name="idsanpham" value="<?php echo $row['idproduct']; ?>">
                        <input type="hidden" name="motasanpham" value="<?php echo $row['motasanpham']?>" id="">
                        <input type="hidden" name="img" value="<?php echo $row['img']; ?>">
                        <input type="hidden" name="namesanpham" value="<?php echo $row['nameproduct']; ?>">
                        <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                        
                        <div style="padding: 15px;" class="btn--buy--add">
                        
    <a class="a--buy btn--buy " href="muangay.php?idsanpham=<?php echo $row['idproduct']; ?>&img=<?php echo urlencode($row['img']); ?>&namesanpham=<?php echo urlencode($row['nameproduct']); ?>&price=<?php echo $row['price']; ?>&motasanpham=<?php echo urldecode($row['motasanpham']); ?>">MUA NGAY</a>
  <!-- lưu thông tin dưới dạng url -->

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
           <h2 class="text--fullproduct" >TẤT CẢ SẢN PHẨM</h2>
           <div class="full--product">
            
    <div class="list--product">
        <?php 
        if(mysqli_num_rows($result)> 0){
            while($row = mysqli_fetch_assoc($result)){
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
                        
                        <div style="padding: 15px;" class="btn--buy--add">
                        
    <a class="a--buy btn--buy " href="muangay.php?idsanpham=<?php echo $row['idproduct']; ?>&img=<?php echo urlencode($row['img']); ?>&namesanpham=<?php echo urlencode($row['nameproduct']); ?>&price=<?php echo $row['price']; ?>&motasanpham=<?php echo urldecode($row['motasanpham']); ?>">MUA NGAY</a>
  <!-- lưu thông tin dưới dạng url -->

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
     <div class="container--img--end" >
        <div class="div--end" >
        <img class="img--end" src="https://images.ctfassets.net/q602vtcuu3w3/6x8JoAmSh2p9DGNnr2pi3u/38e34337df6ade0ebe180f96ac1fd45c/UO_24_HP_Desktop_V_R_US.jpg?w=1420&q=80&fm=webp" alt="">
      <a href="productgirl.php"><button>Thời Trang Nữ</button></a>
        </div>
       <div class="div--end" >
       <img class="img--end"  src="https://images.ctfassets.net/q602vtcuu3w3/5b3XXclr1eABuqFWB3y65F/e35af498cd8d27ddc635af4d6ea7074f/UO_24_HP_Desktop_Graphics.jpg?w=1420&q=80&fm=webp" alt="">
   <a href="productboy.php"> <button>Thời Trang Nam</button></a>
       </div>
     </div>  


     <h3 style="padding:40px; text-align: center;" >CÓ THỂ BẠN QUAN TÂM</h3>
     <div class="container--head2">
       
    
    <?php 
     if(mysqli_num_rows($result2)> 0){
         while($row = mysqli_fetch_assoc($result2)){
             ?>
             <div class="background--product2">
                 
                 <img class="img--product" src="<?php echo htmlspecialchars($row['img']) ?>" alt="">
                 <h3 class="h3--product"><?php echo htmlspecialchars($row['nameproduct']) ?></h3>
                 <p><?php echo htmlspecialchars(number_format($row['price'], 0, ',', '.')) ?> VND</p>
                 <form method="POST" action="">
                     <input type="hidden" name="idsanpham" value="<?php echo $row['idproduct']; ?>">
                     <input type="hidden" name="motasanpham" value="<?php echo $row['motasanpham']?>" id="">
                     <input type="hidden" name="img" value="<?php echo $row['img']; ?>">
                     <input type="hidden" name="namesanpham" value="<?php echo $row['nameproduct']; ?>">
                     <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                     
                     <div style="padding: 15px;" class="btn--buy--add">
                     
 <a class="a--buy btn--buy " href="muangay.php?idsanpham=<?php echo $row['idproduct']; ?>&img=<?php echo urlencode($row['img']); ?>&namesanpham=<?php echo urlencode($row['nameproduct']); ?>&price=<?php echo $row['price']; ?>&motasanpham=<?php echo urldecode($row['motasanpham']); ?>">MUA NGAY</a>
<!-- lưu thông tin dưới dạng url -->

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
    <hr class="custom-hr">

    <div class="end--aokhoac" >
<img class="img--aokhoac" src="https://images.ctfassets.net/q602vtcuu3w3/2fSQdpP6YB60q40ZUlLbS9/94ca7167141a64ce26cb52a84bf6840f/UO_24_HP_TNF_Desktop.jpg?w=1420&q=80&fm=webp" alt="">
<a href="aokhoac.php"><button>XEM NGAY</button></a>
    </div>
      
    

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



     </div>

<script
  type="text/javascript"
  src="https://code.jquery.com/jquery-1.11.0.min.js"
></script>
<script
  type="text/javascript"
  src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"
></script>
<script
  type="text/javascript"
  src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"
></script>
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
<script>
    // Set thời gian đếm ngược (ví dụ: 1 giờ 34 phút 30 giây)
let hours = 9;
let minutes = 34;
let seconds = 30;

function startCountdown() {
    let countdownElement = document.getElementById('countdown');

    // Cập nhật đồng hồ mỗi giây
    let countdownInterval = setInterval(function() {
        if (hours === 0 && minutes === 0 && seconds === 0) {
            clearInterval(countdownInterval);
            countdownElement.innerHTML = "Time's up!";
        } else {
            if (seconds === 0) {
                if (minutes === 0) {
                    hours--;
                    minutes = 59;
                } else {
                    minutes--;
                }
                seconds = 59;
            } else {
                seconds--;
            }
            countdownElement.innerHTML = `Kết thúc trong </br> ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
    }, 1000);
}

startCountdown();

</script>
<script>
    $('.container--head2').slick({
  slidesToShow: 5,
  slidesToScroll: 5,

  prevArrow:`<button type='button' class='slick-prev pull-left'><i class="fa-solid fa-angle-left fa-2xl"></i></button>`,
  nextArrow:`<button type='button' class='slick-next pull-right'><i class="fa-solid fa-angle-right fa-2xl"></i></button>`,
        


});

var filtered = false;

$('.js-filter').on('click', function(){
    
  if (filtered === false) {
    $('.filtering').slick('slickFilter',':even');
    $(this).text('Unfilter Slides');
    filtered = true;
  } else {
    $('.filtering').slick('slickUnfilter');
    $(this).text('Filter Slides');
    filtered = false;
  }
});
</script>
</html>
