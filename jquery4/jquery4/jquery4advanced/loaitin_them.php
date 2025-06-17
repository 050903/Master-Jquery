<?php
// Database connection parameters
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "root";      // Default XAMPP password
$dbname = "jquery4";    // Assuming database name based on context

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8");

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if any items were selected
    if (isset($_POST['chon']) && is_array($_POST['chon'])) {
        $selectedIds = $_POST['chon'];
        
        // Process the selected IDs
        echo "<h2>Selected Items:</h2>";
        echo "<ul>";
        foreach ($selectedIds as $id) {
            // Sanitize the ID to prevent SQL injection
            $id = $conn->real_escape_string($id);
            
            // Query to get details of the selected item
            $sql = "SELECT * FROM loaitin WHERE idLT = '$id'";
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<li>ID: " . $row['idLT'] . " - Name: " . $row['Ten'] . "</li>";
            } else {
                echo "<li>ID: $id - Details not found</li>";
            }
        }
        echo "</ul>";
        
        // Example of processing (e.g., updating status)
        echo "<p>Processing completed for " . count($selectedIds) . " item(s).</p>";
        echo "<a href='jquery.html'>Return to list</a>";
    } else {
        echo "<h2>No items were selected</h2>";
        echo "<a href='jquery.html'>Return to list</a>";
    }
} else {
    // If accessed directly without form submission
    echo "<h2>Invalid access</h2>";
    echo "<a href='jquery.html'>Go to form</a>";
}

// Close connection
$conn->close();
?>