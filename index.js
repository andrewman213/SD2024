function likePost(postId) {
    fetch('like_post.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ post_id: postId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the UI to display the new like status
            const likeCountElement = document.querySelector(`.like-count[data-post-id="${postId}"]`);
            likeCountElement.textContent = parseInt(likeCountElement.textContent) + (data.liked ? 1 : -1);
        } else {
            // Display an error message to the user
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function openCommentsModal(postId) {
    fetch('get_comments.php', {
        method: 'POST',
        body: JSON.stringify({ post_id: postId }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(comments => {
        const commentsList = document.getElementById('commentsList');
        commentsList.innerHTML = ''; // Clear existing comments
        comments.forEach(comment => {
            const commentElement = document.createElement('div');
            commentElement.className = 'comment-item';
            // Populate with actual comment details using template literals
            commentElement.innerHTML = `
                <div class="comment-user">${comment.user_id}</div>
                <div class="comment-text">${comment.comment_text}</div>
                <div class="comment-date">${comment.created_at}</div>
            `;
            commentsList.appendChild(commentElement);
        });
        document.getElementById('commentsModal').style.display = 'block';
    })
    .catch(error => {
        console.error('Error:', error);
    });
}


function closeCommentsModal() {
    document.getElementById('commentsModal').style.display = 'none';
}

// New function to handle comment submission
function submitComment(postId, commentText, commentButton) {
    fetch('add_comment.php', {
        method: 'POST',
        body: JSON.stringify({
            post_id: postId,
            comment: commentText
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear the comment input field
            document.querySelector(`textarea[data-post-id="${postId}"]`).value = '';
            // Update the comment count on the "Read Comments" button
            commentButton.textContent = `Read Comments (${data.newCommentCount})`;
        } else {
            alert('Failed to post comment: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // Add event listeners to like buttons
    const likeButtons = document.querySelectorAll('.like-button');
    likeButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const postId = button.getAttribute('data-post-id');
            likePost(postId);
        });
    });

    // Attach event listeners to 'Read Comments' buttons
    document.querySelectorAll('button[data-post-id]').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            openCommentsModal(postId);
        });
    });

    // Attach submit event to comment forms
    document.querySelectorAll('.comments form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const postId = this.querySelector('input[name="post_id"]').value;
            const commentText = this.querySelector('textarea[name="comment"]').value;
            const commentButton = document.querySelector(`button[data-post-id="${postId}"]`);
            submitComment(postId, commentText, commentButton);
        });
    });
});
