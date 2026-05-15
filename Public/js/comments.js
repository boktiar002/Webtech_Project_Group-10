async function submitComment(articleId) {
    const bodyField = document.getElementById('comment-text');
    const messageDiv = document.getElementById('comment-message');
    const body = bodyField.value;
    const userId = 1; 

    if (!articleId) {
        console.error("Missing Article ID");
        return;
    }

    if (!body.trim()) {
        messageDiv.innerHTML = '<span style="color: orange;">Please enter a comment.</span>';
        return;
    }

    try {
        const response = await fetch('api/comment/create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                article_id: articleId,
                user_id: userId,
                body: body
            })
        });

        const result = await response.json();

        if (result.success) {
            bodyField.value = '';
            location.reload(); 
        } else {
            messageDiv.innerHTML = `<span style="color: red;">${result.message}</span>`;
        }
    } catch (error) {
        console.error("Fetch Error:", error);
        messageDiv.innerHTML = '<span style="color: red;">Network error. Check console for details.</span>';
    }
}

function openReportModal(commentId) {
    document.getElementById('report-comment-id').value = commentId;
    document.getElementById('report-modal').style.display = 'block';
}

function closeReportModal() {
    document.getElementById('report-modal').style.display = 'none';
}

async function sendReport() {
    const commentId = document.getElementById('report-comment-id').value;
    const reason = document.getElementById('report-reason').value;
    const userId = 1;

    if (!commentId) {
        alert("Error: No comment selected to report.");
        return;
    }

    try {
const response = await fetch('api/reports/create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                comment_id: commentId,
                user_id: userId,
                reason: reason
            })
        });

        const result = await response.json();
        alert(result.message);
        
        if (result.success) {
            closeReportModal();
        }
    } catch (error) {
        console.error("Report Error:", error);
        alert("Network error while reporting.");
    }
}