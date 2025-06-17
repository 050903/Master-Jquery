<?php
// Get the ID from the URL
$idLT = isset($_GET['idLT']) ? intval($_GET['idLT']) : 0;

// Read data from JSON file
$jsonFile = 'data.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

// Initialize variables
$ten = "";
$anhien = 1;
$thutu = 0;
$idTL = 0;
$tenTL = "";

// Process form submission if POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $ten = $_POST['ten'] ?? '';
    $anhien = isset($_POST['anhien']) ? 1 : 0;
    $thutu = intval($_POST['thutu'] ?? 0);
    $idTL = intval($_POST['idTL'] ?? 0);
    
    // Get TenTL based on idTL
    $tenTL = "Tin xã hội"; // Default
    if ($idTL == 2) $tenTL = "Tin thể thao";
    if ($idTL == 3) $tenTL = "Tin giải trí";
    
    // Update data in the array
    foreach ($data['loaitin'] as $key => $item) {
        if ($item['idLT'] == $idLT) {
            $data['loaitin'][$key]['Ten'] = $ten;
            $data['loaitin'][$key]['AnHien'] = $anhien;
            $data['loaitin'][$key]['ThuTu'] = $thutu;
            $data['loaitin'][$key]['idTL'] = $idTL;
            $data['loaitin'][$key]['TenTL'] = $tenTL;
            break;
        }
    }
    
    // Save updated data back to JSON file
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    
    // Show success message and redirect
    echo "<script>alert('Cập nhật thành công!'); window.location='jqueryc.html';</script>";
    exit;
} else {
    // Find the record with the specified ID
    $found = false;
    foreach ($data['loaitin'] as $item) {
        if ($item['idLT'] == $idLT) {
            $ten = $item['Ten'];
            $anhien = $item['AnHien'];
            $thutu = $item['ThuTu'];
            $idTL = $item['idTL'];
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        // If ID not found
        echo "<script>alert('Không tìm thấy loại tin!'); window.location='jqueryc.html';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa loại tin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 100px; }
        input[type="text"], select { width: 250px; padding: 5px; }
        input[type="submit"] { padding: 8px 15px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        input[type="submit"]:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <h2>Chỉnh sửa loại tin (ID: <?php echo $idLT; ?>)</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="ten">Tên loại tin:</label>
            <input type="text" id="ten" name="ten" value="<?php echo htmlspecialchars($ten); ?>" required>
        </div>
        <div class="form-group">
            <label for="anhien">Ẩn hiện:</label>
            <input type="checkbox" id="anhien" name="anhien" <?php echo $anhien ? 'checked' : ''; ?>>
        </div>
        <div class="form-group">
            <label for="thutu">Thứ tự:</label>
            <input type="number" id="thutu" name="thutu" value="<?php echo $thutu; ?>">
        </div>
        <div class="form-group">
            <label for="idTL">Thể loại:</label>
            <select id="idTL" name="idTL">
                <option value="1" <?php echo ($idTL == 1) ? 'selected' : ''; ?>>Tin xã hội</option>
                <option value="2" <?php echo ($idTL == 2) ? 'selected' : ''; ?>>Tin thể thao</option>
                <option value="3" <?php echo ($idTL == 3) ? 'selected' : ''; ?>>Tin giải trí</option>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" value="Cập nhật">
            <a href="jqueryc.html" style="margin-left: 10px; text-decoration: none;">Quay lại</a>
        </div>
    </form>
</body>
</html>