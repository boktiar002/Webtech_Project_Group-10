<?php
require_once __DIR__ . '/../Config/Database.php';

class Article {
    public $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function create($title, $body, $category_id, $image_path, $status, $publish_at) {
        $author_id = $_SESSION['user_id'] ?? 1;
        $stmt = $this->conn->prepare(
            "INSERT INTO articles (author_id, title, body, category_id, featured_image_path, status, publish_at, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"
        );
        $stmt->bind_param("ississs", $author_id, $title, $body, $category_id, $image_path, $status, $publish_at);
        $stmt->execute();
        return $this->conn->insert_id;
    }

    public function getAll() {
    return $this->conn->query(
        "SELECT articles.*, COUNT(comments.id) as comment_count 
         FROM articles 
         LEFT JOIN comments ON comments.article_id = articles.id 
         GROUP BY articles.id 
         ORDER BY articles.created_at DESC"
    );
}

    public function getPublished($category_id = null) {
        $sql = "SELECT a.*, u.name AS author_name, u.profile_pic_path,
                c.name AS category_name,
                (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE a.status = 'published'";

        if ($category_id) {
            $sql .= " AND a.category_id = ?";
            $stmt = $this->conn->prepare($sql . " ORDER BY a.created_at DESC");
            $stmt->bind_param("i", $category_id);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        return $this->conn->query($sql . " ORDER BY a.created_at DESC")->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT a.*, u.name AS author_name, u.profile_pic_path,
                c.name AS category_name,
                (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE a.id = ? AND a.status = 'published'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getAnyById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function update($id, $title, $body, $category_id, $image_path, $status, $publish_at) {
        if ($image_path) {
            $stmt = $this->conn->prepare(
                "UPDATE articles SET title=?, body=?, category_id=?, featured_image_path=?, status=?, publish_at=? WHERE id=?"
            );
            $stmt->bind_param("ssisssi", $title, $body, $category_id, $image_path, $status, $publish_at, $id);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE articles SET title=?, body=?, category_id=?, status=?, publish_at=? WHERE id=?"
            );
            $stmt->bind_param("ssissi", $title, $body, $category_id, $status, $publish_at, $id);
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function insertTag($article_id, $tag_name) {
        $stmt = $this->conn->prepare("INSERT IGNORE INTO tags (name) VALUES (?)");
        $stmt->bind_param("s", $tag_name);
        $stmt->execute();

        $stmt2 = $this->conn->prepare("SELECT id FROM tags WHERE name = ?");
        $stmt2->bind_param("s", $tag_name);
        $stmt2->execute();
        $tag = $stmt2->get_result()->fetch_assoc();

        if (!$tag) {
            return false;
        }

        $stmt3 = $this->conn->prepare("INSERT IGNORE INTO article_tags (article_id, tag_id) VALUES (?, ?)");
        $stmt3->bind_param("ii", $article_id, $tag['id']);
        return $stmt3->execute();
    }

    public function deleteTagsByArticle($article_id) {
        $stmt = $this->conn->prepare("DELETE FROM article_tags WHERE article_id = ?");
        $stmt->bind_param("i", $article_id);
        return $stmt->execute();
    }

    public function getTags($article_id) {
        $stmt = $this->conn->prepare(
            "SELECT t.name FROM tags t
             JOIN article_tags art ON t.id = art.tag_id
             WHERE art.article_id = ?"
        );
        $stmt->bind_param("i", $article_id);
        $stmt->execute();

        $tags = [];
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $tags[] = $row['name'];
        }
        return $tags;
    }

    public function incrementView($id) {
        $stmt = $this->conn->prepare("UPDATE articles SET view_count = view_count + 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function search($q) {
        $like = '%' . $q . '%';
        $stmt = $this->conn->prepare(
            "SELECT DISTINCT a.id, a.title, a.created_at, u.name AS author_name
             FROM articles a
             LEFT JOIN users u ON a.author_id = u.id
             LEFT JOIN article_tags art ON a.id = art.article_id
             LEFT JOIN tags t ON art.tag_id = t.id
             WHERE a.status = 'published'
             AND (a.title LIKE ? OR t.name LIKE ?)
             LIMIT 8"
        );
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategories() {
        return $this->conn->query("SELECT * FROM categories ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
    }

    public function publishScheduled() {
        return $this->conn->query("UPDATE articles SET status = 'published'
             WHERE status = 'draft'
             AND publish_at IS NOT NULL
             AND publish_at <= NOW()");
    }
}
?>
