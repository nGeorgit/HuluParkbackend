<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// DB config
require_once 'db_config.php'; // includes $conn

// Parse input
$input = json_decode(file_get_contents('php://input'), true);
$sectorCode = $input['sector_code'] ?? '';
$userId = $input['user_id'] ?? '';
$durId = $input['dur_id'] ?? '';

if (!$sectorCode || !$userId || !$durId) {
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
    exit;
}

// 1. Get half_hour_rate from sector
$stmt = $conn->prepare("SELECT half_hour_rate FROM sectors WHERE sector_code = ?");
$stmt->bind_param("s", $sectorCode);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Sector not found"]);
    exit;
}
$rate = $result->fetch_assoc()['half_hour_rate'];
$stmt->close();

// 2. Get user wallet
$stmt = $conn->prepare("SELECT wallet FROM users WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}
$wallet = $result->fetch_assoc()['wallet'];
$stmt->close();

// 3. Get duration in HH:MM:SS format
$stmt = $conn->prepare("SELECT dur FROM durations WHERE dur_id = ?");
$stmt->bind_param("i", $durId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Duration not found"]);
    exit;
}
$duration = $result->fetch_assoc()['dur'];
$stmt->close();

// Convert HH:MM:SS to total minutes
list($hours, $minutes, $seconds) = explode(':', $duration);
$totalMinutes = $hours * 60 + $minutes + ($seconds > 0 ? 1 : 0);

// Calculate cost
$units = ceil($totalMinutes / 30);
$cost = $units * $rate;

// Check if user can afford it
$canAfford = $wallet >= $cost;

echo json_encode([
    "status" => "success",
    "wallet" => round($wallet, 2),
    "cost" => round($cost, 2),
    "can_afford" => $canAfford
]);

