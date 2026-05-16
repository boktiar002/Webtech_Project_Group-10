<?php
require_once __DIR__ . '/../Config/Database.php';

class Like {
    private $connection;

    public function __construct() {
        $db = new Database();
        $this->connection = $db->getConnection();
    }

    public function hasLiked($article_id, $user_id) {
        $stmt = $this->connection->prepare("SELECT id FROM likes WHERE article_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $article_id, $user_id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function like($article_id, $user_id) {
        $stmt = $this->connection->prepare("INSERT INTO likes (article_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $article_id, $user_id);
        return $stmt->execute();
    }

    public function unlike($article_id, $user_id) {
        $stmt = $this->connection->prepare("DELETE FROM likes WHERE article_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $article_id, $user_id);
        return $stmt->execute();
    }

    public function getCount($article_id) {
        $stmt = $this->connection->prepare("SELECT COUNT(*) AS total FROM likes WHERE article_id = ?");
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return (int)($row['total'] ?? 0);
    }
}
?>
