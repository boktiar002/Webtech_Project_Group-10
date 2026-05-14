<div class="comment-item" id="comment-<?php echo $comment['id']; ?>" style="border-bottom: 1px solid #eee; padding: 15px; margin-bottom: 10px;">
    <div class="comment-header" style="display: flex; justify-content: space-between;">
        <strong><?php echo htmlspecialchars($comment['name']); ?></strong>
        <small class="text-muted"><?php echo date('M d, Y H:i', strtotime($comment['created_at'])); ?></small>
    </div>
    
    <div class="comment-body" style="margin: 10px 0;">
        <?php echo nl2br(htmlspecialchars($comment['body'])); ?>
    </div>

    <div class="comment-actions">
        <button 
            onclick="openReportModal(<?php echo $comment['id']; ?>)" 
            style="background: none; border: none; color: #d9534f; cursor: pointer; padding: 0; font-size: 0.85em;">
            Report
        </button>
    </div>
</div>