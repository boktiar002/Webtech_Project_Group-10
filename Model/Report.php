<?php

class Report {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function alreadyReported($comment_id, $reported_by) {

        $query = "SELECT * FROM reported_comments
                  WHERE comment_id = :comment_id
                  AND reported_by = :reported_by";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':comment_id', $comment_id);
        $stmt->bindParam(':reported_by', $reported_by);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function createReport($comment_id, $reported_by, $reason) {

        $query = "INSERT INTO reported_comments
                  (comment_id, reported_by, reason, created_at)
                  VALUES(:comment_id, :reported_by, :reason, NOW())";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':comment_id', $comment_id);
        $stmt->bindParam(':reported_by', $reported_by);
        $stmt->bindParam(':reason', $reason);

        return $stmt->execute();
    }

    public function getAllReports() {

        $query = "SELECT
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
                  ORDER BY reported_comments.id DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function clearReport($id) {

        $query = "DELETE FROM reported_comments WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function deleteCommentReports($comment_id) {

        $query = "DELETE FROM reported_comments
                  WHERE comment_id = :comment_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':comment_id', $comment_id);

        return $stmt->execute();
    }
}

?>