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

        // FIX: insertComment-এর বদলে মডেলের সঠিক মেثড 'createComment' কল করা হয়েছে
        $inserted = $this->comment->createComment($article_id, $user_id, $body);

        if ($inserted) {
            // FIX: getLastComment() মেথডটি মডেল ফাইলে মিসিং থাকায় এবং পেজ অটো-রিফ্রেশ হওয়ায় 
            // সরাসরি সাকসেস রেসপন্স রিটার্ন করা হলো।
            return [
                'success' => true,
                'message' => 'Comment posted successfully!'
            ];
        }

        return [
            'success' => false,
            'message' => 'Internal Server Error: Could not save comment.'
        ];
    }

    public function getArticleComments($article_id) {
        try {
            // মডেল থেকে MySQLi result set নেওয়া হচ্ছে
            $stmt = $this->comment->getCommentsByArticle($article_id);
            
            // কুয়েরি ডেটা অ্যাসোসিয়েটিভ অ্যারেতে কনভার্ট করা হচ্ছে
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
?>