<?php
require_once __DIR__ . '/../Model/Report.php';

class ReportController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createReport($comment_id, $user_id, $reason) {
        $reportModel = new Report($this->db);
        if ($reportModel->alreadyReported($comment_id, $user_id)) {
            return ['success' => false, 'message' => 'You have already reported this comment.'];
        }
        if ($reportModel->createReport($comment_id, $user_id, $reason)) {
            return ['success' => true, 'message' => 'Report submitted successfully.'];
        }
        
        return ['success' => false, 'message' => 'Failed to submit report.'];
    }
}