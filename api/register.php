<?php

include '../settings/config.php';

header('Content-Type: application/json');


$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : null;
$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : null;
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;

$response = ['success' => false, 'message' => ''];

if (!$username || !$password || !$email) {
    $response['message'] = 'Please fill all fields';
    echo json_encode($response);
    exit;
}

$password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $email);

try {
    $stmt->execute();
    $response['success'] = true;
    $response['message'] = 'User registered successfully';
} catch (Exception $e) {
    if ($conn->errno == 1062) {
        $response['message'] = 'Username or email already exists';
    } else {
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);

$stmt->close();
$conn->close();