let commentsVisible = {}; // We maken dit een object om de zichtbaarheid per post bij te houden

function openCommentsModal(postId) {
    const commentsContainer = document.getElementById(`comments-${postId}`);
    if (!commentsContainer) {
        console.error(`Comments container voor post ID ${postId} bestaat niet.`);
        return; // Stop de functie als het element niet gevonden wordt
    }

    commentsVisible[postId] = !commentsVisible[postId]; // Toggle de zichtbaarheid

    if (commentsVisible[postId]) {
        commentsContainer.style.display = 'block'; // Toon de comments container
        fetch('get_comments.php', {
            method: 'POST',
            body: JSON.stringify({ post_id: postId }),
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(comments => {
            commentsContainer.innerHTML = ''; // Clear existing comments
            comments.forEach(comment => {
                const commentContainer = document.createElement('div');
                commentContainer.className = 'comment-container';

                const textElement = document.createElement('div');
                textElement.className = 'comment-text';
                textElement.textContent = comment.comment; // Gebruikt de 'comment' eigenschap direct

                const dateElement = document.createElement('div');
                dateElement.className = 'comment-date';
                dateElement.textContent = comment.created_at;

                commentContainer.appendChild(textElement);
                commentContainer.appendChild(dateElement);

                commentsContainer.appendChild(commentContainer);
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
    } else {
        commentsContainer.style.display = 'none'; // Verberg de comments container
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
        console.log(data); // For debugging
        if (data.success) {
            // Herlaad de pagina om de nieuwe commentaartelling te zien
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
    document.querySelectorAll('.comments form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const postId = this.querySelector('input[name="post_id"]').value;
            const commentText = this.querySelector('textarea[name="comment"]').value;
            submitComment(postId, commentText, this);
        });
    });
});
