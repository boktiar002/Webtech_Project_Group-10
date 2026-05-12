<?php

class Article {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getArticleAuthor($comment_id) {

        $query = "SELECT articles.author_id
                  FROM comments
                  JOIN articles
                  ON comments.article_id = articles.id
                  WHERE comments.id = :comment_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':comment_id', $comment_id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['author_id'];
    }
}

?>