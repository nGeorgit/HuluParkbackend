<?php
// Debug mode (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hulu_park";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Read input
$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['user_id']) || !isset($input['amount'])) {
    echo json_encode(["status" => "error", "message" => "Missing user_id or amount"]);
    exit;
}

$user_id = (int) $input['user_id'];
$amount = (float) $input['amount'];

if ($amount <= 0) {
    echo json_encode(["status" => "error", "message" => "Amount must be greater than 0"]);
    exit;
}

// Update wallet balance
$stmt = $conn->prepare("UPDATE users SET wallet = wallet + ? WHERE user_id = ?");
$stmt->bind_param("di", $amount, $user_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(["status" => "success", "message" => "Money added successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add money (user may not exist)"]);
}

$stmt->close();
$conn->close();

