<?php
header('Content-Type: application/json');
require '../settings/config.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'add_tag') {
    // Add new tag
    $tag_name = isset($_GET['tag_name']) ? $_GET['tag_name'] : '';
    $sql = "INSERT INTO tags (tag_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tag_name);
    $stmt->execute();
    echo json_encode(["success" => true, "tag_id" => $stmt->insert_id]);
} elseif ($action === 'add_synonym') {
    // Add new synonym
    $tag_id = isset($_GET['tag_id']) ? $_GET['tag_id'] : 0;
    $synonym = isset($_GET['synonym']) ? $_GET['synonym'] : '';
    $sql = "INSERT INTO tag_synonyms (tag_id, synonym) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $tag_id, $synonym);
    $stmt->execute();
    echo json_encode(["synonym_id" => $stmt->insert_id]);
} elseif ($action === 'fetch_all') {
    // Fetch all tags and synonyms
    $sql = "SELECT t.tag_id, t.tag_name, s.synonym FROM tags t LEFT JOIN tag_synonyms s ON t.tag_id = s.tag_id";
    $result = $conn->query($sql);
    $tags = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($tags);
} elseif ($action === 'fetch_tag') {
    // Fetch specific tag by ID
    $tag_id = isset($_GET['tag_id']) ? $_GET['tag_id'] : 0;
    $sql = "SELECT tag_name FROM tags WHERE tag_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tag_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tag = $result->fetch_assoc();
    echo json_encode($tag);
} elseif ($action === 'fetch_synonym') {
    // Fetch specific synonym by tag ID
    $tag_id = isset($_GET['tag_id']) ? $_GET['tag_id'] : 0;
    $sql = "SELECT synonym FROM tag_synonyms WHERE tag_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tag_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $synonyms = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($synonyms);
}
