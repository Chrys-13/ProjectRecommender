<?php
header('Content-Type: application/json');
require '../settings/config.php';

$operation = isset($_GET['operation']) ? $_GET['operation'] : '';

switch ($operation) {
    case 'fetchSingle':
        $project_id = isset($_GET['project_id']) ? $_GET['project_id'] : null;
        if ($project_id) {
            $sql = "SELECT
        projects.project_id, 
        projects.title,
        projects.description,
        projects.difficulty,
        projects.estimated_time,
        projects.prerequisites,
        projects.resources,
        GROUP_CONCAT(tags.tag_name) AS tag_names
    FROM 
        projects
    JOIN 
        project_tags ON projects.project_id = project_tags.project_id 
    JOIN 
        tags ON project_tags.tag_id = tags.tag_id 
    WHERE 
        projects.project_id = ?
    GROUP BY
        projects.project_id, 
        projects.title, 
        projects.description,
        projects.difficulty,
        projects.estimated_time,
        projects.prerequisites,
        projects.resources";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $project_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $project = $result->fetch_assoc();
            echo json_encode($project);
        } else {
            echo json_encode(["error" => "Project ID is required for fetching a single project."]);
        }
        break;
    case 'fetchAll':
        $sql = "SELECT
        projects.project_id, 
        projects.title,
        projects.description,
        projects.difficulty,
        projects.estimated_time,
        projects.prerequisites,
        projects.resources,
        GROUP_CONCAT(tags.tag_name) AS tag_names
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
        projects.resources";
        $result = $conn->query($sql);
        $projects = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($projects);
        break;
    case 'create':
        $title = $_GET['title'] ?? '';
        $description = $_GET['description'] ?? '';
        $difficulty = $_GET['difficulty'] ?? '';
        $estimated_time = $_GET['estimated_time'] ?? 0;
        $prerequisites = $_GET['prerequisites'] ?? '';
        $resources = $_GET['resources'] ?? '';
        $metadataJson = $_GET['metadata'] ?? ''; // Metadata received as a JSON string

        $conn->begin_transaction();
        try {
            $sql = "INSERT INTO projects (title, description, difficulty, estimated_time, prerequisites, resources) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssiss", $title, $description, $difficulty, $estimated_time, $prerequisites, $resources);
            $stmt->execute();
            $project_id = $conn->insert_id;

            if (!empty($metadataJson)) {
                $metadata = json_decode($metadataJson, true);
                if (is_array($metadata)) {
                    $sql_meta = "INSERT INTO project_metadata (project_id, `key`, `value`) VALUES (?, ?, ?)";
                    $stmt_meta = $conn->prepare($sql_meta);
                    foreach ($metadata as $meta) {
                        $stmt_meta->bind_param("iss", $project_id, $meta['key'], $meta['value']);
                        $stmt_meta->execute();
                    }
                }
            }

            $tagIdsJson = $_GET['tag_ids'] ?? ''; // Tag IDs received as a JSON string
            if (!empty($tagIdsJson)) {
                $tagIds = json_decode($tagIdsJson, true);
                if (is_array($tagIds)) {
                    $sql_tag = "INSERT INTO project_tags (project_id, tag_id) VALUES (?, ?)";
                    $stmt_tag = $conn->prepare($sql_tag);
                    foreach ($tagIds as $tagId) {
                        $stmt_tag->bind_param("ii", $project_id, $tagId);
                        $stmt_tag->execute();
                    }
                }
            }
            $conn->commit();

            echo json_encode(["success" => true, "message" => "Project and metadata created successfully"]);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(["success" => false, "message" => "Failed to create project", "error" => $e->getMessage()]);
        }
        break;
    case 'registerUser':
        $user_id = $_GET['user_id'] ?? 0;
        $project_id = $_GET['project_id'] ?? 0;
        $conn->begin_transaction();
        try {
            $sql = "INSERT INTO user_projects (user_id, project_id, status) VALUES (?, ?, 'in-progress')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $project_id);
            $stmt->execute();
            $conn->commit();
            echo json_encode(["success" => true, "message" => "User registered to project successfully"]);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(["success" => false, "message" => "Failed to register user to project", "error" => $e->getMessage()]);
        }
        break;
    case 'update':
        $project_id = $_GET['project_id'] ?? 0;
        $title = $_GET['title'] ?? null;
        $description = $_GET['description'] ?? null;
        $difficulty = $_GET['difficulty'] ?? null;
        $estimated_time = $_GET['estimated_time'] ?? null;
        $prerequisites = $_GET['prerequisites'] ?? null;
        $resources = $_GET['resources'] ?? null;
        $metadataJson = $_GET['metadata'] ?? ''; // Metadata received as a JSON string

        $conn->begin_transaction();
        try {
            $sql = "UPDATE projects SET title = ?, description = ?, difficulty = ?, estimated_time = ?, prerequisites = ?, resources = ? WHERE project_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssissi", $title, $description, $difficulty, $estimated_time, $prerequisites, $resources, $project_id);
            $stmt->execute();

            if (!empty($metadataJson)) {
                $metadata = json_decode($metadataJson, true);
                if (is_array($metadata)) {
                    // Assuming we're replacing all metadata for simplicity
                    $sql_delete_meta = "DELETE FROM project_metadata WHERE project_id = ?";
                    $stmt_delete_meta = $conn->prepare($sql_delete_meta);
                    $stmt_delete_meta->bind_param("i", $project_id);
                    $stmt_delete_meta->execute();

                    $sql_meta = "INSERT INTO project_metadata (project_id, `key`, `value`) VALUES (?, ?, ?)";
                    $stmt_meta = $conn->prepare($sql_meta);
                    foreach ($metadata as $meta) {
                        $stmt_meta->bind_param("iss", $project_id, $meta['key'], $meta['value']);
                        $stmt_meta->execute();
                    }
                }
            }

            $conn->commit();
            echo json_encode(["success" => true, "message" => "Project and metadata updated successfully"]);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(["success" => false, "message" => "Failed to update project", "error" => $e->getMessage()]);
        }
        break;
    case 'completeProject':
        $user_id = $_GET['user_id'] ?? 0;
        $project_id = $_GET['project_id'] ?? 0;
        $feedback = $_GET['feedback'] ?? null;

        $conn->begin_transaction();
        try {
            // Find the user_project_id based on user_id and project_id
            $sql = "SELECT user_project_id FROM user_projects WHERE user_id = ? AND project_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $project_id);
            $stmt->execute();
            $stmt->bind_result($user_project_id);
            $stmt->fetch();
            $stmt->close();

            if ($user_project_id) {
                // Proceed with the update
                $sql = "UPDATE user_projects SET status = 'completed', completed_at = NOW(), feedback = ? WHERE user_project_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $feedback, $user_project_id);
                $stmt->execute();
                $conn->commit();
                echo json_encode(["message" => "Project marked as completed successfully", "success" => true]);
            } else {
                // If no matching user_project_id is found
                echo json_encode(["message" => "No matching project found for the given user and project IDs", "success" => false]);
            }
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(["message" => "Failed to mark project as completed", "error" => $e->getMessage(), "success" => false]);
        }
        break;
    default:
        echo json_encode(["error" => "Invalid operation specified."]);
}
