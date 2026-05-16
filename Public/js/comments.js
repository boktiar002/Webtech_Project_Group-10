// public/comments.js

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
        // FIX: Path align করা হয়েছে সরাসরি Api ফোল্ডারের সাথে
        const response = await fetch('/Webtech_Project_Group-10/Api/comment_create.php', {
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
            if (bodyField) bodyField.value = '';
            window.location.reload(); // কমেন্ট পড়ার সাথে সাথে পেজ রিফ্রেশ করে কমেন্ট দেখাবে
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
    const userId = window.currentUserId;

    if (!commentId || !userId) {
        alert('Please log in before reporting a comment.');
        return;
    }

    try {
        // FIX: Path সঠিক করে Api/report_create.php বা প্রজেক্টের রুট এপিআই ফোল্ডারে পাঠানো হয়েছে
        const response = await fetch('/Webtech_Project_Group-10/Api/report_create.php', {
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
        alert("Failed to submit report.");
    }
}