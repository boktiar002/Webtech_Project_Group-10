<?php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../../Config/Database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

    if ($userId > 0) {
        $db = (new Database())->getConnection();
        $sql = "UPDATE users SET role = 'author', pending_author = 0 WHERE id = ? AND role = 'reader'";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $userId);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'User successfully promoted to Author!',
                'new_role' => 'author'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'User not found, already an author, or query failed.'
            ]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid User ID.']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
?>