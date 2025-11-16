<?php

require_once __DIR__.'/../../core/controller.php';

require_once __DIR__.'/../models/Post.php';
require_once __DIR__.'/../models/Comment.php';

require_once __DIR__.'/../../core/flash.php';

class PostController extends Controller {

    private Post $post;
    private Comment $comment;

    public function __construct(?Post $post = null)
    {
        // If no Post is injected, create a default one
        $this->post = $post ?? new Post();
        $this->comment = new Comment();
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
        Flash::setFlash('error', 'There is no post with such ID.');
        header('Location: /posts');
        exit;
    }

    $comments=$this->comment->forPost((int) $id);


    $this->viewPosts('show', [
        'title' => $post['title'] ?? 'Post',
        'post'  => $post,
        'comments' => $comments,
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
                Flash::setFlash('error', 'There is no post with such ID to update.');
                header('Location: /posts');
                exit;    
            }
            else{
            Flash::setFlash('success', 'Post updated successfully.');
            header('Location: /posts');
            exit;
            }
        }

        // Otherwise → this is a new post
        $this->post->saveToArray($title, $body);
        $this->post->saveToStorage();

        // PRG: DO NOT render index here, just redirect
        
        Flash::setFlash('success', 'Post created successfully.');
        header('Location: /posts');
        exit;
    }


    public function destroy($id): void
    {
    $deleted = $this->post->delete((int) $id);

    if ($deleted) {
        Flash::setFlash('success', 'Post deleted successfully.');

        header('Location: /posts');
        exit;
    }
    else{
        Flash::setFlash('error', 'There is no post with such ID to delete.');
        header('Location: /posts');
        exit;
    }

}


    public function editForm($id): void
{
    $post = $this->post->findPost((int) $id);

    if (!$post) {
    Flash::setFlash('error', 'There is no post with such ID to edit.');
    header('Location: /posts');
    exit;
    }

    $this->viewPosts('edit', [
        'post' => $post,
    ]);
}

}

