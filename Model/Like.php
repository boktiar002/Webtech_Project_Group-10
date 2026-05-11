<?php
require_once __DIR__ . '/Database.php';

class Like {

    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // check if user already liked
    public function hasLiked($article_id, $user_id) {
        $stmt = $this->pdo->prepare("
            SELECT id FROM likes WHERE article_id = ? AND user_id = ?
        ");
        $stmt->execute([$article_id, $user_id]);
        return $stmt->fetch() ? true : false;
    }

    // add like
    public function like($article_id, $user_id) {
        $stmt = $this->pdo->prepare("
            INSERT INTO likes (article_id, user_id) VALUES (?, ?)
        ");
        $stmt->execute([$article_id, $user_id]);
    }

    // remove like
    public function unlike($article_id, $user_id) {
        $stmt = $this->pdo->prepare("
            DELETE FROM likes WHERE article_id = ? AND user_id = ?
        ");
        $stmt->execute([$article_id, $user_id]);
    }

    // get like count
    public function getCount($article_id) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM likes WHERE article_id = ?
        ");
        $stmt->execute([$article_id]);
        return $stmt->fetchColumn();
    }
}