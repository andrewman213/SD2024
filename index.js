let commentsVisible = false; // To keep track of comments visibility

function likePost(postId) {
    fetch('like_post.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ post_id: postId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeCountElement = document.querySelector(`.like-count[data-post-id="${postId}"]`);
            likeCountElement.textContent = parseInt(likeCountElement.textContent) + (data.liked ? 1 : -1);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function openCommentsModal(postId) {
    const commentsList = document.getElementById(`comments-${postId}`);
    const addCommentForm = document.getElementById(`add-comment-${postId}`);

    if (!commentsVisible) {
        fetch('get_comments.php', {
            method: 'POST',
            body: JSON.stringify({ post_id: postId }),
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(comments => {
            commentsList.innerHTML = ''; // Clear existing comments
            comments.forEach(comment => {
                const commentContainer = document.createElement('div');
                commentContainer.className = 'comment-container';

                const userElement = document.createElement('div');
                userElement.className = 'comment-user';
                userElement.textContent = comment.user_id;

                const textElement = document.createElement('div');
                textElement.className = 'comment-text';
                textElement.textContent = comment.comment_text;

                const dateElement = document.createElement('div');
                dateElement.className = 'comment-date';
                dateElement.textContent = comment.created_at;

                commentContainer.appendChild(userElement);
                commentContainer.appendChild(textElement);
                commentContainer.appendChild(dateElement);

                commentsList.appendChild(commentContainer);
            });

            commentsVisible = true; // Comments are now visible
            // Show the add comment form
            addCommentForm.style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
        });
    } else {
        commentsList.innerHTML = ''; // Clear comments when toggling off
        commentsVisible = false; // Comments are now hidden
        // Hide the add comment form
        addCommentForm.style.display = 'none';
    }
}

function closeCommentsModal() {
    // No modal to close, just clear comments if needed
    commentsVisible = false; // Comments are now hidden
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
        console.log(data); // For debugging
        if (data.success) {
            // Clear the textarea
            document.querySelector(`textarea[data-post-id="${postId}"]`).value = '';
            commentButton.textContent = `Read Comments (${data.newCommentCount})`;

            // Update the comments list dynamically
            const commentsList = document.getElementById(`comments-${postId}`);
            if (commentsList && commentsVisible) {
                const commentContainer = document.createElement('div');
                commentContainer.className = 'comment-container';
                commentContainer.innerHTML = `
                    <div class="comment-user">User</div>
                    <div class="comment-text">${commentText}</div>
                    <div class="comment-date">Just now</div>
                `;
                commentsList.appendChild(commentContainer);
            } else {
                console.error('Could not find the comments list element or comments are hidden.');
            }
        } else {
            alert('Failed to post comment: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const likeButtons = document.querySelectorAll('.like-button');
    likeButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const postId = button.getAttribute('data-post-id');
            likePost(postId);
        });
    });

    document.querySelectorAll('button[data-post-id]').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            openCommentsModal(postId);
        });
    });

    document.querySelectorAll('.comments form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const postId = this.querySelector('input[name="post_id"]').value;
            const commentText = this.querySelector('textarea[name="comment"]').value;
            const commentButton = this.querySelector(`button[data-post-id="${postId}"]`);
            submitComment(postId, commentText, commentButton);
        });
    });
});
