<?php

require_once __DIR__.'/../../core/controller.php';

require_once __DIR__.'/../models/Post.php';

class PostController extends Controller {

    private Post $post;

    public function __construct(?Post $post = null)
    {
        // If no Post is injected, create a default one
        $this->post = $post ?? new Post();
    }


      public function index(): void
    {
        $posts = $this->post->all();

        // Pass as ['posts' => ...] so extract() creates $posts
        $this->viewPosts('index', [
            'title' => 'All Posts',
            'posts' => $posts,
        ]);
    }
    
public function show($id): void
{
    $post = $this->post->findPost($id);

    if (!$post) {
        http_response_code(404);
        echo "There is no post with such ID";
        return;
    }

    $this->viewPosts('show', [
        'title' => $post['title'] ?? 'Post',
        'post'  => $post,
    ]);
}

    public function form(){

        $body='Fill up the body';
        $title='Enter a title please';

        $this->viewPosts('form', [
        'title'=>$title,
        'body'=>$body
        ]);

    }

    public function store($id = null)
    {
        $title = $_POST['title'] ?? '';
        $body  = $_POST['body'] ?? '';

        $title = trim($title);
        $body  = trim($body);

        if ($title === '' || $body === '') {
            http_response_code(422);
            echo "Title and body are required.";
            return;
            // Later you can re-render the form with old input + error messages instead of echoing.
        }

        // If $id is not null → we're updating an existing post
        if ($id !== null) {
            $updated = $this->post->update((int)$id, $title, $body);

            if (!$updated) {
                http_response_code(404);
                echo "There is no post with such ID to update";
                return;
            }
            $_SESSION['flash'] = 'Post was updated successfully.';
            header('Location: /posts');
            exit;
        }

        // Otherwise → this is a new post
        $this->post->saveToArray($title, $body);
        $this->post->saveToStorage();

        // PRG: DO NOT render index here, just redirect
        
        $_SESSION['flash'] = 'Post was created successfully.';
        header('Location: /posts');
        exit;
    }


    public function destroy($id): void
    {
    $deleted = $this->post->delete((int) $id);

    if ($deleted) {
        $_SESSION['flash'] = 'Post deleted successfully.';
        header('Location: /posts');
        exit;
    }

    http_response_code(404);
    echo "There is no post with such ID to delete";
    }

    public function editForm($id): void
{
    $post = $this->post->findPost((int) $id);

    if (!$post) {
        http_response_code(404);
        echo "Post not found";
        return;
    }

    $this->viewPosts('edit', [
        'post' => $post,
    ]);
}

}