<?php
// Kết nối đến MySQL
$servername = "localhost";
$username = "root";
$password = "root"; // hoặc "" nếu bạn không đặt mật khẩu
$dbname = "jquery4";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Câu lệnh tạo bảng loaitin
$sql = "CREATE TABLE loaitin (
    idLT INT AUTO_INCREMENT PRIMARY KEY,
    ten VARCHAR(255) NOT NULL,
    anhien TINYINT(1) DEFAULT 1,
    thutu INT DEFAULT 0
)";

// Thực thi SQL
if ($conn->query($sql) === TRUE) {
    echo "✅ Tạo bảng 'loaitin' thành công!";
} else {
    echo "❌ Lỗi khi tạo bảng: " . $conn->error;
}

// Đóng kết nối
$conn->close();
?>
