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
        
        return $result; // MySQLi result set রিটার্ন করছে কন্ট্রোলারের জন্য
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

    // NEW FIX: ইউজারদের রিপোর্ট ডাটাবেজে সেভ করার মেথড (Student-4 Task)
    // এটি যুক্ত না থাকায় এতক্ষণ "Failed to submit report" আসছিল
    public function reportComment($commentId, $userId, $reason) {
        // তোমার ডাটাবেজের 'comment_reports' টেবিলে ডেটা ইনসার্ট করার কুয়েরি
        $query = "INSERT INTO comment_reports (comment_id, user_id, reason, created_at) VALUES (?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iis", $commentId, $userId, $reason);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>