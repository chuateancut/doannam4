<?php
// URL API lấy danh sách tỉnh
$url = "https://provinces.open-api.vn/api/?depth=1";

// Khởi tạo curl
$ch = curl_init();

// Thiết lập curl để lấy dữ liệu
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Thực thi curl và lấy kết quả
$response = curl_exec($ch);
curl_close($ch);

// Chuyển dữ liệu từ JSON sang mảng
$data = json_decode($response, true);

// Hiển thị danh sách tỉnh
foreach ($data as $province) {
    echo $province['name'] . "<br>";
}
?>
<form method="POST" action="save_shipping_info.php">
    <label for="province">Tỉnh/Thành phố:</label>
    <select id="province" name="province">
        <!-- Danh sách tỉnh sẽ được load bằng API -->
    </select>

    <label for="district">Quận/Huyện:</label>
    <select id="district" name="district">
        <!-- Danh sách huyện sẽ được load sau khi chọn tỉnh -->
    </select>

    <label for="ward">Phường/Xã:</label>
    <select id="ward" name="ward">
        <!-- Danh sách xã sẽ được load sau khi chọn huyện -->
    </select>

    <input type="submit" value="Lưu thông tin">
</form>

<script>
    // Lấy danh sách tỉnh
    fetch('https://provinces.open-api.vn/api/?depth=1')
        .then(response => response.json())
        .then(data => {
            let provinceSelect = document.getElementById('province');
            data.forEach(province => {
                let option = document.createElement('option');
                option.value = province.code;
                option.text = province.name;
                provinceSelect.appendChild(option);
            });
        });

    // Lấy danh sách huyện sau khi chọn tỉnh
    document.getElementById('province').addEventListener('change', function() {
        let provinceCode = this.value;
        fetch('https://provinces.open-api.vn/api/p/' + provinceCode + '?depth=2')
            .then(response => response.json())
            .then(data => {
                let districtSelect = document.getElementById('district');
                districtSelect.innerHTML = ''; // Xóa danh sách cũ
                data.districts.forEach(district => {
                    let option = document.createElement('option');
                    option.value = district.code;
                    option.text = district.name;
                    districtSelect.appendChild(option);
                });
            });
    });

    // Lấy danh sách xã sau khi chọn huyện
    document.getElementById('district').addEventListener('change', function() {
        let districtCode = this.value;
        fetch('https://provinces.open-api.vn/api/d/' + districtCode + '?depth=2')
            .then(response => response.json())
            .then(data => {
                let wardSelect = document.getElementById('ward');
                wardSelect.innerHTML = ''; // Xóa danh sách cũ
                data.wards.forEach(ward => {
                    let option = document.createElement('option');
                    option.value = ward.code;
                    option.text = ward.name;
                    wardSelect.appendChild(option);
                });
            });
    });
</script>
