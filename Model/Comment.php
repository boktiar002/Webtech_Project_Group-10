<?php
class Comment {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($article_id, $user_id, $body) {
        return $this->insertComment($article_id, $user_id, $body);
    }

    public function insertComment($article_id, $user_id, $body) {
        $stmt = $this->db->prepare("INSERT INTO comments (article_id, user_id, body) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $article_id, $user_id, $body);
        return $stmt->execute();
    }

    public function getLastComment() {
        $result = $this->db->query("SELECT c.*, u.name
            FROM comments c
            JOIN users u ON c.user_id = u.id
            ORDER BY c.id DESC
            LIMIT 1");
        return $result->fetch_assoc();
    }

    public function report($comment_id, $user_id, $reason) {
        $stmt = $this->db->prepare("INSERT INTO reported_comments (comment_id, reported_by, reason) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $comment_id, $user_id, $reason);
        return $stmt->execute();
    }

    public function delete($id) {
        return $this->deleteComment($id);
    }

    public function deleteComment($id) {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getByArticle($article_id) {
        return $this->getCommentsByArticle($article_id)->fetch_all(MYSQLI_ASSOC);
    }

    public function getCommentsByArticle($article_id) {
        $stmt = $this->db->prepare("SELECT c.*, u.name
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.article_id = ?
            ORDER BY c.created_at DESC");
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
