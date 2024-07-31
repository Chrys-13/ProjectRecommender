<?php
require '../settings/config.php';

header('Content-Type: application/json');

function getRequestData($key)
{
    $value = null;
    if (isset($_POST[$key])) {
        $value = $_POST[$key];
    } elseif (isset($_GET[$key])) {
        $value = $_GET[$key];
    } elseif (isset($_REQUEST[$key])) {
        $value = $_REQUEST[$key];
    }
    return $value;
}

$method = getRequestData('method');

switch ($method) {
    case 'getProfile':
        if (!($user_id = getRequestData('user_id'))) {
            echo json_encode(["message" => "User ID is required"]);
            exit;
        }
        $user_id = (int)$user_id;
        $sql = "SELECT * FROM user_profiles JOIN users ON user_profiles.user_id = users.user_id WHERE user_profiles.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $profile = $result->fetch_assoc();
        echo json_encode($profile);
        break;

    case 'updateProfile':
        $user_id = getRequestData('user_id') ? (int)getRequestData('user_id') : 0;
        $interests = getRequestData('interests') ?? '';
        $interests_json = json_encode(explode(', ', $interests));
        $skills = getRequestData('skills') ?? '';
        $goals = getRequestData('goals') ?? '';
        $current_skill_level = getRequestData('current_skill_level') ?? '';
        $sql = "UPDATE user_profiles SET interests = ?, skills = ?, goals = ?, current_skill_level = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $interests_json, $skills, $goals, $current_skill_level, $user_id);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Profile updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update profile"]);
        }
        break;

    default:
        echo json_encode(["message" => "Invalid method"]);
        break;
}
