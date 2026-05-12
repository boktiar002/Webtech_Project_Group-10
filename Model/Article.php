
<?php
require_once __DIR__ . '/Database.php';

class Article {

    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // get latest published articles
    public function getPublished($category_id = null) {
        if ($category_id) {
            $stmt = $this->pdo->prepare("
                SELECT a.*, u.name AS author_name, u.profile_pic_path, c.name AS category_name,
                (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE a.status = 'published' AND a.category_id = ?
                ORDER BY a.created_at DESC
            ");
            $stmt->execute([$category_id]);
        } else {
            $stmt = $this->pdo->prepare("
                SELECT a.*, u.name AS author_name, u.profile_pic_path, c.name AS category_name,
                (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE a.status = 'published'
                ORDER BY a.created_at DESC
            ");
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // get single article by id
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT a.*, u.name AS author_name, u.profile_pic_path, u.bio AS author_bio,
            c.name AS category_name,
            (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
            FROM articles a
            LEFT JOIN users u ON a.author_id = u.id
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.id = ? AND a.status = 'published'
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // get tags for an article
    public function getTags($article_id) {
        $stmt = $this->pdo->prepare("
            SELECT t.name FROM tags t
            JOIN article_tags at ON t.id = at.tag_id
            WHERE at.article_id = ?
        ");
        $stmt->execute([$article_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // increment view count
    public function incrementView($id) {
        $stmt = $this->pdo->prepare("
            UPDATE articles SET view_count = view_count + 1 WHERE id = ?
        ");
        $stmt->execute([$id]);
    }

    // search articles by title or tag
    public function search($q) {
        $like = '%' . $q . '%';
        $stmt = $this->pdo->prepare("
            SELECT DISTINCT a.id, a.title, a.featured_image_path, a.created_at,
            u.name AS author_name
            FROM articles a
            LEFT JOIN users u ON a.author_id = u.id
            LEFT JOIN article_tags art ON a.id = at.article_id
            LEFT JOIN tags t ON at.tag_id = t.id
            WHERE a.status = 'published'
            AND (a.title LIKE ? OR t.name LIKE ?)
            LIMIT 8
        ");
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // get all categories
    public function getCategories() {
        $stmt = $this->pdo->prepare("SELECT * FROM categories");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // publish scheduled articles
    public function publishScheduled() {
        $stmt = $this->pdo->prepare("
            UPDATE articles SET status = 'published'
            WHERE status = 'draft'
            AND publish_at IS NOT NULL
            AND publish_at <= NOW()
        ");
        $stmt->execute();
    }
}

