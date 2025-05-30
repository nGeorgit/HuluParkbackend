<?php
// Debug mode (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Database
require_once 'db_config.php'; // includes $conn

// Get input
$input = json_decode(file_get_contents("php://input"), true);

if (
    !isset($input['sector_id']) ||
    !isset($input['sector_code']) ||
    !isset($input['half_hour_rate']) ||
    !isset($input['location_point_a_lat']) ||
    !isset($input['location_point_a_lon']) ||
    !isset($input['location_point_b_lat']) ||
    !isset($input['location_point_b_lon'])
) {
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
    exit;
}

$sector_id = (int)$input['sector_id'];
$sector_code = trim($input['sector_code']);
$half_hour_rate = (double)$input['half_hour_rate'];
$location_point_a_lat = (double)$input['location_point_a_lat'];
$location_point_a_lon = (double)$input['location_point_a_lon'];
$location_point_b_lat = (double)$input['location_point_b_lat'];
$location_point_b_lon = (double)$input['location_point_b_lon'];



// Insert session
$stmt_insert = $conn->prepare("
    UPDATE sectors 
    SET sector_code=?, half_hour_rate=?, location_point_a_lat=?, location_point_a_lon=?, location_point_b_lat=?, location_point_b_lon=?
    WHERE sector_id=?
");
$stmt_insert->bind_param("sdddddi", $sector_code, $half_hour_rate, $location_point_a_lat, $location_point_a_lon, $location_point_b_lat, $location_point_b_lon, $sector_id);

if ($stmt_insert->execute()) {
    echo json_encode([
        "status" => "success",
      	"sector_id" => $stmt_insert->insert_id
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update sector"]);
}
$stmt_insert->close();
$conn->close();

