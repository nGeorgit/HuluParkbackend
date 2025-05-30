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
    !isset($input['email']) ||
    !isset($input['wallet']) ||
    !isset($input['admin'])
) {
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
    exit;
}

$user_id = (int)$input['user_id'];
$email = trim($input['email']);
$wallet = (double)$input['wallet'];
$admin = (int)$input['admin'];



// Insert session
$stmt_insert = $conn->prepare("
    UPDATE users 
    SET email=?, wallet=?, admin=?
    WHERE user_id=?
");
$stmt_insert->bind_param("sidi", $email, $wallet, $admin, $user_id);

if ($stmt_insert->execute()) {
    echo json_encode([
        "status" => "success",
      	"user_id" => $stmt_insert->insert_id
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update user"]);
}
$stmt_insert->close();
$conn->close();

