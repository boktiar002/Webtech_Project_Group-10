<?php
require_once __DIR__ . "/../Model/Tag.php";

class TagController {
    public $tag;

    public function __construct(){
        $this->tag = new Tag();
    }

    public function index(){
        return $this->tag->getAll();
    }

    public function store($name){
        $this->tag->create($name);
        header("Location: http://localhost/Webtech_Project_Group-10/View/category/index.php");
        exit();
    }

    public function delete($id){
        return $this->tag->delete($id);
    }
}
?>