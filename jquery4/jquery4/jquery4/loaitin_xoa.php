<?php
// Get the ID from the URL
$idLT = isset($_GET['idLT']) ? intval($_GET['idLT']) : 0;

// Read data from JSON file
$jsonFile = 'data.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

// Find and remove the record with the specified ID
$found = false;
foreach ($data['loaitin'] as $key => $item) {
    if ($item['idLT'] == $idLT) {
        // Remove this item from the array
        array_splice($data['loaitin'], $key, 1);
        $found = true;
        break;
    }
}

if ($found) {
    // Save updated data back to JSON file
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    
    // Show success message and redirect
    echo "<script>alert('Xóa thành công loại tin có ID: " . $idLT . "!'); window.location='jqueryc.html';</script>";
} else {
    // Invalid ID
    echo "<script>alert('ID không hợp lệ hoặc không tồn tại!'); window.location='jqueryc.html';</script>";
}
?>