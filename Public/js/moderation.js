async function deleteReportedComment(commentId, reportId) {
    if (!confirm("Are you sure you want to delete this comment? This cannot be undone.")) {
        return;
    }

    try {
        const response = await fetch('/Webtech_Project_Group-10/Api/reports/delete_comment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ comment_id: commentId })
        });

        const text = await response.text(); 
        try {
            const result = JSON.parse(text);
            if (result.success) {
                const row = document.getElementById(`report-row-${reportId}`);
                if (row) row.remove();
                alert("Comment and associated reports deleted.");
            } else {
                alert("Error: " + result.message);
            }
        } catch (e) {
            console.error("Server Error Response:", text);
            alert("Server returned invalid response. Check console.");
        }
    } catch (error) {
        console.error("Moderation Error:", error);
    }
}

async function dismissReport(reportId) {
    if (!confirm("Are you sure you want to dismiss this report?")) {
        return;
    }

    try {
        const response = await fetch('/Webtech_Project_Group-10/Api/reports/clear.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ report_id: reportId })
        });

        const result = await response.json();
        if (result.success) {
            const row = document.getElementById(`report-row-${reportId}`);
            if (row) row.remove();
            alert("Report dismissed successfully.");
        } else {
            alert("Error dismissing report.");
        }
    } catch (error) {
        console.error("Moderation Error:", error);
    }
}