<?php
// Enable error reporting (for development only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Database connection
require_once 'db_config.php'; // includes $conn

// Parse input
$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['user_id'] ?? null;
$amount = $input['amount'] ?? null;

if (!$userId || !$amount || $amount <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

// 1. Get user's wallet
$stmt = $conn->prepare("SELECT wallet FROM users WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($wallet);
if (!$stmt->fetch()) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}
$stmt->close();

// 2. Check and update if enough balance
if ($wallet >= $amount) {
    $stmt = $conn->prepare("UPDATE users SET wallet = wallet - ? WHERE user_id = ?");
    $stmt->bind_param("di", $amount, $userId);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Payment successful"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update wallet"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Insufficient balance"]);
}
?>

