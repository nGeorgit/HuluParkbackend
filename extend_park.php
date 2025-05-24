<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once 'db_config.php'; // includes $conn


$input = json_decode(file_get_contents("php://input"), true);

if (
    !isset($input['user_id']) ||
    !isset($input['car_plate']) ||
    !isset($input['dur_id']) ||
    !isset($input['sector_code']) ||
    !isset($input['session_id'])
) {
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
    exit;
}

$user_id = (int)$input['user_id'];
$car_plate = trim($input['car_plate']);
$dur_id = (int)$input['dur_id'];
$sector_code = trim($input['sector_code']);
$session_id = (int)$input['session_id'];

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

// Fetch current end_time from session
$stmt_end = $conn->prepare("SELECT end_time FROM sessions WHERE session_id = ?");
$stmt_end->bind_param("i", $session_id);
$stmt_end->execute();
$result_end = $stmt_end->get_result();
if ($result_end->num_rows == 0) {
    echo json_encode(["status" => "error", "message" => "Session not found"]);
    exit;
}
$current_end_time = $result_end->fetch_assoc()['end_time'];
$stmt_end->close();

// Calculate new end time
$stmt_add = $conn->prepare("SELECT ADDTIME(?, ?) AS new_end_time");
$stmt_add->bind_param("ss", $current_end_time, $duration);
$stmt_add->execute();
$result_add = $stmt_add->get_result();
$new_end_time = $result_add->fetch_assoc()['new_end_time'];
$stmt_add->close();

// Update session
$stmt_update = $conn->prepare("
    UPDATE sessions 
    SET end_time = ?, duration = ADDTIME(duration, ?) 
    WHERE session_id = ?
");
$stmt_update->bind_param("ssi", $new_end_time, $duration, $session_id);

if ($stmt_update->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Parking extended successfully",
        "session_id" => $session_id,
        "new_end_time" => $new_end_time
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update session"]);
}
$stmt_update->close();
$conn->close();

