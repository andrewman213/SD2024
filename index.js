function likePost(postId) {
    fetch('like_post.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ post_id: postId })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Update de UI om de nieuwe like-status weer te geven
            const likeCountElement = document.querySelector(`.like-count[data-post-id="${postId}"]`);
            likeCountElement.textContent = parseInt(likeCountElement.textContent) + (data.liked ? 1 : -1);
        } else {
            // Toon een foutmelding aan de gebruiker
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Voeg event listeners toe aan like-knoppen
document.addEventListener('DOMContentLoaded', () => {
    const likeButtons = document.querySelectorAll('.like-button');
    likeButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const postId = button.getAttribute('data-post-id');
            likePost(postId);
        });
    });
});
