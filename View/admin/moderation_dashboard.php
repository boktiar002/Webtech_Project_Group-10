<?php

require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../Model/Comment.php';

$db = (new Database())->getConnection();
$commentModel = new Comment($db);
$reportsResult = $commentModel->getReportedComments();
$reports = [];
if ($reportsResult) {
    while ($row = $reportsResult->fetch_assoc()) {
        $reports[] = $row;
    }
}
?>

<div class="admin-dashboard" style="padding: 20px; font-family: system-ui, sans-serif;">
    <h2>Moderation Dashboard</h2>
    <p>Review reported comments below.</p>

    <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px; border-color: #e2e8f0;">
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
                        <td style="padding: 10px;"><span style="background: #fff3cd; padding: 2px 5px; color: #856404; border-radius: 4px;"><?php echo htmlspecialchars($r['reason']); ?></span></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($r['reporter_name']); ?></td>
                        <td style="padding: 10px;">
                            <button onclick="deleteReportedComment(<?php echo $r['comment_id']; ?>, <?php echo $r['id']; ?>)" style="color: white; background: #d9534f; border: none; padding: 6px; cursor: pointer; margin-bottom: 5px; width: 100%; border-radius: 4px; font-weight: bold;">Delete Comment</button>
                            <button onclick="dismissReport(<?php echo $r['id']; ?>)" style="color: black; background: #eee; border: 1px solid #ccc; padding: 6px; cursor: pointer; width: 100%; border-radius: 4px;">Dismiss</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px; color: #666;">Queue is clear! No reports found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
async function deleteReportedComment(commentId, reportId) {
    if (!confirm('Are you sure you want to delete this comment permanently?')) {
        return;
    }

    try {
        const response = await fetch('/Webtech_Project_Group-10/Api/delete_comments.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ comment_id: commentId })
        });

        const result = await response.json();
        alert(result.message);

        if (result.success) {
            const row = document.getElementById(`report-row-${reportId}`);
            if (row) row.remove();
        }
    } catch (error) {
        console.error("Delete Error:", error);
        alert("Failed to delete comment.");
    }
}

async function dismissReport(reportId) {
    if (!confirm('Dismiss this report? (The comment will remain safe on the platform)')) {
        return;
    }

    try {
        const response = await fetch('/Webtech_Project_Group-10/Api/report/clear.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ report_id: reportId })
        });

        const result = await response.json();
        alert(result.message);

        if (result.success) {
            const row = document.getElementById(`report-row-${reportId}`);
            if (row) row.remove();
        }
    } catch (error) {
        console.error("Dismiss Error:", error);
        alert("Failed to dismiss report.");
    }
}
</script>