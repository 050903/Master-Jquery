<?php
// Get the ID from the URL
$idLT = isset($_GET['idLT']) ? intval($_GET['idLT']) : 0;

// Read data from JSON file
$jsonFile = 'data.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

// Find and remove the record with the specified ID
$found = false;
$deletedItem = null;

foreach ($data['loaitin'] as $key => $item) {
    if ($item['idLT'] == $idLT) {
        // Store the item before removing
        $deletedItem = $item;
        
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
    $message = "Successfully deleted topic: " . $deletedItem['Ten'] . " (ID: " . $idLT . ")";
    echo "<script>alert('" . $message . "'); window.location='jqueryc.html';</script>";
} else {
    // Invalid ID
    echo "<script>alert('Invalid or non-existent ID!'); window.location='jqueryc.html';</script>";
}
?>