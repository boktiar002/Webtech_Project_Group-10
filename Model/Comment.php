<?php
class Comment {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // কমেন্ট ডাটাবেজে ইনসার্ট করার মেথড
    public function createComment($articleId, $userId, $body) {
        $stmt = $this->conn->prepare("INSERT INTO comments (article_id, user_id, body, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $articleId, $userId, $body);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // মডারেশন ড্যাশবোর্ডের জন্য কমেন্ট ডিলিট করার মেথড (Student-4 Task)
    public function deleteComment($commentId) {
        $stmt = $this->conn->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->bind_param("i", $commentId);
        return $stmt->execute();
    }
}
?>