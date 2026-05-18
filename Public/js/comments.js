async function submitComment(articleId) {
    const bodyField = document.getElementById('comment-text');
    const messageDiv = document.getElementById('comment-message');
    const body = bodyField ? bodyField.value : '';

    if (!articleId) {
        if (messageDiv) {
            messageDiv.innerHTML = '<span style="color:#dc2626;">Invalid Article Session.</span>';
        }
        return;
    }

    if (!body.trim()) {
        if (messageDiv) {
            messageDiv.innerHTML = '<span style="color:#b45309;">Please enter a comment.</span>';
        }
        return;
    }

    try {
        const response = await fetch('/Webtech_Project_Group-10/Api/comment_create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                article_id: articleId,
                body: body
            })
        });

        const result = await response.json();

        if (result.success) {
            if (bodyField) bodyField.value = '';
            window.location.reload();
        } else if (messageDiv) {
            messageDiv.innerHTML = `<span style="color:#dc2626;">${result.message}</span>`;
        }
    } catch (error) {
        console.error("Comment Error:", error);
        if (messageDiv) {
            messageDiv.innerHTML = '<span style="color:#dc2626;">Error submitting comment. Check console.</span>';
        }
    }
}

function openReportModal(commentId) {
    const modal = document.getElementById('report-modal');
    const inputField = document.getElementById('report-comment-id');
    if (inputField) inputField.value = commentId;
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeReportModal() {
    const modal = document.getElementById('report-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}

async function sendReport() {
    const commentId = document.getElementById('report-comment-id').value;
    const reason = document.getElementById('report-reason').value;

    if (!commentId) {
        alert('Invalid Comment Target.');
        return;
    }

    if (!reason) {
        alert('Please select a reason.');
        return;
    }

    try {
        const response = await fetch('/Webtech_Project_Group-10/Api/report/create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                comment_id: commentId,
                reason: reason
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        alert(result.message);

        if (result.success) {
            closeReportModal();
        }
    } catch (error) {
        console.error("Report Error:", error);
        alert("Failed to submit report. Please check the network log.");
    }
}