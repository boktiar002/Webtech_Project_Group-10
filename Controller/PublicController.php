<?php
session_start();
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../models/Like.php';

class PublicController {

    private $articleModel;
    private $likeModel;

    public function __construct() {
        $this->articleModel = new Article();
        $this->likeModel = new Like();
    }

    // homepage
    public function home() {
        $this->articleModel->publishScheduled();
        $categories = $this->articleModel->getCategories();
        $articles = $this->articleModel->getPublished();
        include __DIR__ . '/../views/layouts/header.php';
        include __DIR__ . '/../views/public/home.php';
        include __DIR__ . '/../views/layouts/footer.php';
    }

    // article reading page
    public function article($id) {
        $article = $this->articleModel->getById($id);
        if (!$article) {
            echo "Article not found.";
            return;
        }
        $this->articleModel->incrementView($id);
        $tags = $this->articleModel->getTags($id);
        include __DIR__ . '/../views/layouts/header.php';
        include __DIR__ . '/../views/public/article.php';
        include __DIR__ . '/../views/layouts/footer.php';
    }
}