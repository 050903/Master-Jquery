<?php
// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if any items were selected
    if (isset($_POST['chon']) && is_array($_POST['chon'])) {
        $selectedIds = $_POST['chon'];
        
        // Display the selected IDs
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Form Processing Result</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                    line-height: 1.6;
                }
                h2 {
                    color: #333;
                }
                ul {
                    list-style-type: circle;
                    padding-left: 20px;
                }
                a {
                    display: inline-block;
                    margin-top: 20px;
                    padding: 8px 16px;
                    background-color: #4CAF50;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                }
                a:hover {
                    background-color: #45a049;
                }
            </style>
        </head>
        <body>
            <h2>Selected Items:</h2>
            <ul>";
            
        foreach ($selectedIds as $id) {
            echo "<li>ID: " . htmlspecialchars($id) . " - Item selected</li>";
        }
        
        echo "</ul>
            <p>Processing completed for " . count($selectedIds) . " item(s).</p>
            <a href='jquery.html'>Return to list</a>
        </body>
        </html>";
    } else {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Form Processing Result</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                    line-height: 1.6;
                }
                h2 {
                    color: #f44336;
                }
                a {
                    display: inline-block;
                    margin-top: 20px;
                    padding: 8px 16px;
                    background-color: #4CAF50;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                }
                a:hover {
                    background-color: #45a049;
                }
            </style>
        </head>
        <body>
            <h2>No items were selected</h2>
            <a href='jquery.html'>Return to list</a>
        </body>
        </html>";
    }
} else {
    // If accessed directly without form submission
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Invalid Access</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                line-height: 1.6;
            }
            h2 {
                color: #f44336;
            }
            a {
                display: inline-block;
                margin-top: 20px;
                padding: 8px 16px;
                background-color: #4CAF50;
                color: white;
                text-decoration: none;
                border-radius: 4px;
            }
            a:hover {
                background-color: #45a049;
            }
        </style>
    </head>
    <body>
        <h2>Invalid access</h2>
        <p>This page should be accessed through the form submission.</p>
        <a href='jquery.html'>Go to form</a>
    </body>
    </html>";
}
?>