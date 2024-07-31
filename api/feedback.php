<?php
require '../settings/config.php';

$operation = isset($_GET['operation']) ? $_GET['operation'] : '';

if ($operation === 'submit') {
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
    $project_id = isset($_GET['project_id']) ? $_GET['project_id'] : null;
    $rating = isset($_GET['rating']) ? $_GET['rating'] : null;
    $comments = isset($_GET['comments']) ? $_GET['comments'] : '';

    $sql = "INSERT INTO feedback (user_id, project_id, rating, comments) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $user_id, $project_id, $rating, $comments);
    $stmt->execute();
    echo json_encode(["message" => "Feedback submitted successfully"]);
} elseif ($operation === 'retrieve') {
    $project_id = isset($_GET['project_id']) ? $_GET['project_id'] : null;

    $sql = "SELECT * FROM feedback WHERE project_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $feedback = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($feedback);
} else {
    echo json_encode(["error" => "Invalid operation specified"]);
}
?>