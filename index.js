let commentsVisible = {}; 

function openCommentsModal(postId) {
    const commentsList = document.getElementById(`comments-list-${postId}`);
    
    if (!commentsList) {
        console.error(`Element voor reacties van post ID ${postId} bestaat niet.`);
        return;
    }

    commentsVisible[postId] = !commentsVisible[postId];
    commentsList.style.display = commentsVisible[postId] ? 'block' : 'none';

    if (commentsVisible[postId]) {
        fetch('get_comments.php', {
            method: 'POST',
            body: JSON.stringify({ post_id: postId }),
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(comments => {
            commentsList.innerHTML = ''; 
            comments.forEach(comment => {
                const commentDiv = document.createElement('div');
                commentDiv.className = 'comment';
                commentDiv.textContent = comment.comment; 

                commentsList.appendChild(commentDiv);
            });
        })
        .catch(error => console.error('Fout bij ophalen van reacties:', error));
    }
}


function submitComment(postId, commentText, commentButton) {
    const formData = new FormData();
    formData.append('post_id', postId);
    formData.append('comment', commentText);

    fetch('add_comment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Voor debuggen
        if (data.success) {
            window.location.reload();
        } else {
            alert('Failed to post comment: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.comments .comment-form form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const postId = this.querySelector('input[name="post_id"]').value;
            const commentText = this.querySelector('textarea[name="comment"]').value;
            submitComment(postId, commentText, this);
        });
    });
});