<?php
$host = "localhost";      // or your DB host
$db_name = "hulu_park";  // replace with your actual database name
$username = "root"; // replace with your DB username
$password = ""; // replace with your DB password

$conn = new mysqli($host, $username, $password, $db_name);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}
?>
