<?php
// Get the list of IDs from the URL
$listid = isset($_GET['listid']) ? $_GET['listid'] : '';
$ids = explode(',', $listid);

// Read data from JSON file
$jsonFile = 'data.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

$deletedIds = [];
$invalidIds = [];

// Process each ID
foreach ($ids as $id) {
    $id = intval($id);
    $found = false;
    
    foreach ($data['loaitin'] as $key => $item) {
        if ($item['idLT'] == $id) {
            // Remove this item from the array
            array_splice($data['loaitin'], $key, 1);
            $deletedIds[] = $id;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $invalidIds[] = $id;
    }
}

// Save updated data back to JSON file
file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));

// Prepare message
if (count($deletedIds) > 0) {
    $message = "Successfully deleted " . count($deletedIds) . " items: " . implode(", ", $deletedIds);
    
    if (count($invalidIds) > 0) {
        $message .= "\nCould not delete " . count($invalidIds) . " items: " . implode(", ", $invalidIds);
    }
} else {
    $message = "No items were deleted!";
}

// Redirect with message
echo "<script>alert('" . $message . "'); window.location='jqueryc.html';</script>";
?>