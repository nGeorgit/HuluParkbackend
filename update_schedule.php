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
    !isset($input['week_day']) ||
    !isset($input['start']) ||
    !isset($input['end'])
) {
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
    exit;
}

$week_day = (int)$input['week_day'];
$start = trim($input['start']);
$end = trim($input['end']);




// Insert session
$stmt_insert = $conn->prepare("
    UPDATE weekly_hours 
    SET start_hour=?, end_hour=?
    WHERE week_day=?
");
$stmt_insert->bind_param("ssi", $start, $end, $week_day);

if ($stmt_insert->execute()) {
    echo json_encode([
        "status" => "success",
      	"week_day" => $stmt_insert->insert_id
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update weekly hours"]);
}
$stmt_insert->close();
$conn->close();

