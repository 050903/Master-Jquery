<?php
// Get the ID from the URL
$idLT = isset($_GET['idLT']) ? intval($_GET['idLT']) : 0;

// Read data from JSON file
$jsonFile = 'data.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

// Get categories
$categories = $data['categories'] ?? [];

// Initialize variables
$ten = "";
$anhien = 1;
$thutu = 0;
$idTL = 0;
$tenTL = "";
$tags = [];
$analytics = null;

// Process form submission if POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $ten = $_POST['ten'] ?? '';
    $anhien = isset($_POST['anhien']) ? 1 : 0;
    $thutu = intval($_POST['thutu'] ?? 0);
    $idTL = intval($_POST['idTL'] ?? 0);
    $tagsInput = $_POST['tags'] ?? '';
    
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
    
    // Update data in the array
    foreach ($data['loaitin'] as $key => $item) {
        if ($item['idLT'] == $idLT) {
            $data['loaitin'][$key]['Ten'] = $ten;
            $data['loaitin'][$key]['AnHien'] = $anhien;
            $data['loaitin'][$key]['ThuTu'] = $thutu;
            $data['loaitin'][$key]['idTL'] = $idTL;
            $data['loaitin'][$key]['TenTL'] = $tenTL;
            
            // Only update tags if they exist in the original item
            if (isset($item['Tags'])) {
                $data['loaitin'][$key]['Tags'] = $tags;
            }
            
            break;
        }
    }
    
    // Save updated data back to JSON file
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    
    // Show success message and redirect
    echo "<script>alert('Topic updated successfully!'); window.location='jqueryc.html';</script>";
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
            $tenTL = $item['TenTL'];
            $tags = isset($item['Tags']) ? $item['Tags'] : [];
            $analytics = isset($item['Analytics']) ? $item['Analytics'] : null;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        // If ID not found
        echo "<script>alert('Topic not found!'); window.location='jqueryc.html';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Topic</title>
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
            display: flex;
            gap: 20px;
        }
        
        .main-content {
            flex: 1;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar {
            width: 300px;
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
        
        textarea {
            min-height: 100px;
            resize: vertical;
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
        
        .analytics-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .analytics-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .analytics-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .analytics-label {
            color: #6c757d;
        }
        
        .analytics-value {
            font-weight: 500;
        }
        
        .trending-up {
            color: #28a745;
        }
        
        .trending-down {
            color: #dc3545;
        }
        
        .neutral {
            color: #6c757d;
        }
        
        .tag-input-help {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <h2><i class="fas fa-edit"></i> Edit Topic (ID: <?php echo $idLT; ?>)</h2>
            
            <form method="post" action="">
                <div class="form-group">
                    <label for="ten">Topic Name:</label>
                    <input type="text" id="ten" name="ten" value="<?php echo htmlspecialchars($ten); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="anhien">Visibility:</label>
                    <div>
                        <input type="checkbox" id="anhien" name="anhien" <?php echo $anhien ? 'checked' : ''; ?>>
                        <label for="anhien" style="display: inline;">Visible</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="thutu">Order:</label>
                    <input type="number" id="thutu" name="thutu" value="<?php echo $thutu; ?>">
                </div>
                
                <div class="form-group">
                    <label for="idTL">Category:</label>
                    <select id="idTL" name="idTL">
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['idTL']; ?>" <?php echo ($idTL == $category['idTL']) ? 'selected' : ''; ?>><?php echo $category['TenTL']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <?php if (!empty($tags)): ?>
                <div class="form-group">
                    <label for="tags">Tags:</label>
                    <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars(implode(', ', $tags)); ?>">
                    <div class="tag-input-help">Separate tags with commas</div>
                </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                    <a href="jqueryc.html" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
            </form>
        </div>
        
        <?php if ($analytics): ?>
        <div class="sidebar">
            <div class="analytics-card">
                <div class="analytics-title">
                    <i class="fas fa-chart-line"></i> Analytics
                </div>
                
                <div class="analytics-item">
                    <span class="analytics-label">Views:</span>
                    <span class="analytics-value"><?php echo number_format($analytics['Views']); ?></span>
                </div>
                
                <div class="analytics-item">
                    <span class="analytics-label">Engagement:</span>
                    <span class="analytics-value"><?php echo number_format($analytics['Engagement'] * 100, 0); ?>%</span>
                </div>
                
                <div class="analytics-item">
                    <span class="analytics-label">Growth:</span>
                    <span class="analytics-value">
                        <?php 
                        if ($analytics['Growth'] > 15) {
                            echo '<i class="fas fa-arrow-up trending-up"></i> ';
                        } elseif ($analytics['Growth'] < 0) {
                            echo '<i class="fas fa-arrow-down trending-down"></i> ';
                        } else {
                            echo '<i class="fas fa-minus neutral"></i> ';
                        }
                        echo $analytics['Growth'] . '%';
                        ?>
                    </span>
                </div>
            </div>
            
            <div class="analytics-card">
                <div class="analytics-title">
                    <i class="fas fa-lightbulb"></i> AI Suggestions
                </div>
                
                <p>
                    <?php
                    if ($analytics['Engagement'] > 0.8) {
                        echo "This topic has high engagement. Consider creating more related content to capitalize on its popularity.";
                    } elseif ($analytics['Growth'] > 15) {
                        echo "This topic is trending upward. Consider featuring it more prominently.";
                    } elseif ($analytics['Views'] > 2000) {
                        echo "This is one of your most viewed topics. Consider refreshing the content to maintain interest.";
                    } else {
                        echo "Consider adding more keywords and improving content to increase engagement.";
                    }
                    ?>
                </p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>