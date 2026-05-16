async function submitComment(articleId) {
    const bodyField = document.getElementById('comment-text');
    const messageDiv = document.getElementById('comment-message');
    const body = bodyField ? bodyField.value : '';
    const userId = window.currentUserId;

    if (!articleId || !userId) {
        if (messageDiv) {
            messageDiv.innerHTML = '<span style="color:#b45309;">Please log in before commenting.</span>';
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
        const response = await fetch('/Webtech_Project_Group-10/Api/comment/create.php', {
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
            window.location.reload();
        } else if (messageDiv) {
            messageDiv.innerHTML = `<span style="color:#dc2626;">${result.message}</span>`;
        }
    } catch (error) {
        console.error('Fetch Error:', error);
        if (messageDiv) {
            messageDiv.innerHTML = '<span style="color:#dc2626;">Network error. Check console for details.</span>';
        }
    }
}

function openReportModal(commentId) {
    const modal = document.getElementById('report-modal');
    document.getElementById('report-comment-id').value = commentId;
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
    const userId = window.currentUserId;

    if (!commentId || !userId) {
        alert('Please log in before reporting a comment.');
        return;
    }

    try {
        const response = await fetch('/Webtech_Project_Group-10/Api/report/create.php', {
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
        console.error('Report Error:', error);
        alert('Network error while reporting.');
    }
}
