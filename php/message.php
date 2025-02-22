<?php
header("Content-Type: application/json");
include '../database/db_connect.php';

// Debugging: Log raw POST data
error_log("RAW POST: " . file_get_contents("php://input"));

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["response" => "Invalid request. Only POST allowed."]);
    exit();
}

// Check if 'text' exists in POST request
if (!isset($_POST['text'])) {
    echo json_encode(["response" => "Error: No 'text' parameter found.", "received" => $_POST]);
    exit();
}

// Get user message
$userMessage = strtolower(trim($_POST['text']));

// Search for response in database
$stmt = $conn->prepare("SELECT bot_response FROM chatbot WHERE user_input = ?");
$stmt->bind_param("s", $userMessage);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($botResponse);
$stmt->fetch();

// Return bot response or default message
if ($stmt->num_rows > 0) {
    echo json_encode(["response" => $botResponse]);
} else {
    echo json_encode(["response" => "I'm sorry, I don't understand that."]);
}

$stmt->close();
$conn->close();
?>
