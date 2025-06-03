<?php
// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', '/path-to-your-log-file/php-error.log'); // Update this path

header("Access-Control-Allow-Origin: http://localhost:3000"); // React app URL
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json"); // Ensure JSON response

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    exit(0);
}

$conn = new mysqli('localhost', 'root', '', 'school');

// Handle connection errors
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error); // Log the error
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

if (isset($_GET['name'])) {
    $name = $conn->real_escape_string($_GET['name']);

    // Query the `module_info` table
    $sql = "SELECT * FROM module_info WHERE name='$name'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Fetch themes for this module from the `themes` table
        $themes_sql = "SELECT * FROM themes WHERE module_name='$name'";
        $themes_result = $conn->query($themes_sql);
        $themes = [];

        while ($theme = $themes_result->fetch_assoc()) {
            $themes[] = $theme;
        }

        $row['themes'] = $themes; // Add themes to module info
        echo json_encode($row); // Output JSON
    } else {
        echo json_encode(["error" => "Module not found"]);
    }
} else {
    echo json_encode(["error" => "No module name provided"]);
}

$conn->close();
?>
