<?php
require "connet.php" ;
// Kết nối đến cơ sở dữ l

// Lấy từ khóa tìm kiếm
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$sql = "SELECT * FROM sanpham WHERE nameproduct LIKE ? OR motasanpham LIKE ? LIMIT 4";
$stmt = $conn->prepare($sql);
$searchKeyword = '%' . $keyword . '%';
$stmt->bind_param('ss', $searchKeyword, $searchKeyword);
$stmt->execute();
$result = $stmt->get_result();

// Hiển thị sản phẩm
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '
        <div class="product-item">
            <img class="img--product" src="' . htmlspecialchars($row['img']) . '" alt="">
            <h3 class="h3--product">' . htmlspecialchars($row['nameproduct']) . '</h3>
            <p>' . htmlspecialchars($row['price']) . ' VND</p>
            <form method="POST" action="">
                <input type="hidden" name="idsanpham" value="' . $row['idproduct'] . '">
                <input type="hidden" name="motasanpham" value="' . htmlspecialchars($row['motasanpham']) . '">
                <input type="hidden" name="img" value="' . htmlspecialchars($row['img']) . '">
                <input type="hidden" name="namesanpham" value="' . htmlspecialchars($row['nameproduct']) . '">
                <input type="hidden" name="price" value="' . htmlspecialchars($row['price']) . '">
                
                <div style="padding: 15px;" class="btn--buy--add">
                    <a class="a--buy btn--buy" href="muangay.php?idsanpham=' . $row['idproduct'] . '&img=' . urlencode($row['img']) . '&namesanpham=' . urlencode($row['nameproduct']) . '&price=' . $row['price'] . '&motasanpham=' . urlencode($row['motasanpham']) . '">MUA NGAY</a>
                    <button style="background-color: transparent; border: none;" type="submit" name="add--product" onclick="checkLoginuser(event); return confirm(\'Bạn có muốn thêm sản phẩm này vào giỏ hàng?\')">
                        <i class="fa-solid fa-cart-shopping fa-2xl btn--add" style="color: #74C0FC;"></i>
                    </button>
                </div>
            </form>
        </div>';
    }
} else {
    echo '<p>Không tìm thấy sản phẩm nào phù hợp.</p>';
}

$conn->close();
?>
