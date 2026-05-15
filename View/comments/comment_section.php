<section class="comment-section" style="max-width: 800px; margin: 20px auto;">
    <h3>Discussion</h3>

    <div class="comment-form" style="margin-bottom: 30px;">
        <textarea id="comment-text" placeholder="Write a comment..." style="width: 100%; height: 80px; padding: 10px; margin-bottom: 10px;"></textarea>
        <button onclick="submitComment(<?php echo $article_id; ?>)" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Post Comment
        </button>
        <div id="comment-message" style="margin-top: 10px;"></div>
    </div>


    
    <hr>

    <div id="comments-container">
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <?php include 'comment_item.php'; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p id="no-comments">No comments yet. Be the first to join the conversation!</p>
        <?php endif; ?>
    </div>
</section>

<div id="report-modal" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border: 1px solid #ccc; box-shadow: 0 4px 8px rgba(0,0,0,0.2); z-index: 1000;">
    <h4>Report Comment</h4>
    <input type="hidden" id="report-comment-id">
    <select id="report-reason" style="width: 100%; margin-bottom: 10px; padding: 5px;">
        <option value="Spam">Spam</option>
        <option value="Inappropriate Content">Inappropriate Content</option>
        <option value="Harassment">Harassment</option>
        <option value="Other">Other</option>
    </select>
    <button onclick="sendReport()" style="background: #d9534f; color: white; border: none; padding: 5px 10px;">Submit Report</button>
    <button onclick="closeReportModal()" style="background: #eee; border: none; padding: 5px 10px;">Cancel</button>
</div>