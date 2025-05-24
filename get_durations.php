<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set response header
header('Content-Type: application/json');

// Database configuration
require_once 'db_config.php'; // includes $conn

// Query to get all durations
$query = "SELECT * FROM durations";
$result = $conn->query($query);

$durations = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $durations[] = $row;
    }
    echo json_encode([
        "status" => "success",
        "durations" => $durations
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Query failed: " . $conn->error
    ]);
}

$conn->close();

