<?php
// Get the list of IDs from the URL
$listid = isset($_GET['listid']) ? $_GET['listid'] : '';
$ids = explode(',', $listid);

// Read data from JSON file
$jsonFile = 'data.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

// Get categories
$categories = $data['categories'] ?? [];

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
    $tenTL = "";
    foreach ($categories as $category) {
        if ($category['idTL'] == $idTL) {
            $tenTL = $category['TenTL'];
            break;
        }
    }
    
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
    echo "<script>alert('Updated " . count($ids) . " items successfully!'); window.location='jqueryc.html';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batch Edit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #000;
            --secondary: #333;
            --accent: #3d66b4;
            --bg: #fff;
            --text: #111;
            --border: #eee;
            --hover: #f5f5f5;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 40px;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.5;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        h2 {
            font-size: 24px;
            font-weight: 500;
            margin: 0 0 30px 0;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--secondary);
        }
        
        input[type="text"], 
        input[type="number"], 
        select {
            width: 100%;
            max-width: 400px;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 14px;
        }
        
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            vertical-align: middle;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
            margin-right: 10px;
        }
        
        .btn-primary {
            background-color: var(--accent);
            color: white;
        }
        
        .btn-secondary {
            background-color: #f1f1f1;
            color: var(--secondary);
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        a {
            text-decoration: none;
            color: var(--accent);
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border: 1px solid var(--border);
            border-radius: 4px;
            overflow: hidden;
        }
        
        th, td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        
        th {
            background-color: var(--hover);
            font-weight: 500;
        }
        
        .tag {
            display: inline-block;
            padding: 4px 8px;
            background-color: var(--hover);
            border-radius: 4px;
            font-size: 12px;
            margin-right: 4px;
            margin-top: 4px;
        }
        
        .selected-ids {
            background-color: var(--hover);
            padding: 12px;
            border-radius: 4px;
            font-family: monospace;
            margin-bottom: 8px;
        }
        
        .status-active {
            color: #22c55e;
        }
        
        .status-inactive {
            color: #ef4444;
        }
        
        .actions {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-edit"></i> Batch Edit</h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Order</th>
                    <th>Category</th>
                    <?php if (!empty($itemsToEdit) && isset($itemsToEdit[0]['Tags'])): ?>
                    <th>Tags</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($itemsToEdit as $item) {
                    echo "<tr>";
                    echo "<td>{$item['idLT']}</td>";
                    echo "<td>{$item['Ten']}</td>";
                    echo "<td><span class='" . ($item['AnHien'] ? 'status-active' : 'status-inactive') . "'>" . 
                         ($item['AnHien'] ? 'Active' : 'Hidden') . "</span></td>";
                    echo "<td>{$item['ThuTu']}</td>";
                    echo "<td>{$item['TenTL']}</td>";
                    
                    if (isset($item['Tags'])) {
                        echo "<td>";
                        foreach ($item['Tags'] as $tag) {
                            echo "<span class='tag'>{$tag}</span>";
                        }
                        echo "</td>";
                    }
                    
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        
        <form method="post" action="">
            <div class="form-group">
                <label>Selected IDs:</label>
                <div class="selected-ids"><?php echo htmlspecialchars($listid); ?></div>
            </div>
            <div class="form-group">
                <label for="anhien">Visibility:</label>
                <div>
                    <input type="checkbox" id="anhien" name="anhien" checked>
                    <label for="anhien" style="display: inline; font-weight: normal;">Active</label>
                </div>
            </div>
            <div class="form-group">
                <label for="thutu">Order:</label>
                <input type="number" id="thutu" name="thutu" value="0">
            </div>
            <div class="form-group">
                <label for="idTL">Category:</label>
                <select id="idTL" name="idTL">
                    <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['idTL']; ?>"><?php echo $category['TenTL']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="actions">
                <button type="submit" class="btn btn-primary">Update All</button>
                <a href="jqueryc.html" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>