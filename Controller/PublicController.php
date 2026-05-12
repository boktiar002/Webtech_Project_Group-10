<?php
require_once __DIR__ . '/../Model/Article.php';
require_once __DIR__ . '/../Model/Like.php';

class PublicController {

    private $articleModel;
    private $likeModel;

    public function __construct() {
        // Model classes are created once and reused for each request.
        $this->articleModel = new Article();
        $this->likeModel = new Like();
    }

    // Render the homepage with categories and published articles.
    public function home() {
        $this->articleModel->publishScheduled();
        $categories = $this->articleModel->getCategories();
        $articles = $this->articleModel->getPublished();
        include __DIR__ . '/../View/Layouts/header.php';
        include __DIR__ . '/../View/Public/home.php';
        include __DIR__ . '/../View/Layouts/footer.php';
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
        include __DIR__ . '/../View/Layouts/header.php';
        include __DIR__ . '/../View/Public/article.php';
        include __DIR__ . '/../View/Layouts/footer.php';
    }
}