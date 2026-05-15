<?php
require_once __DIR__ . "/../Model/Catagory.php";

class CategoryController {
    public $category;

    public function __construct(){
        $this->category = new Category();
    }

    public function index(){
        return $this->category->getAll();
    }

    public function store($name){
        $this->category->create($name);
        header("Location: http://localhost/Webtech_Project_Group-10/View/category/index.php");
        exit();
    }

    public function delete($id){
        return $this->category->delete($id);
    }
}
?>