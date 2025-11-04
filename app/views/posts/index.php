
<h1> Blog Posts </h1>

<ul> 
    <?php foreach($posts as $post): ?>
        <li> 
            <h2><?=htmlspecialchars($post['title'])?></h2>

        </li>
    <?php endforeach; ?>
</ul>
