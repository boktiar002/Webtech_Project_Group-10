<?php
require_once __DIR__ . "/../Model/Tag.php";

class TagController {
    public $tag;

    public function __construct() {
        $this->tag = new Tag();
    }

    // Get all tags
    public function index() {
        return $this->tag->getAll();
    }

    // Create new tag
    public function store($name) {
        $name = trim($name);
        if(empty($name)) return false;
        $this->tag->create($name);
        header("Location: /Webtech_Project_Group-10/index.php?page=categories");
        exit();
    }

    // Delete Tag
    public function delete($id) {
        return $this->tag->delete($id);
    }
}
?>