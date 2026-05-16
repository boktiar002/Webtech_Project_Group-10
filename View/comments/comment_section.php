<style>
    .comment-section {
        max-width: 100%;
    }

    .comment-title {
        margin: 0 0 18px;
        font-size: 1.5rem;
        color: #111827;
    }

    .comment-form-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px;
        margin-bottom: 24px;
    }

    .comment-textarea {
        width: 100%;
        min-height: 110px;
        padding: 14px;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        resize: vertical;
        font: inherit;
        box-sizing: border-box;
    }

    .comment-submit {
        margin-top: 12px;
        padding: 11px 18px;
        border: none;
        border-radius: 999px;
        background: #2563eb;
        color: #fff;
        font-weight: 700;
        cursor: pointer;
    }

    .comment-login-note {
        margin: 0 0 20px;
        padding: 14px 16px;
        background: #fff7ed;
        color: #9a3412;
        border: 1px solid #fdba74;
        border-radius: 14px;
    }

    .comment-message {
        margin-top: 10px;
        min-height: 20px;
    }

    .comment-list {
        display: grid;
        gap: 14px;
    }

    .report-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .report-modal-card {
        width: min(100%, 420px);
        background: #fff;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 20px 60px rgba(15, 23, 42, 0.25);
    }

    .report-actions {
        display: flex;
        gap: 10px;
        margin-top: 14px;
    }

    .report-actions button,
    .report-modal select {
        font: inherit;
    }

    .report-modal select {
        width: 100%;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        margin-top: 10px;
    }

    .report-submit,
    .report-cancel {
        border: none;
        border-radius: 999px;
        padding: 10px 14px;
        cursor: pointer;
    }

    .report-submit {
        background: #dc2626;
        color: #fff;
    }

    .report-cancel {
        background: #e5e7eb;
        color: #111827;
    }
</style>

<section class="comment-section">
    <h3 class="comment-title">Discussion</h3>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="comment-form-card">
            <textarea id="comment-text" class="comment-textarea" placeholder="Write a comment..."></textarea>
            <button class="comment-submit" onclick="submitComment(<?php echo (int) $article_id; ?>)">Post Comment</button>
            <div id="comment-message" class="comment-message"></div>
        </div>
    <?php else: ?>
        <p class="comment-login-note">
            Log in to join the discussion and post a comment.
        </p>
    <?php endif; ?>

    <div id="comments-container" class="comment-list">
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <?php include __DIR__ . '/comment_item.php'; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p id="no-comments">No comments yet. Be the first to join the conversation.</p>
        <?php endif; ?>
    </div>
</section>

<div id="report-modal" class="report-modal">
    <div class="report-modal-card">
        <h4>Report Comment</h4>
        <input type="hidden" id="report-comment-id">
        <select id="report-reason">
            <option value="Spam">Spam</option>
            <option value="Inappropriate Content">Inappropriate Content</option>
            <option value="Harassment">Harassment</option>
            <option value="Other">Other</option>
        </select>
        <div class="report-actions">
            <button class="report-submit" onclick="sendReport()">Submit Report</button>
            <button class="report-cancel" onclick="closeReportModal()">Cancel</button>
        </div>
    </div>
</div>
