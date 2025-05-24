<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set response header
header('Content-Type: application/json');

// Database configuration
require_once 'db_config.php'; // includes $conn

// Read JSON input
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Validate user_id
if (!is_array($data) || !isset($data['user_id']) || !is_numeric($data['user_id'])) {
    echo json_encode(["status" => "error", "message" => "user_id is missing or invalid"]);
    exit;
}

$user_id = (int)$data['user_id'];

// Prepare query to fetch sessions
$stmt = $conn->prepare("SELECT * FROM sessions WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Collect results
$sessions = [];

while ($row = $result->fetch_assoc()) {

    $sector_id = $row['sector_id'];

    // Get sector_code from sectors table
    $sector_stmt = $conn->prepare("SELECT sector_code FROM sectors WHERE sector_id = ?");
    $sector_stmt->bind_param("i", $sector_id);
    $sector_stmt->execute();
    $sector_result = $sector_stmt->get_result();

    if ($sector_row = $sector_result->fetch_assoc()) {
        $row['sector_code'] = $sector_row['sector_code'];
    } else {
        $row['sector_code'] = null; // Or a default value if not found
    }

    $sector_stmt->close();

    $sessions[] = $row;
}

$stmt->close();
$conn->close();

// Return sessions or empty array
echo json_encode([
    "status" => "success",
    "user_id" => $user_id,
    "sessions" => $sessions
]);

