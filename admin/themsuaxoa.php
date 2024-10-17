<?php
require "connet.php";
$sql_user = "SELECT COUNT(*) AS tong_user FROM user";
$result_user = $conn -> query($sql_user) ;
$sql_tongdonhang = "SELECT COUNT(*) AS tongdonhang FROM thongtingiaohang";
$result_tongdonhang = $conn -> query($sql_tongdonhang);
$sql_tongtien = "SELECT SUM(price) AS tongtien from thongtingiaohang";
$result_tongtien = $conn -> query(query: $sql_tongtien);
$sql_tongsanpham = "SELECT COUNT(*) as tongsanpham from sanpham";
$result_tongsanpham = $conn -> query($sql_tongsanpham);
$sql_donhang = "SELECT * FROM thongtingiaohang ORDER BY thoigian DESC";
$result_donhang = $conn -> query($sql_donhang);
$sql_sanpham = "SELECT * FROM sanpham ";
$result_sanpham = $conn -> query($sql_sanpham);
$sql_suasanpham = "SELECT FROM sanpham where idproduct=?";
$result_suasanpham = mysqli_query($conn, $sql_suasanpham);




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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet"> <!-- Font từ Google Fonts -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <style>
        .btn--add{
            background-color: blueviolet;
            color: white;
            padding: 4px 8px;
        }
        .btn--delete{
            background-color: cornflowerblue;
            color: white;
            padding: 4px 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
      <img class="logo" src="https://tse1.mm.bing.net/th?id=OIP.WuhFKY7a0IpWwM-HWueyhQHaHI&pid=Api&P=0&h=180" alt="">
      <div style="padding: 10px;"><a  href="index.php"><i class="fa-solid fa-house">  </i> Trang Chủ</a></div>
      <div style="padding: 10px;" ><a href=""><i class="fa-solid fa-wrench"></i> Thêm,Sửa,Xóa</a></div>
        </div>
        <div class="main-content">
        <div class="header">
    <form action="" method="get" >
    <input type="text" name="timidsanpham" id="" placeholder="Tìm ID sản phẩm">
    <button name="btn--timsanpham" type="button" onclick="hideDiv()">Tìm</button>
   
    </form>
</div>

          <div class="container--tong" >
            <div class="tong--user user" >
                <div>
                <?php 
                if($result_user -> num_rows >0){
                    $rowuser = $result_user -> fetch_assoc();
                    echo  " <p>Tổng Thành Viên  </p>" .$rowuser['tong_user'];
                }
                ?>
                </div>
            <div class="tong--i" ><i class="fa-solid fa-user"></i></div>
          
            </div>
            <div class="tong--user donhang" >
                <div>
                <?php 
                if($result_tongdonhang -> num_rows >0){
                    $rowtongdonhang = $result_tongdonhang -> fetch_assoc();
                    echo  " <p>Tổng Đơn Hàng  </p>" .$rowtongdonhang['tongdonhang'];
                }
                ?>
                </div>
            <div class="tong--i" ><i class="fa-solid fa-cart-shopping"></i></div>
          
            </div>
            <div class="tong--user tongtien " >
                <div>
                <?php 
                if($result_tongtien -> num_rows >0){
                    $rowtongtien = $result_tongtien -> fetch_assoc(); 
                    echo  " <p>Tổng Tiền Bán "  .number_format($rowtongtien['tongtien'],0,',','.')." VND </p>" ;
                }
                ?>
                </div>
            <div class="tong--i" ><i class="fa-solid fa-money-bill"></i></div>
          
            </div>
            <div class="tong--user tongsanpham " >
                <div>
                <?php 
                if($result_tongsanpham -> num_rows >0){
                    $rowtongsanpham = $result_tongsanpham -> fetch_assoc();
                    echo  " <p>Tổng Sản Phẩm  </p>" .$rowtongsanpham['tongsanpham'];
                }
                ?>
                </div>
            <div class="tong--i" ><i class="fa-solid fa-wrench"></i></div>
          
            </div>
          </div>


          <div class="table--donhang" >
    <h1 style="margin: 20px;" > Sản Phẩm Hiện Có</h1>
    <button class="btn--add--product" onclick="blockthemsanpham()" >Thêm Sản Phẩm</button>
    <form action="themsuaxoasanpham.php" method="post"  >
    <div class="container--addproduct" >
    <label for="type">Loại:</label>
       <select name="type" id="">
<option value="quannu">quần nữ</option>
<option value="aonu">áo nữ</option>
<option value="quannam">quần nam</option>
<option value="aonam">áo nam</option>
<option value="aokhoac">áo khoác</option>
       </select>

        <label for="img">Ảnh:</label>
        <input type="text" name="img" required><br>

        <label for="nameproduct">Tên Sản Phẩm:</label>
        <input type="text" name="nameproduct" required><br>

        <label for="price">Giá:</label>
        <input type="number" name="price" required><br>

        <label for="motasanpham">Mô Tả:</label>
        <input name="motasanpham" required><br>

        <button type="submit" name="add_product">Thêm Sản Phẩm</button>
    </div>
    </form>
 <!-- chức năng sửa sản phhamar -->

 
<!-- kết thúc -->
    <table>
<tr>
    <th>ID SP</th>
    <th>Loại </th>
    <th>IMG</th>
    <th>Tên Sản Phẩm</th>
    <th>Gía</th>
    <th>Mô Tả</th>
<th>Trạng Thái</th>
</tr>
   
<?php 
if ($result_sanpham->num_rows > 0) {
    while ($rowsanpham = $result_sanpham->fetch_assoc()) { ?>
        <tr>
            <td><?php echo($rowsanpham['idproduct']) ?></td>
            <td><?php echo($rowsanpham['type']) ?></td>
            <td><?php echo mb_substr($rowsanpham['img'], 0, 40, 'UTF-8'); ?></td>
            <td><?php echo($rowsanpham['nameproduct']) ?></td>
            <td><?php echo(number_format($rowsanpham['price'], 0, ',', '.')) ?> VND</td>
            <td><?php echo mb_substr($rowsanpham['motasanpham'], 0, 40, 'UTF-8'); ?></td>
            <form action="themsuaxoasanpham.php" method="post" >
                <input type="hidden" name="idproduct" value="<?php echo $rowsanpham['idproduct'] ?>" >
                <input type="hidden" name="type" value="<?php echo $rowsanpham['type'] ?>" >
                <input type="hidden" name="img" value="<?php echo $rowsanpham['img'] ?>" >
                <input type="hidden" name="price" value="<?php echo $rowsanpham['price'] ?>" >
                <input type="hidden" name="nameproduct" value="<?php echo $rowsanpham['nameproduct'] ?>" >
                <input type="hidden" name="motasanpham" value="<?php echo $rowsanpham['motasanpham'] ?>" >
                <td class="btn--add-delete">
                <button class="btn--add"  name="changesanpham" >Sửa</button>
                <button class="btn--delete"  name="xoasanpham">XÓA</button>
</td>
            
            </form>
        </tr>
    <?php }
}
?>

 </table>
 </div>


        </div>
    </div>
</body>

<script>
function blockthemsanpham() {
    var div = document.querySelector('.container--addproduct');
    
    if (div.classList.contains('show')) {
        div.classList.remove('show');
    } else {
        div.classList.add('show');
    }
}


 

</script>

</html>  