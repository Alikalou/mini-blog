
<h1> Blog Posts </h1>


<ul> 
    <?php foreach($posts as $post): ?>
        <li> 
            <a href='/posts/show/<?= htmlspecialchars($post['id'])?>'>
                <h2><?=htmlspecialchars($post['title'])?></h2>
                <?php if( isset($post['author']) && $post['author'] !== null ): ?>
                <h2><?=htmlspecialchars("By ".$post['author'])?></h2>
                <?php endif; ?> 
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<footer>
    <p><a href="/"> Go to Home</a></p>
    <p><a href="/posts/create"> Create a new Post</a></p>
</footer>