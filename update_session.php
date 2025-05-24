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
    !isset($input['user_id']) ||
    !isset($input['car_plate']) ||
    !isset($input['duration']) ||
    !isset($input['sector_id']) ||
    !isset($input['start_time']) ||
    !isset($input['session_id'])
) {
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
    exit;
}

$user_id = (int)$input['user_id'];
$car_plate = trim($input['car_plate']);
$duration = trim($input['duration']);
$session_id = (int)$input['session_id'];
$sector_id = trim($input['sector_id']);
$start_time = trim($input['start_time']);




$end_time_query = $conn->prepare("SELECT ADDTIME(?, ?) AS end_time");
$end_time_query->bind_param("ss", $start_time, $duration);
$end_time_query->execute();
$end_time_result = $end_time_query->get_result();
$end_time = $end_time_result->fetch_assoc()['end_time'];
$end_time_query->close();

// Insert session
$stmt_insert = $conn->prepare("
    UPDATE sessions 
    SET user_id=?, sector_id=?, start_time=?, end_time=?, duration=?, car_plate=?
    WHERE session_id=?
");
$stmt_insert->bind_param("iissssi", $user_id, $sector_id, $start_time, $end_time, $duration, $car_plate, $session_id);

if ($stmt_insert->execute()) {
    echo json_encode([
        "status" => "success",
        "session_id" => $stmt_insert->insert_id,
        "start_time" => $start_time,
        "end_time" => $end_time
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to insert session"]);
}
$stmt_insert->close();
$conn->close();

