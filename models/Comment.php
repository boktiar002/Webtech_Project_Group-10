<?php

class Comment {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insertComment($article_id, $user_id, $body) {

        $query = "INSERT INTO comments(article_id, user_id, body, created_at)
                  VALUES(:article_id, :user_id, :body, NOW())";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':article_id', $article_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':body', $body);

        return $stmt->execute();
    }

    public function getCommentsByArticle($article_id) {

        $query = "SELECT comments.*, users.name
                  FROM comments
                  JOIN users ON comments.user_id = users.id
                  WHERE article_id = :article_id
                  ORDER BY comments.id DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':article_id', $article_id);

        $stmt->execute();

        return $stmt;
    }

    public function getLastComment() {

        $query = "SELECT comments.*, users.name
                  FROM comments
                  JOIN users ON comments.user_id = users.id
                  ORDER BY comments.id DESC
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteComment($id) {

        $query = "DELETE FROM comments WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function getCommentOwner($comment_id) {

        $query = "SELECT user_id FROM comments WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $comment_id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['user_id'];
    }
}

?>