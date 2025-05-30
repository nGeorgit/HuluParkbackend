<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set response header
header('Content-Type: application/json');

// Database configuration
require_once 'db_config.php'; // includes $conn

// Read the JSON input from the request body
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Check if JSON is valid and email is provided
if (!is_array($data) || !isset($data['email'])) {
    echo json_encode(["status" => "error", "message" => "Email parameter is missing or invalid JSON"]);
    exit;
}

$email = trim($data['email']);

// Prepare and execute SELECT statement
$stmt_check = $conn->prepare("SELECT user_id, admin FROM users WHERE email = ?");
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    // User found, return login success
    $row = $result->fetch_assoc();
    $response = [
        "status" => "success",
        "message" => "Login successful",
        "user_id" => $row['user_id'],
        "admin" => $row['admin']
    ];
} else {
    // User not found, create new account
    $stmt_insert = $conn->prepare("INSERT INTO users (email, admin) VALUES (?, 0)");
    $stmt_insert->bind_param("s", $email);

    if ($stmt_insert->execute()) {
        $newUserId = $conn->insert_id;
        $response = [
            "status" => "success",
            "message" => "Account created and login successful",
            "user_id" => $newUserId,
            "admin" => 0
        ];
    } else {
        $response = [
            "status" => "error",
            "message" => "Failed to create account: " . $stmt_insert->error
        ];
    }

    $stmt_insert->close();
}

$stmt_check->close();
$conn->close();

echo json_encode($response);

