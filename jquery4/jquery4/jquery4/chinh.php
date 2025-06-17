<?php
// Get the list of IDs from the URL
$listid = isset($_GET['listid']) ? $_GET['listid'] : '';
$ids = explode(',', $listid);

// Read data from JSON file
$jsonFile = 'data.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

// Filter items to edit
$itemsToEdit = [];
foreach ($data['loaitin'] as $item) {
    if (in_array($item['idLT'], $ids)) {
        $itemsToEdit[] = $item;
    }
}

// Process form submission if POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $anhien = isset($_POST['anhien']) ? 1 : 0;
    $thutu = intval($_POST['thutu'] ?? 0);
    $idTL = intval($_POST['idTL'] ?? 0);
    
    // Get TenTL based on idTL
    $tenTL = "Tin xã hội"; // Default
    if ($idTL == 2) $tenTL = "Tin thể thao";
    if ($idTL == 3) $tenTL = "Tin giải trí";
    
    // Update data in the array
    foreach ($data['loaitin'] as $key => $item) {
        if (in_array($item['idLT'], $ids)) {
            $data['loaitin'][$key]['AnHien'] = $anhien;
            $data['loaitin'][$key]['ThuTu'] = $thutu;
            $data['loaitin'][$key]['idTL'] = $idTL;
            $data['loaitin'][$key]['TenTL'] = $tenTL;
        }
    }
    
    // Save updated data back to JSON file
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    
    // Show success message and redirect
    echo "<script>alert('Cập nhật thành công " . count($ids) . " mục!'); window.location='jqueryc.html';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa nhiều loại tin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 150px; }
        input[type="text"], select { width: 250px; padding: 5px; }
        input[type="submit"] { padding: 8px 15px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        input[type="submit"]:hover { background-color: #45a049; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Chỉnh sửa nhiều loại tin</h2>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên loại tin</th>
                <th>Ẩn hiện</th>
                <th>Thứ tự</th>
                <th>Thể loại</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($itemsToEdit as $item) {
                echo "<tr>";
                echo "<td>{$item['idLT']}</td>";
                echo "<td>{$item['Ten']}</td>";
                echo "<td>" . ($item['AnHien'] ? 'Đang hiện' : 'Đang ẩn') . "</td>";
                echo "<td>{$item['ThuTu']}</td>";
                echo "<td>{$item['TenTL']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    
    <form method="post" action="">
        <div class="form-group">
            <label>Các ID được chọn:</label>
            <span><?php echo htmlspecialchars($listid); ?></span>
        </div>
        <div class="form-group">
            <label for="anhien">Ẩn hiện:</label>
            <select id="anhien" name="anhien">
                <option value="1">Hiện</option>
                <option value="0">Ẩn</option>
            </select>
        </div>
        <div class="form-group">
            <label for="thutu">Thứ tự:</label>
            <input type="number" id="thutu" name="thutu" value="0">
        </div>
        <div class="form-group">
            <label for="idTL">Thể loại:</label>
            <select id="idTL" name="idTL">
                <option value="1">Tin xã hội</option>
                <option value="2">Tin thể thao</option>
                <option value="3">Tin giải trí</option>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" value="Cập nhật tất cả">
            <a href="jqueryc.html" style="margin-left: 10px; text-decoration: none;">Quay lại</a>
        </div>
    </form>
</body>
</html>