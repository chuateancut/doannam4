<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div id="paypal-button-container"></div>
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
            src="https://www.paypal.com/sdk/js?client-id=AZguRLQRcQLTR1mO6LYJGeUY6u6StGLTXwTy4L2A2D9XC4OCBdNVlwiyHF_HwAq5Ot86bpHlVpNDV6_R&buyer-country=US&currency=USD&components=buttons&enable-funding=venmo,paylater,card"
            data-sdk-integration-source="developer-studio"
        ></script>



        <script>
    paypal.Buttons({
        style: {
            layout: 'vertical',  // Hiển thị nút theo chiều dọc
            color: 'gold',       // Màu của nút
            shape: 'rect',       // Hình dạng nút (rectangle)
            label: 'paypal'      // Chỉ hiển thị nút PayPal
        },
        createOrder: function(data, actions) {
            var tongtien_usd = document.getElementById('tongtien').value;
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: tongtien_usd // Giá trị thanh toán
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alert('Thanh toán thành công, cảm ơn bạn ' + details.payer.name.given_name + '!');
           window.location.replace('thanhtoan.php=paypal');
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