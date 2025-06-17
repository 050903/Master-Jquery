<?php
// Get the number of items to add
$numToAdd = isset($_GET['num']) ? intval($_GET['num']) : 0;

if ($numToAdd <= 0) {
    echo "<script>alert('Invalid number of items to add!'); window.location='jqueryc.html';</script>";
    exit;
}

// Read data from JSON file
$jsonFile = 'data.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

// Get categories
$categories = $data['categories'] ?? [];

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
        $ten = $_POST["ten$index"] ?? "New Topic " . ($i + 1);
        $anhien = isset($_POST["anhien$index"]) ? 1 : 0;
        $thutu = intval($_POST["thutu$index"] ?? 0);
        $idTL = intval($_POST["idTL$index"] ?? 1);
        $tagsInput = $_POST["tags$index"] ?? '';
        
        // Process tags
        $tags = array_filter(array_map('trim', explode(',', $tagsInput)));
        
        // Get TenTL based on idTL
        $tenTL = "";
        foreach ($categories as $category) {
            if ($category['idTL'] == $idTL) {
                $tenTL = $category['TenTL'];
                break;
            }
        }
        
        // Create new item with analytics
        $newItem = [
            'idLT' => $maxId + $i + 1,
            'Ten' => $ten,
            'AnHien' => $anhien,
            'ThuTu' => $thutu,
            'idTL' => $idTL,
            'TenTL' => $tenTL,
            'Tags' => $tags,
            'Analytics' => [
                'Views' => rand(100, 500),
                'Engagement' => round(rand(30, 90) / 100, 2),
                'Growth' => round(rand(-5, 25), 1)
            ]
        ];
        
        // Add to data array
        $data['loaitin'][] = $newItem;
    }
    
    // Save updated data back to JSON file
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    
    // Show success message and redirect
    echo "<script>alert('Successfully added $numToAdd new topics!'); window.location='jqueryc.html';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Multiple Topics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
            color: #333;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            color: #333;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: 600;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .item-container {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            background-color: #f8f9fa;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .item-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: #6e8efb;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        input[type="text"], 
        input[type="number"], 
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            vertical-align: middle;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-primary {
            background-color: #6e8efb;
            color: white;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        a {
            color: #6e8efb;
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        .tag-input-help {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-plus-circle"></i> Add <?php echo $numToAdd; ?> New Topics</h2>
        
        <form method="post" action="">
            <?php
            for ($i = 0; $i < $numToAdd; $i++) {
                $index = $i + 1;
                echo '<div class="item-container">';
                echo '<div class="item-title"><i class="fas fa-file-alt"></i> Topic #' . $index . '</div>';
                
                echo '<div class="form-group">';
                echo '<label for="ten' . $index . '">Topic Name:</label>';
                echo '<input type="text" id="ten' . $index . '" name="ten' . $index . '" value="New Topic ' . $index . '" required>';
                echo '</div>';
                
                echo '<div class="form-group">';
                echo '<label for="anhien' . $index . '" class="checkbox-label">';
                echo '<input type="checkbox" id="anhien' . $index . '" name="anhien' . $index . '" checked>';
                echo 'Visible';
                echo '</label>';
                echo '</div>';
                
                echo '<div class="form-group">';
                echo '<label for="thutu' . $index . '">Order:</label>';
                echo '<input type="number" id="thutu' . $index . '" name="thutu' . $index . '" value="' . ($maxId + $index) . '">';
                echo '</div>';
                
                echo '<div class="form-group">';
                echo '<label for="idTL' . $index . '">Category:</label>';
                echo '<select id="idTL' . $index . '" name="idTL' . $index . '">';
                foreach ($categories as $category) {
                    echo '<option value="' . $category['idTL'] . '">' . $category['TenTL'] . '</option>';
                }
                echo '</select>';
                echo '</div>';
                
                echo '<div class="form-group">';
                echo '<label for="tags' . $index . '">Tags:</label>';
                echo '<input type="text" id="tags' . $index . '" name="tags' . $index . '" placeholder="AI, Technology, Innovation">';
                echo '<div class="tag-input-help">Separate tags with commas</div>';
                echo '</div>';
                
                echo '</div>'; // End item-container
            }
            ?>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Topics</button>
                <a href="jqueryc.html" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
        </form>
    </div>
</body>
</html>