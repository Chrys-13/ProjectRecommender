<?php
require '../settings/config.php';

// Check if the operation parameter exists
if (isset($_GET['operation'])) {
    $operation = $_GET['operation'];

    if ($operation === 'fetchAll') {
        // Fetch all metrics
        $sql = "SELECT * FROM usage_metrics";
        $result = $conn->query($sql);
        $metrics = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($metrics);
    } elseif ($operation === 'recordMetric') {
        // Record or update a metric
        // Ensure required parameters are present
        if (isset($_GET['user_id']) && isset($_GET['metric_key']) && isset($_GET['metric_value'])) {
            $user_id = $_GET['user_id'];
            $metric_key = $_GET['metric_key'];
            $metric_value = $_GET['metric_value'];

            // Check if the metric already exists
            $checkSql = "SELECT metric_id FROM usage_metrics WHERE user_id = ? AND metric_key = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("is", $user_id, $metric_key);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                // Metric exists, update it
                $row = $result->fetch_assoc();
                $updateSql = "UPDATE usage_metrics SET metric_value = ?, recorded_at = NOW() WHERE metric_id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("si", $metric_value, $row['metric_id']);
                $updateStmt->execute();
                echo json_encode(["message" => "Metric updated successfully"]);
            } else {
                // Metric does not exist, insert it
                $insertSql = "INSERT INTO usage_metrics (user_id, metric_key, metric_value) VALUES (?, ?, ?)";
                $insertStmt = $conn->prepare($insertSql);
                $insertStmt->bind_param("iss", $user_id, $metric_key, $metric_value);
                $insertStmt->execute();
                echo json_encode(["message" => "Metric recorded successfully"]);
            }
        } else {
            echo json_encode(["error" => "Missing required parameters"]);
        }
    } else {
        echo json_encode(["error" => "Invalid operation"]);
    }
} else {
    echo json_encode(["error" => "No operation specified"]);
}
?>