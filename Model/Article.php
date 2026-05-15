<?php
require_once __DIR__ . '/../Config/Database.php';

class Article {

    public $conn;

    public function __construct(){
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // CREATE
    public function create($title, $body, $category_id, $image_path, $status, $publish_at){
        $stmt = $this->conn->prepare(
            "INSERT INTO articles (title, body, category_id, featured_image_path, status, publish_at, created_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW())"
        );
        $stmt->bind_param("ssisss", $title, $body, $category_id, $image_path, $status, $publish_at);
        $stmt->execute();
        return $this->conn->insert_id;
    }

    // READ ALL (ADMIN STYLE)
    public function getAll(){
        return $this->conn->query("SELECT * FROM articles ORDER BY created_at DESC");
    }

    // GET PUBLISHED (USER VIEW - ENHANCED)
    public function getPublished($category_id = null) {
        if ($category_id) {
            $sql = "SELECT a.*, u.name AS author_name, u.profile_pic_path,
                    c.name AS category_name,
                    (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
                    FROM articles a
                    LEFT JOIN users u ON a.author_id = u.id
                    LEFT JOIN categories c ON a.category_id = c.id
                    WHERE a.status = 'published' AND a.category_id = ?";
            $statement = $this->conn->prepare($sql);
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
            $statement = $this->conn->prepare($sql);
        }

        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // SINGLE ARTICLE
    public function getById($id){
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

    // UPDATE
    public function update($id, $title, $body, $category_id, $image_path, $status, $publish_at){
        if($image_path){
            $stmt = $this->conn->prepare(
                "UPDATE articles 
                 SET title=?, body=?, category_id=?, featured_image_path=?, status=?, publish_at=? 
                 WHERE id=?"
            );
            $stmt->bind_param("ssisssi", $title, $body, $category_id, $image_path, $status, $publish_at, $id);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE articles 
                 SET title=?, body=?, category_id=?, status=?, publish_at=? 
                 WHERE id=?"
            );
            $stmt->bind_param("sisssi", $title, $body, $category_id, $status, $publish_at, $id);
        }
        $stmt->execute();
    }

    // DELETE
    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // INSERT TAG
    public function insertTag($article_id, $tag_name){
        $stmt = $this->conn->prepare("INSERT IGNORE INTO tags (name) VALUES (?)");
        $stmt->bind_param("s", $tag_name);
        $stmt->execute();

        $stmt2 = $this->conn->prepare("SELECT id FROM tags WHERE name = ?");
        $stmt2->bind_param("s", $tag_name);
        $stmt2->execute();
        $tag = $stmt2->get_result()->fetch_assoc();

        $stmt3 = $this->conn->prepare(
            "INSERT IGNORE INTO article_tags (article_id, tag_id) VALUES (?, ?)"
        );
        $stmt3->bind_param("ii", $article_id, $tag['id']);
        $stmt3->execute();
    }

    // DELETE TAGS BY ARTICLE
    public function deleteTagsByArticle($article_id){
        $stmt = $this->conn->prepare("DELETE FROM article_tags WHERE article_id = ?");
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
    }

    // GET TAGS
    public function getTags($article_id){
        $sql = "SELECT t.name FROM tags t
                JOIN article_tags art ON t.id = art.tag_id
                WHERE art.article_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $tags = [];
        while ($row = $result->fetch_assoc()) {
            $tags[] = $row['name'];
        }
        return $tags;
    }

    // INCREMENT VIEW
    public function incrementView($id){
        $stmt = $this->conn->prepare(
            "UPDATE articles SET view_count = view_count + 1 WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // SEARCH
    public function search($q){
        $like = '%' . $q . '%';
        $sql = "SELECT DISTINCT a.id, a.title, a.created_at, u.name AS author_name
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN article_tags art ON a.id = art.article_id
                LEFT JOIN tags t ON art.tag_id = t.id
                WHERE a.status = 'published'
                AND (a.title LIKE ? OR t.name LIKE ?)
                LIMIT 8";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // CATEGORIES
    public function getCategories(){
        $stmt = $this->conn->prepare("SELECT * FROM categories");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // PUBLISH SCHEDULED
    public function publishScheduled(){
        $stmt = $this->conn->prepare(
            "UPDATE articles SET status = 'published'
             WHERE status = 'draft'
             AND publish_at IS NOT NULL
             AND publish_at <= NOW()"
        );
        $stmt->execute();
    }
}
?>