<?php
require_once __DIR__ . '/../Model/Report.php';
require_once __DIR__ . '/../Model/Comment.php';

class ModerationController {
    private $report;
    private $comment;

    public function __construct($db) {
        $this->report = new Report($db);
        $this->comment = new Comment($db);
    }

    public function listAllReports() {
        $result = $this->report->getAllReports();
        // Fixed: Fetching using mysqli instead of PDO
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteReportedComment($comment_id) {
        $this->report->deleteCommentReports($comment_id);
        
        if ($this->comment->deleteComment($comment_id)) {
            return ['success' => true, 'message' => 'Comment and related reports deleted successfully'];
        }

        return ['success' => false, 'message' => 'Failed to delete comment'];
    }

    public function dismissReport($report_id) {
        if ($this->report->clearReport($report_id)) {
            return ['success' => true, 'message' => 'Report dismissed successfully'];
        }

        return ['success' => false, 'message' => 'Failed to dismiss report'];
    }
}
?>