<?php
require_once __DIR__ . "/../Model/Article.php";

class ArticleController {

    public $article;

    public function __construct() {
        $this->article = new Article();
    }

    // CREATE
    public function store() {
        $errors = [];

        $title       = trim($_POST["title"] ?? "");
        $body        = trim($_POST["body"] ?? "");
        $category_id = !empty($_POST["category_id"]) ? $_POST["category_id"] : null;
        $status      = $_POST["status"] ?? "draft";
        $publish_at  = !empty($_POST["publish_at"]) ? $_POST["publish_at"] : null;
        $tags        = $_POST["tags"] ?? "";

        // Validate required fields
        if(empty($title)) $errors[] = "Title is required";
        if(empty($body))  $errors[] = "Body is required";

        // Handle image upload
        $image_path = null;
        if(!empty($_FILES["featured_image"]["name"])) {
            $file    = $_FILES["featured_image"];
            $allowed = ["image/jpeg", "image/png"];
            $maxSize = 3 * 1024 * 1024;

            if(!in_array($file["type"], $allowed)) {
                $errors[] = "Only JPEG/PNG allowed";
            } elseif($file["size"] > $maxSize) {
                $errors[] = "Image must be under 3MB";
            } else {
                $uploadDir = __DIR__ . "/../public/uploads/articles/";
                if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $filename  = time() . "_" . basename($file["name"]);
                move_uploaded_file($file["tmp_name"], $uploadDir . $filename);
                $image_path = "public/uploads/articles/" . $filename;
            }
        }

        // Save article and tags
        if(empty($errors)) {
            $article_id = $this->article->create($title, $body, $category_id, $image_path, $status, $publish_at);

            if(!empty($tags)) {
                foreach(explode(",", $tags) as $tag) {
                    $tag = strtolower(trim($tag));
                    if(!empty($tag)) $this->article->insertTag($article_id, $tag);
                }
            }

            header("Location: /Webtech_Project_Group-10/index.php?page=dashboard");
            exit();
        }
    }

    // DELETE
    public function delete($id) {
        $this->article->delete($id);
        header("Location: /Webtech_Project_Group-10/index.php?page=dashboard");
        exit();
    }

    // GET ALL ARTICLES
    public function index() {
        $this->article->publishScheduled();
        return $this->article->getAll();
    }

    // GET SINGLE ARTICLE
    public function getById($id) {
        return $this->article->getAnyById($id);
    }

    // UPDATE
    public function update($id) {
        $errors = [];

        $title       = trim($_POST["title"] ?? "");
        $body        = trim($_POST["body"] ?? "");
        $category_id = !empty($_POST["category_id"]) ? $_POST["category_id"] : null;
        $status      = $_POST["status"] ?? "draft";
        $publish_at  = !empty($_POST["publish_at"]) ? $_POST["publish_at"] : null;
        $tags        = $_POST["tags"] ?? "";

        // Validate required fields
        if(empty($title)) $errors[] = "Title is required";
        if(empty($body))  $errors[] = "Body is required";

        // Handle image upload
        $image_path = null;
        if(!empty($_FILES["featured_image"]["name"])) {
            $file    = $_FILES["featured_image"];
            $allowed = ["image/jpeg", "image/png"];
            $maxSize = 3 * 1024 * 1024;

            if(!in_array($file["type"], $allowed)) {
                $errors[] = "Only JPEG/PNG allowed";
            } elseif($file["size"] > $maxSize) {
                $errors[] = "Image must be under 3MB";
            } else {
                $uploadDir = __DIR__ . "/../public/uploads/articles/";
                if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $filename  = time() . "_" . basename($file["name"]);
                move_uploaded_file($file["tmp_name"], $uploadDir . $filename);
                $image_path = "public/uploads/articles/" . $filename;
            }
        }

        // Update article and tags
        if(empty($errors)) {
            $this->article->update($id, $title, $body, $category_id, $image_path, $status, $publish_at);

            // Delete old tags and insert new ones
            $this->article->deleteTagsByArticle($id);
            if(!empty($tags)) {
                foreach(explode(",", $tags) as $tag) {
                    $tag = strtolower(trim($tag));
                    if(!empty($tag)) $this->article->insertTag($id, $tag);
                }
            }

            header("Location: /Webtech_Project_Group-10/index.php?page=dashboard");
            exit();
        }
    }
}
?>