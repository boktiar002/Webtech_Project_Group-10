<?php
require_once __DIR__ . "/../Config/Database.php";

class Category {
    public $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        return $this->conn->query(
            "SELECT categories.*, COUNT(articles.id) as article_count 
             FROM categories 
             LEFT JOIN articles ON articles.category_id = categories.id 
             GROUP BY categories.id"
        );
    }

    public function create($name) {
        $stmt = $this->conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    }

    public function delete($id) {
        // Block deletion if articles reference this category
        $check = $this->conn->prepare("SELECT id FROM articles WHERE category_id = ? LIMIT 1");
        $check->bind_param("i", $id);
        $check->execute();
        $check->store_result();
        if($check->num_rows > 0) return false;

        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return true;
    }
}
?>