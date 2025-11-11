<h1> Blog Post</h1>

            
<h2><?=htmlspecialchars($post['title'])?></h2>
<p><?=htmlspecialchars($post['body'])?></p>
<form 
    action="/posts/delete/<?= htmlspecialchars($post['id'], ENT_QUOTES, 'UTF-8') ?>" 
    method="post" 
    style="display:inline"
    onsubmit="return confirm('Are you sure you want to delete this post?');"
>
    <button type="submit">Delete Post</button>
</form>

<a href="/posts">Back to the main posts page</a>
<p><a href="/posts/edit/<?= htmlspecialchars($post['id'], ENT_QUOTES, 'UTF-8') ?>"  >Edit this post</a></p>
    
        

