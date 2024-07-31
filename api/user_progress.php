<?php
require '../settings/config.php';

$operation = $_GET['operation'] ?? '';

switch ($operation) {
    case 'createProgress':
        $user_id = $_GET['user_id'] ?? 0;
        $project_id = $_GET['project_id'] ?? 0;
        $progress_percentage = $_GET['progress_percentage'] ?? 0.0;

        $sql = "INSERT INTO user_progress (user_id, project_id, progress_percentage) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iid", $user_id, $project_id, $progress_percentage);
        if ($stmt->execute()) {
            echo json_encode(["message" => "User progress created successfully"]);
        } else {
            echo json_encode(["message" => "Failed to create user progress"]);
        }
        break;
    case 'updateProgress':
        $progress_id = $_GET['progress_id'] ?? 0;
        $progress_percentage = $_GET['progress_percentage'] ?? 0.0;

        $sql = "UPDATE user_progress SET progress_percentage = ? WHERE progress_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $progress_percentage, $progress_id);
        if ($stmt->execute()) {
            echo json_encode(["message" => "User progress updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update user progress"]);
        }
        break;
    case 'fetchProgress':
        $user_id = $_GET['user_id'] ?? 0;
        $project_id = $_GET['project_id'] ?? 0;

        $sql = "SELECT * FROM user_progress JOIN projects ON user_progress.project_id = projects.project_id JOIN users ON user_progress.user_id = users.user_id WHERE user_progress.user_id = ? AND user_progress.project_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $project_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $progress = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($progress);
        break;

    case 'fetchSpecificUserProgressAllProjects':
        $user_id = $_GET['user_id'] ?? 0;
        $sql = "SELECT
                projects.project_id, 
                projects.title,
                projects.description,
                projects.difficulty,
                projects.estimated_time,
                projects.prerequisites,
                projects.resources,
                GROUP_CONCAT(tags.tag_name) AS tag_names,
                user_progress.progress_percentage
            FROM 
                user_progress 
            JOIN 
                projects ON user_progress.project_id = projects.project_id 
            JOIN 
                users ON user_progress.user_id = users.user_id 
            JOIN 
                project_tags ON projects.project_id = project_tags.project_id 
            JOIN 
                tags ON project_tags.tag_id = tags.tag_id 
            WHERE 
                user_progress.user_id = ?
            GROUP BY 
                user_progress.progress_percentage, 
                projects.title, 
                projects.description,
                projects.difficulty,
                projects.estimated_time,
                projects.prerequisites,
                projects.resources,
                users.username";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $progress = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($progress);
        break;


    default:
        echo json_encode(["error" => "Invalid operation specified."]);
}
