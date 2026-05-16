<?php
// Api/report/create.php
header('Content-Type: application/json');
session_start();

// সাব-ফোল্ডারে থাকার কারণে ২ বার ডিরেক্টরি ব্যাক করা হয়েছে
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../Model/Comment.php';

// জাভাস্ক্রিপ্ট থেকে পাঠানো JSON বডি রিড করা হচ্ছে
$inputRaw = file_get_contents('php://input');
$data = json_decode($inputRaw, true);

// ১. ইউজার লগইন আছে কিনা চেক
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please log in to report this comment.'
    ]);
    exit;
}

// ২. জাভাস্ক্রিপ্ট থেকে পাঠানো ডেটা ঠিকঠাক এসেছে কিনা চেক করা
// (এখানেই মূলত অমিল থাকার কারণে 'Incomplete data' আসছিল)
if (!isset($data['comment_id']) || !isset($data['reason'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Incomplete data sent to server.'
    ]);
    exit;
}

$db = (new Database())->getConnection();
$commentModel = new Comment($db);

$commentId = (int)$data['comment_id'];
$userId = (int)$_SESSION['user_id']; // সেশন থেকে সিকিউরড উপায়ে নেওয়া হলো
$reason = trim($data['reason']);

if (empty($reason)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please select a valid reason.'
    ]);
    exit;
}

// মডেলে ডেটা পাঠানো হচ্ছে সেভ করার জন্য
$result = $commentModel->reportComment($commentId, $userId, $reason);

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! The comment has been flagged for moderation.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Database Error: Failed to save the report.'
    ]);
}
exit;