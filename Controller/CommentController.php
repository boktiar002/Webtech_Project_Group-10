<?php
require_once __DIR__ . '/../Model/Comment.php';

class CommentController {

    private $comment;

    public function __construct($db) {
        $this->comment = new Comment($db);
    }
    public function createComment($article_id, $user_id, $body) {
        if (strlen(trim($body)) < 5) {
            return [
                'success' => false,
                'message' => 'Comment must be at least 5 characters long.'
            ];
        }

        $inserted = $this->comment->insertComment($article_id, $user_id, $body);

        if ($inserted) {
            $newComment = $this->comment->getLastComment();
            return [
                'success' => true,
                'message' => 'Comment posted successfully!',
                'comment' => $newComment
            ];
        }

        return [
            'success' => false,
            'message' => 'Internal Server Error: Could not save comment.'
        ];
    }
    public function getArticleComments($article_id) {
        try {
            $stmt = $this->comment->getCommentsByArticle($article_id);
            $comments = $stmt->fetch_all(MYSQLI_ASSOC);
            
            return [
                'success' => true,
                'data' => $comments
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
