<?php

class Article {
    private $connection;

<<<<<<< HEAD
    function __construct() {
        $db = new db();
        $this->connection = $db->connection();
    }

    // get all published articles, optional category filter
    function getPublished($category_id = null) {
        if ($category_id) {
            $sql = "SELECT a.*, u.name AS author_name, u.profile_pic_path,
                    c.name AS category_name,
                    (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
                    FROM articles a
                    LEFT JOIN users u ON a.author_id = u.id
                    LEFT JOIN categories c ON a.category_id = c.id
                    WHERE a.status = 'published' AND a.category_id = ?";
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("i", $category_id);
        } else {
            $sql = "SELECT a.*, u.name AS author_name, u.profile_pic_path,
                    c.name AS category_name,
                    (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
                    FROM articles a
                    LEFT JOIN users u ON a.author_id = u.id
                    LEFT JOIN categories c ON a.category_id = c.id
                    WHERE a.status = 'published'
                    ORDER BY a.created_at DESC";
            $statement = $this->connection->prepare($sql);
        }
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // get single article by id
    function getById($id) {
        $sql = "SELECT a.*, u.name AS author_name, u.profile_pic_path,
                c.name AS category_name,
                (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE a.id = ? AND a.status = 'published'";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_assoc();
    }

    // get tags for an article
    function getTags($article_id) {
        $sql = "SELECT t.name FROM tags t
                JOIN article_tags art ON t.id = art.tag_id
                WHERE art.article_id = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("i", $article_id);
        $statement->execute();
        $result = $statement->get_result();
        $tags = [];
        while ($row = $result->fetch_assoc()) {
            $tags[] = $row['name'];
        }
        return $tags;
    }

    // increment view count
    function incrementView($id) {
        $sql = "UPDATE articles SET view_count = view_count + 1 WHERE id = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("i", $id);
        $statement->execute();
    }

    // search articles by title or tag
    function search($q) {
        $like = '%' . $q . '%';
        $sql = "SELECT DISTINCT a.id, a.title, a.created_at, u.name AS author_name
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN article_tags art ON a.id = art.article_id
                LEFT JOIN tags t ON art.tag_id = t.id
                WHERE a.status = 'published'
                AND (a.title LIKE ? OR t.name LIKE ?)
                LIMIT 8";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("ss", $like, $like);
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // get all categories
    function getCategories() {
        $sql = "SELECT * FROM categories";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // publish scheduled articles
    function publishScheduled() {
        $sql = "UPDATE articles SET status = 'published'
                WHERE status = 'draft'
                AND publish_at IS NOT NULL
                AND publish_at <= NOW()";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }
}
=======
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

>>>>>>> 43e5fcb99a6573a906057cc3798c99421378d774
?>