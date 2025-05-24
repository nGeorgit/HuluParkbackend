<?php
// Enable error reporting (for development only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set header
header('Content-Type: application/json');

// Get current datetime in format "YYYY-MM-DD HH:MM:SS"
$currentTime = date("Y-m-d H:i:s");

echo json_encode([
    "status" => "success",
    "current_time" => $currentTime
]);
?>

