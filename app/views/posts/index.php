
<h1> Blog Posts </h1>

<ul> 
    <?php foreach($posts as $post): ?>
        <li> 
            <a href='/posts/show/<?= htmlspecialchars($post['id'])?>'>
                <h2><?=htmlspecialchars($post['title'])?></h2>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<footer>
    <p><a href="/"> Go to Home</a></p>
    <p><a href="/posts/create"> Create a new Post</a></p>
</footer>