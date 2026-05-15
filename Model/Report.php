<?php
class Report {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function alreadyReported($comment_id, $reported_by) {
        $stmt = $this->conn->prepare("SELECT id FROM reported_comments WHERE comment_id = ? AND reported_by = ?");
        $stmt->bind_param("ii", $comment_id, $reported_by);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function createReport($comment_id, $reported_by, $reason) {
        $stmt = $this->conn->prepare(
            "INSERT INTO reported_comments (comment_id, reported_by, reason, created_at)
             VALUES (?, ?, ?, NOW())"
        );
        $stmt->bind_param("iis", $comment_id, $reported_by, $reason);
        return $stmt->execute();
    }

    public function getAllReports() {
        return $this->conn->query("SELECT
                reported_comments.id,
                reported_comments.reason,
                comments.body AS comment_body,
                comments.id AS comment_id,
                articles.title,
                users.name AS reporter_name
            FROM reported_comments
            JOIN comments ON reported_comments.comment_id = comments.id
            JOIN articles ON comments.article_id = articles.id
            JOIN users ON reported_comments.reported_by = users.id
            ORDER BY reported_comments.id DESC");
    }

    public function clearReport($id) {
        $stmt = $this->conn->prepare("DELETE FROM reported_comments WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function deleteCommentReports($comment_id) {
        $stmt = $this->conn->prepare("DELETE FROM reported_comments WHERE comment_id = ?");
        $stmt->bind_param("i", $comment_id);
        return $stmt->execute();
    }
}
?>
