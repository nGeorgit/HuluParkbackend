<?php
// Debug mode (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Database credentials
require_once 'db_config.php'; // includes $conn

// Get user_id from GET or POST (support both)
$user_id = null;
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id'])) {
    $user_id = (int) $_GET['user_id'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    if (isset($input['user_id'])) {
        $user_id = (int) $input['user_id'];
    }
}

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "Missing or invalid user_id"]);
    exit;
}

// Prepare and execute query
$stmt = $conn->prepare("SELECT wallet FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
} else {
    $wallet = $result->fetch_assoc()['wallet'];
    echo json_encode(["status" => "success", "balance" => floatval($wallet)]);
}

$stmt->close();
$conn->close();

