<?php
require_once __DIR__ . "/../Model/Catagory.php";

class CategoryController {
    public $category;

    public function __construct() {
        $this->category = new Category();
    }

    // Get all categories
    public function index() {
        return $this->category->getAll();
    }

    // Create new category
    public function store($name) {
        $name = trim($name);
        if(empty($name)) return false;
        $this->category->create($name);
        header("Location: /Webtech_Project_Group-10/index.php?page=categories");
        exit();
    }

    // Delete category
    public function delete($id) {
        return $this->category->delete($id);
    }
}
?>