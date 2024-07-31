<?php

header('Content-Type: application/json');
require '../settings/config.php';

$operation = isset($_GET['operation']) ? $_GET['operation'] : '';
switch ($operation) {
    case 'fetchRandom':
        $sql = "SELECT
    projects.project_id, 
    projects.title,
    projects.description,
    projects.difficulty,
    projects.estimated_time,
    projects.prerequisites,
    projects.resources,
    GROUP_CONCAT(tags.tag_id) AS tag_ids
FROM 
    projects
JOIN 
    project_tags ON projects.project_id = project_tags.project_id 
JOIN 
    tags ON project_tags.tag_id = tags.tag_id 
WHERE 
    1 = 1
GROUP BY
    projects.project_id, 
    projects.title, 
    projects.description,
    projects.difficulty,
    projects.estimated_time,
    projects.prerequisites,
    projects.resources
ORDER BY 
    RAND()
LIMIT 3";
        $result = $conn->query($sql);
        $projects = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($projects);
        break;

    case 'recommend':
        $tagIds = $_GET['tagIds'];
        $tagIdsArray = explode(',', $tagIds);
        $tagIdsPlaceholder = implode(',', array_fill(0, count($tagIdsArray), '?'));

        $user_id = $_GET['user_id'] ?? 0;

        $sql = "SELECT 
                projects.project_id,
                projects.title,
                projects.description,
                projects.difficulty,
                projects.estimated_time,
                projects.prerequisites,
                projects.resources,
                COUNT(project_tags.tag_id) as tag_count
            FROM 
                projects
            JOIN 
                project_tags ON projects.project_id = project_tags.project_id
            LEFT JOIN 
                user_projects ON projects.project_id = user_projects.project_id AND user_projects.user_id = ?
            WHERE 
                project_tags.tag_id IN ($tagIdsPlaceholder) AND user_projects.project_id IS NULL
            GROUP BY 
                projects.project_id
            ORDER BY 
                tag_count DESC";

        $stmt = $conn->prepare($sql);

        // Create a dynamic parameter type string
        $paramTypes = str_repeat('i', count($tagIdsArray)) . 'i';

        // Merge tagIdsArray and user_id into a single array
        $params = array_merge($tagIdsArray, [$user_id]);

        // Use call_user_func_array to bind parameters dynamically
        $stmt->bind_param($paramTypes, ...$params);

        $stmt->execute();
        $result = $stmt->get_result();
        $projects = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($projects);
        break;


    case 'createRecommendation':
        $user_id = $_GET['user_id'] ?? 0;
        $project_id = $_GET['project_id'] ?? 0;

        $sql = "INSERT INTO recommendations (user_id, project_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $project_id);
        $stmt->execute();
        echo json_encode(["message" => "Recommendation created successfully"]);
        break;

    case 'fetchRecommendations':
        $user_id = $_GET['user_id'] ?? 0;

        $sql = "SELECT 
                projects.project_id,
                projects.title,
                projects.description,
                projects.difficulty,
                projects.estimated_time,
                projects.prerequisites,
                projects.resources
            FROM 
                recommendations
            JOIN 
                projects ON recommendations.project_id = projects.project_id
            WHERE 
                recommendations.user_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $projects = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($projects);
        break;

    default:
        echo json_encode(['error' => 'Invalid operation']);
        break;
}
