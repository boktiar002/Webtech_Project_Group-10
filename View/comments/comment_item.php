<?php
if (isset($comment) && is_array($comment)):
?>
    <div class="comment-item-card" id="comment-row-<?php echo $comment['id']; ?>" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); transition: transform 0.15s ease;">
        
        <div class="comment-item-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
            <div class="comment-user-info" style="font-size: 0.95rem;">
                <strong style="color: #0f172a;"><?php echo htmlspecialchars($comment['user_name'] ?? 'Anonymous User'); ?></strong>
                <span style="color: #94a3b8; margin-left: 8px; font-size: 0.85rem;">
                    <?php echo date('M d, Y • h:i A', strtotime($comment['created_at'])); ?>
                </span>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <button class="comment-report-trigger" onclick="openReportModal(<?php echo $comment['id']; ?>)" style="background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; border-radius: 999px; padding: 4px 10px; font-size: 0.82rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: background 0.2s;" onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fef2f2'">
                    ⚠️ Report
                </button>
            <?php endif; ?>
        </div>

        <div class="comment-item-body" style="color: #334155; font-size: 0.95rem; line-height: 1.6; white-space: pre-line; word-break: break-word;">
            <?php echo htmlspecialchars($comment['body']); ?>
        </div>
        
    </div>
<?php 
endif; 
?>