<?php
require_once __DIR__ . '/../Config/Database.php';

class Like {
    private $connection;

    function __construct() {
        $db = new Database();
        $this->connection = $db->getConnection();
    }

    // check if user already liked
    function hasLiked($article_id, $user_id) {
        $sql = "SELECT id FROM likes WHERE article_id = ? AND user_id = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("ii", $article_id, $user_id);
        $statement->execute();
        $result = $statement->get_result();
        return $result->num_rows > 0;
    }

    // add like
    function like($article_id, $user_id) {
        $sql = "INSERT INTO likes (article_id, user_id) VALUES (?, ?)";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("ii", $article_id, $user_id);
        $statement->execute();
    }

    // remove like
    function unlike($article_id, $user_id) {
        $sql = "DELETE FROM likes WHERE article_id = ? AND user_id = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("ii", $article_id, $user_id);
        $statement->execute();
    }

    // get like count
    function getCount($article_id) {
        $sql = "SELECT COUNT(*) AS total FROM likes WHERE article_id = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("i", $article_id);
        $statement->execute();
        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
?>