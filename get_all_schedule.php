<?php
// Enable error reporting (for development only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Database connection
require_once 'db_config.php'; // includes $conn	


// Query schedule for today
$stmt = $conn->prepare("SELECT * FROM weekly_hours");

$stmt->execute();
$result = $stmt->get_result();

$schedules = [];
while ($row = $result->fetch_assoc()) {
    $schedules[] = $row;
}

if (empty($schedules)) {
    echo json_encode([
        "status" => "error",
        "message" => "No schedule found for today",
        "week_day" => $weekday
    ]);
} else {
    echo json_encode([
        "schedules" => $schedules
    ]);
}
?>

