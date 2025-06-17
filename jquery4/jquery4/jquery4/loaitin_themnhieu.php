<?php
// Get the number of items to add
$numToAdd = isset($_GET['num']) ? intval($_GET['num']) : 0;

if ($numToAdd <= 0) {
    echo "<script>alert('Số lượng thêm không hợp lệ!'); window.location='jqueryc.html';</script>";
    exit;
}

// Read data from JSON file
$jsonFile = 'data.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

// Find the highest ID to generate new IDs
$maxId = 0;
foreach ($data['loaitin'] as $item) {
    if ($item['idLT'] > $maxId) {
        $maxId = $item['idLT'];
    }
}

// Process form submission if POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new items to the data
    for ($i = 0; $i < $numToAdd; $i++) {
        $index = $i + 1;
        $ten = $_POST["ten$index"] ?? "Loại tin mới " . ($i + 1);
        $anhien = isset($_POST["anhien$index"]) ? 1 : 0;
        $thutu = intval($_POST["thutu$index"] ?? 0);
        $idTL = intval($_POST["idTL$index"] ?? 1);
        
        // Get TenTL based on idTL
        $tenTL = "Tin xã hội"; // Default
        if ($idTL == 2) $tenTL = "Tin thể thao";
        if ($idTL == 3) $tenTL = "Tin giải trí";
        
        // Create new item
        $newItem = [
            'idLT' => $maxId + $i + 1,
            'Ten' => $ten,
            'AnHien' => $anhien,
            'ThuTu' => $thutu,
            'idTL' => $idTL,
            'TenTL' => $tenTL
        ];
        
        // Add to data array
        $data['loaitin'][] = $newItem;
    }
    
    // Save updated data back to JSON file
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    
    // Show success message and redirect
    echo "<script>alert('Đã thêm thành công $numToAdd loại tin mới!'); window.location='jqueryc.html';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm nhiều loại tin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #333; }
        .form-group { margin-bottom: 15px; }
        .item-container { 
            border: 1px solid #ddd; 
            padding: 15px; 
            margin-bottom: 20px; 
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .item-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #007bff;
        }
        label { display: inline-block; width: 100px; }
        input[type="text"], select { width: 250px; padding: 5px; }
        input[type="submit"] { 
            padding: 8px 15px; 
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            cursor: pointer;
            border-radius: 3px;
        }
        input[type="submit"]:hover { background-color: #45a049; }
        a { 
            text-decoration: none; 
            margin-left: 10px;
            color: #007bff;
        }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>Thêm <?php echo $numToAdd; ?> loại tin mới</h2>
    
    <form method="post" action="">
        <?php
        for ($i = 0; $i < $numToAdd; $i++) {
            $index = $i + 1;
            echo '<div class="item-container">';
            echo '<div class="item-title">Loại tin #' . $index . '</div>';
            
            echo '<div class="form-group">';
            echo '<label for="ten' . $index . '">Tên loại tin:</label>';
            echo '<input type="text" id="ten' . $index . '" name="ten' . $index . '" value="Loại tin mới ' . $index . '" required>';
            echo '</div>';
            
            echo '<div class="form-group">';
            echo '<label for="anhien' . $index . '">Ẩn hiện:</label>';
            echo '<input type="checkbox" id="anhien' . $index . '" name="anhien' . $index . '" checked>';
            echo '</div>';
            
            echo '<div class="form-group">';
            echo '<label for="thutu' . $index . '">Thứ tự:</label>';
            echo '<input type="number" id="thutu' . $index . '" name="thutu' . $index . '" value="' . ($maxId + $index) . '">';
            echo '</div>';
            
            echo '<div class="form-group">';
            echo '<label for="idTL' . $index . '">Thể loại:</label>';
            echo '<select id="idTL' . $index . '" name="idTL' . $index . '">';
            echo '<option value="1">Tin xã hội</option>';
            echo '<option value="2">Tin thể thao</option>';
            echo '<option value="3">Tin giải trí</option>';
            echo '</select>';
            echo '</div>';
            
            echo '</div>'; // End item-container
        }
        ?>
        
        <div class="form-group">
            <input type="submit" value="Thêm loại tin">
            <a href="jqueryc.html">Quay lại</a>
        </div>
    </form>
</body>
</html>