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

    public function store(){
        $title=$_POST['title'] ?? '';
        $body=$_POST['body'] ?? '';

        $title=trim($title);
        $body=trim($body);

        if ($title === '' || $body === '') {
        http_response_code(422);
        echo "Title and body are required.";
        return;
        // Later you can re-render the form with old input + error messages instead of echoing.
    }
        //Here you would normally store the post in a database
        //But for now, we will just display a confirmation message.

        
        $this->post->saveToArray($title, $body);

        $this->post->saveToStorage();

        $this->viewPosts('index', [
            'title' => 'All Posts',
            'posts' => $this->post->all(),
        ]);

        header('Location: /posts');
        exit;
    }

    //So, we need two functions in the post
    //controller, since post controller will be responsible for the control
    //of two interfaces, the index and the show pages.

}