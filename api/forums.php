<?php
require '../settings/config.php';
header('Content-Type: application/json');

$operation = isset($_GET['operation']) ? $_GET['operation'] : '';

if ($operation === 'create_forum') {
    // Create a new discussion forum
    $project_id = isset($_GET['project_id']) ? $_GET['project_id'] : 0;
    $title = isset($_GET['title']) ? $_GET['title'] : '';

    $sql = "INSERT INTO discussion_forums (project_id, title) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $project_id, $title);
    $stmt->execute();
    echo json_encode(["forum_id" => $stmt->insert_id]);
} elseif ($operation === 'submit_post') {
    // Submit a new post to a forum
    $forum_id = isset($_GET['forum_id']) ? $_GET['forum_id'] : 0;
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
    $content = isset($_GET['content']) ? $_GET['content'] : '';

    $sql = "INSERT INTO forum_posts (forum_id, user_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $forum_id, $user_id, $content);
    $stmt->execute();
    echo json_encode(["post_id" => $stmt->insert_id]);
} elseif ($operation === 'fetch_posts') {
    // Fetch all posts for a specific forum
    $forum_id = isset($_GET['forum_id']) ? $_GET['forum_id'] : 0;

    $sql = "
        SELECT fp.*, u.username 
        FROM forum_posts fp
        JOIN users u ON fp.user_id = u.user_id
        WHERE fp.forum_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $forum_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($posts);
} elseif ($operation === 'fetch_forums') {
    $sql = "SELECT * FROM discussion_forums";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $forums = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($forums);
} else {
    echo json_encode(["error" => "Invalid operation specified"]);
}
