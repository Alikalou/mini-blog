<?php

require_once __DIR__.'/../../core/controller.php';

require_once __DIR__.'/../models/Post.php';
require_once __DIR__.'/../models/Comment.php';

require_once __DIR__.'/../../core/flash.php';
require_once __DIR__.'/../../core/auth.php';


class PostController extends Controller
{
    private Post $post;
    private Comment $comment;
    private User $user;

    public function __construct(?Post $post = null)
    {
        // Initialize the base Controller (sets up its own $user, etc.)
        parent::__construct();

        // If no Post is injected, create a default one
        $this->post    = $post ?? new Post();
        $this->comment = new Comment();
        $this->user    = new User();
    }

    /**
     * GET /posts
     */
    public function index(): void
    {
        $posts = $this->post->all();

        $this->viewPosts('index', [
            'title' => 'All Posts',
            'posts' => $posts,
        ]);
    }

    /**
     * GET /posts/create
     * Used by: $router->get('/posts/create', [$postController, 'form'], 'auth');
     */
    public function form(): void
    {
        // Optional safety: if someone hits this without the 'auth' guard working
        if (Auth::userId() === null) {
            Flash::setFlash('error', 'You must be logged in to create a post.');
            header('Location: /login');
            exit;
        }

        $this->viewPosts('form', [
            'title' => 'Create a new post',
        ]);
    }

    /**
     * GET /posts/show/{id}
     */
    public function show(int $id): void
    {
        $post     = $this->post->findPost($id);
        $comments = $this->comment->forPost($id);

        // Default author name if we can’t find one
        $authorName = 'Unknown author';

        // Try to load the author based on the post author_id
        if (!empty($post['author_id'])) {
            $author = $this->user->findById((int) $post['author_id']);

            if ($author !== null && isset($author['name'])) {
                $authorName = $author['name'];
            }
        }

        $this->viewPosts('show', [
            'title'       => $post['title'] ?? 'Post',
            'post'        => $post,
            'comments'    => $comments,
            'author_name' => $authorName,
        ]);
    }

    /**
     * POST /posts          → create
     * POST /posts/{id}     → update
     */
    public function store($id = null): void
    {
        // Extra safety: ensure user is logged in
        if (Auth::userId() === null) {
            Flash::setFlash('error', 'You must be logged in to save a post.');
            header('Location: /login');
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $body  = trim($_POST['body'] ?? '');

        if ($title === '' || $body === '') {
            http_response_code(422);
            echo "Title and body are required.";
            return;
        }

        // If $id is not null → we're updating an existing post
        if ($id !== null) {
            $tempPost = $this->post->findPost($id);

            if ($tempPost === null) {
                Flash::setFlash('error', 'There is no post with such ID to update.');
                header('Location: /posts');
                exit;
            }

            if (Auth::userId() === $tempPost['author_id']) {
                $this->post->update((int) $id, $title, $body);
                Flash::setFlash('success', 'Post updated successfully');
                header('Location: /posts');
                exit;
            }

            Flash::setFlash('error', 'The post is not yours to change');
            header('Location: /posts');
            exit;
        }

        // Otherwise → this is a new post
        $author = $this->user->findById(Auth::userId());

        $this->post->saveToArray($title, $body, Auth::userId(), $author['name'] ?? 'Unknown author');
        $this->post->saveToStorage();

        Flash::setFlash('success', 'Post created successfully.');
        header('Location: /posts');
        exit;
    }

    /**
     * POST /posts/delete/{id}
     */
    public function destroy($id): void
    {
        $id = (int) $id;

        $tempPost = $this->post->findPost($id);

        if ($tempPost === null) {
            Flash::setFlash('warning', 'There is no such post to remove');
            header('Location: /posts');
            exit;
        }

        $authorId = $tempPost['author_id'] ?? null;

        if ((int) $authorId !== (int) Auth::userId()) {
            Flash::setFlash('warning', 'You cannot delete a post you do not own.');
            header('Location: /posts/show/' . $id);
            exit;
        }

        $this->post->delete($id);

        Flash::setFlash('success', 'The post was deleted successfully');
        header('Location: /posts');
        exit;
    }

    /**
     * GET /posts/edit/{id}
     */
    public function editForm($id): void
    {
        $id = (int) $id;

        if (Auth::userId() === null) {
            Flash::setFlash('error', 'You must be logged in to edit a post.');
            header('Location: /login');
            exit;
        }

        $post = $this->post->findPost($id);

        if ($post === null) {
            Flash::setFlash('error', 'There is no post with such ID to edit.');
            header('Location: /posts');
            exit;
        }

        $authorId = $post['author_id'] ?? null;

        if ((int) $authorId !== (int) Auth::userId()) {
            Flash::setFlash('warning', 'You cannot edit a post you do not own.');
            header('Location: /posts/show/' . $id);
            exit;
        }

        $this->viewPosts('edit', [
            'post' => $post,
        ]);
    }
}
