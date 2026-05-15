<?php
class Article {

    public $conn;

    public function __construct(){
        $this->conn = new mysqli("localhost", "root", "", "blog_news_project");
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

    // READ ALL
    public function getAll(){
        return $this->conn->query("SELECT * FROM articles ORDER BY created_at DESC");
    }

    // SINGLE
    public function getById($id){
        $stmt = $this->conn->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // UPDATE
    public function update($id, $title, $body, $category_id, $image_path, $status, $publish_at){
        if($image_path){
            $stmt = $this->conn->prepare(
                "UPDATE articles SET title=?, body=?, category_id=?, featured_image_path=?, status=?, publish_at=? WHERE id=?"
            );
            $stmt->bind_param("ssisssi", $title, $body, $category_id, $image_path, $status, $publish_at, $id);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE articles SET title=?, body=?, category_id=?, status=?, publish_at=? WHERE id=?"
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

        $stmt3 = $this->conn->prepare("INSERT IGNORE INTO article_tags (article_id, tag_id) VALUES (?, ?)");
        $stmt3->bind_param("ii", $article_id, $tag['id']);
        $stmt3->execute();
    }

    // DELETE TAGS BY ARTICLE
    public function deleteTagsByArticle($article_id){
        $stmt = $this->conn->prepare("DELETE FROM article_tags WHERE article_id = ?");
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
    }

    // PUBLISH SCHEDULED
    public function publishScheduled(){
        $this->conn->query(
            "UPDATE articles SET status='published'
             WHERE status='draft' AND publish_at IS NOT NULL AND publish_at <= NOW()"
        );
    }
}
?>