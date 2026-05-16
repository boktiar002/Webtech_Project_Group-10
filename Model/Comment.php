<?php
class Comment {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCommentsByArticle($articleId) {
        $query = "SELECT comments.*, users.name AS user_name 
                  FROM comments 
                  JOIN users ON comments.user_id = users.id 
                  WHERE comments.article_id = ? 
                  ORDER BY comments.created_at DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $articleId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result;
    }

    public function createComment($articleId, $userId, $body) {
        $stmt = $this->conn->prepare("INSERT INTO comments (article_id, user_id, body, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $articleId, $userId, $body);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteComment($commentId) {
        $stmt = $this->conn->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->bind_param("i", $commentId);
        return $stmt->execute();
    }

    public function reportComment($commentId, $userId, $reason) {
        $query = "INSERT INTO comment_reports (comment_id, user_id, reason, created_at) VALUES (?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iis", $commentId, $userId, $reason);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getReportedComments() {
        $query = "SELECT 
                    r.id AS report_id,
                    r.reason,
                    r.created_at AS reported_at,
                    c.id AS comment_id,
                    c.body AS comment_body,
                    u.name AS commenter_name
                  FROM comment_reports r
                  JOIN comments c ON r.comment_id = c.id
                  JOIN users u ON c.user_id = u.id
                  ORDER BY r.created_at DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>