<h1>Blog Post</h1>

<?php
    // $user comes from the controller (can be null for guests)
    $userId = $user['id'] ?? null;
?>

<h2><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h2>
<p><?= nl2br(htmlspecialchars($post['body'], ENT_QUOTES, 'UTF-8')) ?></p>
<p><?= nl2br(htmlspecialchars("By ".$author_name, ENT_QUOTES, 'UTF-8')) ?></p>


<hr>

<h3>Comments</h3>

<?php if (empty($comments)): ?>
    <p style="color:#666;">No comments yet. Be the first to comment!</p>
<?php else: ?>
    <?php foreach ($comments as $comment): ?>
    <div style="border:1px solid #ddd; padding:8px; margin-bottom:8px;">
            <strong><?= htmlspecialchars($comment['author'], ENT_QUOTES, 'UTF-8') ?></strong>
            <small style="color:#666;">
                <?= htmlspecialchars($comment['created_at'], ENT_QUOTES, 'UTF-8') ?>
            </small>
            <p>
                <?= nl2br(htmlspecialchars($comment['body'], ENT_QUOTES, 'UTF-8')) ?>
            </p>
    
    
        <?php if ($userId !== null && $userId === $comment['author_id']): ?> 
        <div>
            <form
                action="/posts/<?=htmlspecialchars($post['id'], ENT_QUOTES, 'UTF-8') ?>/comments/delete/<?= htmlspecialchars($comment['id'], ENT_QUOTES, 'UTF-8') ?>"
                method="post"
                style="display:inline"
                onsubmit="return confirm('Are you sure you want to delete this comment?');"
            >
                <button type="submit">Delete Comment</button>
            </form>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
<?php endif; ?>


<?php if( $user !== null): ?>

<hr>

<h3>Add a comment</h3>

<form action="/posts/<?= htmlspecialchars($post['id'], ENT_QUOTES, 'UTF-8') ?>/comments" method="post">
   
    <p>
        <label for="body">Comment:</label><br>
        <textarea id="body" name="body" rows="4" required></textarea>
    </p>
    <button type="submit">Add Comment</button>
</form>

<?php endif; ?>



<?php if ($userId !== null && $userId === $post['author_id']): ?>

<hr>

<form
    action="/posts/delete/<?= htmlspecialchars($post['id'], ENT_QUOTES, 'UTF-8') ?>"
    method="post"
    style="display:inline"
    onsubmit="return confirm('Are you sure you want to delete this post?');"
>
    <button type="submit">Delete Post</button>
</form>
<?php endif; ?>

<?php if ($userId !== null && $userId === $post['author_id']): ?>
    <p><a href="/posts/edit/<?= htmlspecialchars($post['id'], ENT_QUOTES, 'UTF-8') ?>">Edit this post</a></p>
<?php endif; ?>

<p><a href="/posts">Back to the main posts page</a></p>
