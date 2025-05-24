<?php
// Debug mode (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Database credentials
require_once 'db_config.php'; // includes $conn

// SQL query to select all rows from the sectors table
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch all rows into an associative array
    $rows = array();
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    // Return the data as JSON
    echo json_encode($rows);
} else {
    echo json_encode(["status" => "success", "message" => "No sectors found"]); // changed message to success, since the script executed correctly
}

$conn->close();
?>

