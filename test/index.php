<?php
session_start();
require "connet.php"; // Kết nối cơ sở dữ liệu
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán PayPal & COD</title>
</head>
<body>
    <h1>Sản phẩm: iPhone 15</h1>
    <p>Giá: 1,500,000 VNĐ</p>

    <!-- Form nhập thông tin nhận hàng -->
    <form id="order-form">
        <input type="hidden" id="idsanpham" value="1"> <!-- ID sản phẩm -->
        <input type="hidden" id="namesanpham" value="iPhone 15"> <!-- Tên sản phẩm -->
        <input type="hidden" id="tongtien" value="1500000"> <!-- Giá tiền (VNĐ) -->
        <input type="hidden" id="tongtien_usd" value="60"> <!-- Giá tiền (USD, giả định tỷ giá 1 USD = 25,000 VNĐ) -->
        <label for="name">Tên người nhận:</label>
        <input type="text" id="name" required><br>
        <label for="phone">Số điện thoại:</label>
        <input type="text" id="phone" required><br>
        <label for="address">Địa chỉ nhận hàng:</label>
        <input type="text" id="address" required><br>
        <label for="notes">Ghi chú:</label>
        <textarea id="notes"></textarea><br>

        <p>Chọn phương thức thanh toán:</p>
        <input type="radio" name="payment" value="PayPal" id="pay_paypal" checked>
        <label for="pay_paypal">PayPal</label><br>
        <input type="radio" name="payment" value="COD" id="pay_cod">
        <label for="pay_cod">Thanh toán khi nhận hàng (COD)</label><br>

        <!-- Nút thanh toán -->
        <div id="paypal-button-container"></div>
        <button type="button" id="cod-button" style="display: none;">Xác nhận thanh toán COD</button>
    </form>

    <!-- PayPal SDK -->
    <script
        src="https://www.paypal.com/sdk/js?client-id=AZguRLQRcQLTR1mO6LYJGeUY6u6StGLTXwTy4L2A2D9XC4OCBdNVlwiyHF_HwAq5Ot86bpHlVpNDV6_R&currency=USD">
    </script>

    <script>
        // Hiển thị nút phù hợp theo phương thức thanh toán
        const paypalButton = document.getElementById('paypal-button-container');
        const codButton = document.getElementById('cod-button');
        const paymentOptions = document.getElementsByName('payment');

        paymentOptions.forEach(option => {
            option.addEventListener('change', () => {
                if (option.value === 'PayPal') {
                    paypalButton.style.display = 'block';
                    codButton.style.display = 'none';
                } else {
                    paypalButton.style.display = 'none';
                    codButton.style.display = 'block';
                }
            });
        });

        // Xử lý PayPal
        paypal.Buttons({
            style: {
                layout: 'vertical',
                color: 'gold',
                shape: 'rect',
                label: 'paypal'
            },
            createOrder: function(data, actions) {
                const tongtienUsd = document.getElementById('tongtien_usd').value;
                return actions.order.create({
                    purchase_units: [{
                        amount: { value: tongtienUsd }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Thanh toán thành công, cảm ơn bạn ' + details.payer.name.given_name + '!');

                    // Gửi dữ liệu về server
                    sendOrderData('PayPal');
                });
            }
        }).render('#paypal-button-container');

        // Xử lý COD
        codButton.addEventListener('click', function() {
            sendOrderData('COD');
        });

        // Gửi dữ liệu lên server
        function sendOrderData(paymentMethod) {
            const orderData = {
                idsanpham: document.getElementById('idsanpham').value,
                namesanpham: document.getElementById('namesanpham').value,
                tongtien: document.getElementById('tongtien').value,
                name: document.getElementById('name').value,
                phone: document.getElementById('phone').value,
                address: document.getElementById('address').value,
                notes: document.getElementById('notes').value,
                payment: paymentMethod
            };

            fetch('phieuthanhtoan.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(orderData)
            })
            .then(response => response.text())
            .then(result => {
                console.log(result);
                alert('Đơn hàng đã được xử lý.');
                window.location.replace('index.php?thanhtoan=' + paymentMethod);
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
