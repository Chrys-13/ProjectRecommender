<?php

include '../settings/config.php';
include '../settings/core.php';

header('Content-Type: application/json');

$login = isset($_REQUEST['login']) ? $_REQUEST['login'] : null; // 'login' can be either username or email
$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : null;

$response = ['success' => false, 'message' => ''];

if (!$login || !$password) {
    $response['message'] = 'Please fill all fields';
    echo json_encode($response);
    exit;
}

$stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $login, $login);

$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    if (password_verify($password, $user['password'])) {
        $response['success'] = true;
        $response['message'] = 'Login successful';

        setSession('user_id', $user['user_id']);
        setSession('username', $login);
    } else {
        $response['message'] = 'Invalid login credentials';
    }
} else {
    $response['message'] = 'Invalid login credentials';
}

echo json_encode($response);

$stmt->close();
$conn->close();