<div class="admin-dashboard" style="padding: 20px;">
    <h2>Moderation Dashboard</h2>
    <p>Review reported comments below.</p>

    <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead style="background: #f8f9fa;">
            <tr>
                <th style="padding: 10px;">Article Title</th>
                <th style="padding: 10px;">Reported Comment</th>
                <th style="padding: 10px;">Reason</th>
                <th style="padding: 10px;">Reporter</th>
                <th style="padding: 10px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($reports) > 0): ?>
                <?php foreach ($reports as $r): ?>
                    <tr id="report-row-<?php echo $r['id']; ?>">
                        <td style="padding: 10px;"><?php echo htmlspecialchars($r['title']); ?></td>
                        <td style="padding: 10px;">
                            <blockquote style="margin: 0; font-style: italic; color: #555;">
                                "<?php echo htmlspecialchars($r['comment_body']); ?>"
                            </blockquote>
                        </td>
                        <td style="padding: 10px;"><span style="background: #fff3cd; padding: 2px 5px;"><?php echo htmlspecialchars($r['reason']); ?></span></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($r['reporter_name']); ?></td>
                        <td style="padding: 10px;">
                            <button onclick="deleteReportedComment(<?php echo $r['comment_id']; ?>, <?php echo $r['id']; ?>)" style="color: white; background: #d9534f; border: none; padding: 5px; cursor: pointer; margin-bottom: 5px; width: 100%;">Delete Comment</button>
                            <button onclick="dismissReport(<?php echo $r['id']; ?>)" style="color: black; background: #eee; border: 1px solid #ccc; padding: 5px; cursor: pointer; width: 100%;">Dismiss</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Queue is clear! No reports found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>