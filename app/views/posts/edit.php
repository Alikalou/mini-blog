<?php /** @var array $post */ ?>

<h1>Edit Post</h1>

<form action="/posts/save/<?= htmlspecialchars($post['id'], ENT_QUOTES, 'UTF-8') ?>" method="post">
    <label for="title">Title:</label><br>
    <input
        type="text"
        id="title"
        name="title"
        value="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>"
        required
    ><br><br>

    <label for="body">Body:</label><br>
    <textarea
        id="body"
        name="body"
        rows="5"
        cols="40"
        required
    ><?= htmlspecialchars($post['body'], ENT_QUOTES, 'UTF-8') ?></textarea><br><br>

    <button type="submit">Save Changes</button>
</form>

<footer>
    <p><a href="/posts">Back to Posts</a></p>
    <p><a href="/">Go to Home</a></p>
</footer>
