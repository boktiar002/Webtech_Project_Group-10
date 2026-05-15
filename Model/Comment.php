<?php
class Comment {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function create($article_id, $user_id, $body) {
        $stmt = $this->db->prepare("INSERT INTO comments (article_id, user_id, body) VALUES (?, ?, ?)");
        return $stmt->execute([$article_id, $user_id, $body]);
    }

    public function report($comment_id, $user_id, $reason) {
        $stmt = $this->db->prepare("INSERT INTO reported_comments (comment_id, reported_by, reason) VALUES (?, ?, ?)");
        return $stmt->execute([$comment_id, $user_id, $reason]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getByArticle($article_id) {
        $stmt = $this->db->prepare("SELECT c.*, u.name FROM comments c JOIN users u ON c.user_id = u.id WHERE c.article_id = ? ORDER BY c.created_at DESC");
        $stmt->execute([$article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}