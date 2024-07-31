<?php
require '../settings/config.php';

$operation = isset($_GET['operation']) ? $_GET['operation'] : '';

if ($operation === 'fetch') {
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
    $sql = "SELECT * FROM learning_paths WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $learning_path = $result->fetch_assoc();
    echo json_encode($learning_path);
} elseif ($operation === 'update') {
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
    // Assuming project_sequence is passed as a JSON string in the URL
    $project_sequence = isset($_GET['project_sequence']) ? $_GET['project_sequence'] : '[]';
    $sql = "UPDATE learning_paths SET project_sequence = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $project_sequence, $user_id);
    $stmt->execute();
    echo json_encode(["message" => "Learning path updated successfully"]);
} else {
    echo json_encode(["error" => "Invalid operation specified"]);
}
