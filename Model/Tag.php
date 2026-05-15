<?php
class Tag {
    public $conn;

    public function __construct(){
        $this->conn = new mysqli("localhost", "root", "", "blog_news_project");
    }

    public function getAll(){
        return $this->conn->query("SELECT * FROM tags");
    }

    public function create($name){
        $name = strtolower(trim($name));
        $stmt = $this->conn->prepare("INSERT IGNORE INTO tags (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    }

    public function delete($id){
        $check = $this->conn->prepare("SELECT article_id FROM article_tags WHERE tag_id = ? LIMIT 1");
        $check->bind_param("i", $id);
        $check->execute();
        $check->store_result();
        if($check->num_rows > 0) return false;

        $stmt = $this->conn->prepare("DELETE FROM tags WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return true;
    }
}
?>