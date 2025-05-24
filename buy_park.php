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
    !isset($input['dur_id']) ||
    !isset($input['sector_code'])
) {
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
    exit;
}

$user_id = (int)$input['user_id'];
$car_plate = trim($input['car_plate']);
$dur_id = (int)$input['dur_id'];
$sector_code = trim($input['sector_code']);

// Get sector_id
$stmt_sector = $conn->prepare("SELECT sector_id FROM sectors WHERE sector_code = ?");
$stmt_sector->bind_param("s", $sector_code);
$stmt_sector->execute();
$result_sector = $stmt_sector->get_result();
if ($result_sector->num_rows == 0) {
    echo json_encode(["status" => "error", "message" => "Sector not found"]);
    exit;
}
$sector_id = $result_sector->fetch_assoc()['sector_id'];
$stmt_sector->close();

// Get duration time
$stmt_duration = $conn->prepare("SELECT dur FROM durations WHERE dur_id = ?");
$stmt_duration->bind_param("i", $dur_id);
$stmt_duration->execute();
$result_duration = $stmt_duration->get_result();
if ($result_duration->num_rows == 0) {
    echo json_encode(["status" => "error", "message" => "Duration not found"]);
    exit;
}
$duration = $result_duration->fetch_assoc()['dur'];
$stmt_duration->close();

// Create session
$start_time = date("Y-m-d H:i:s");
$end_time_query = $conn->prepare("SELECT ADDTIME(?, ?) AS end_time");
$end_time_query->bind_param("ss", $start_time, $duration);
$end_time_query->execute();
$end_time_result = $end_time_query->get_result();
$end_time = $end_time_result->fetch_assoc()['end_time'];
$end_time_query->close();

// Insert session
$stmt_insert = $conn->prepare("
    INSERT INTO sessions (user_id, sector_id, start_time, end_time, duration, car_plate)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt_insert->bind_param("iissss", $user_id, $sector_id, $start_time, $end_time, $duration, $car_plate);

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

