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

// Validate sector_id
if (!is_array($data) || !isset($data['sector_id']) || !is_numeric($data['sector_id'])) {
    echo json_encode(["status" => "error", "message" => "sector_id is missing or invalid"]);
    exit;
}

$sector_id = (int)$data['sector_id'];

// Prepare query to fetch sector rate
$stmt = $conn->prepare("SELECT half_hour_rate FROM sectors WHERE sector_id = ?");
$stmt->bind_param("i", $sector_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "status" => "success",
        "sector_id" => $sector_id,
        "half_hour_rate" => $row['half_hour_rate']
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Sector not found"
    ]);
}

$stmt->close();
$conn->close();
?>
