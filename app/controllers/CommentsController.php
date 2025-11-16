<?php

require_once __DIR__.'/../models/Post.php';
require_once __DIR__.'/../models/Comment.php';


class CommentsController extends Controller{
    private Post $post;
    private Comment $comment;

    public function __construct(){
            $this->post=new Post();
            $this->comment=new Comment();
    }
    //since we are using flashes, there is really no need to return any value from a function in case of failure.
    
    public function store(int $postId): void
    {
        $postId = (int) $postId;

        // 1) Ensure post exists
        $post = $this->post->findPost($postId);
        if ($post === null) {
            Flash::setFlash('error', 'There is no post with such ID to add a comment to.');
            header('Location: /posts');
            exit;
        }

        // 2) Validate input
        $author = trim($_POST['author'] ?? '');
        $body   = trim($_POST['body'] ?? '');

        if ($author === '' || $body === '') {
            Flash::setFlash('warning', 'Author and comment body cannot be empty.');
            header('Location: /posts/show/' . $postId);
            exit;
        }

        if (strlen($author) > 20 || strlen($body) > 150) {
            Flash::setFlash('warning', 'Please keep name ≤ 20 characters and comment ≤ 150 characters.');
            header('Location: /posts/show/' . $postId);
            exit;
        }

        // 3) Create the comment
        // ⛔️ Do NOT use $_SESSION['id'] here — you need the post id
        // If your model method is createComment(postId, author, body):
        $this->comment->createComment($postId, $author, $body);

        // 4) PRG redirect
        Flash::setFlash('success', 'Comment added.');
        header('Location: /posts/show/' . $postId); // optionally add '#comments'
        exit;
    }

    public function destroy(int $postId, int $commentId): void
    {
    $postId    = (int) $postId;
    $commentId = (int) $commentId;

    // 1) Check post exists
    if ($this->post->findPost($postId) === null) {
        Flash::setFlash('error', 'There is no such post to delete a comment from.');
        header('Location: /posts');
        exit;
    }

    // 2) Retrieve the comment
    $comment = $this->comment->findComment($commentId);
    if ($comment === null) {
        Flash::setFlash('error', 'There is no such comment to delete.');
        header('Location: /posts/show/' . $postId);
        exit;
    }

    // 3) Verify ownership
    if ((int)$comment['post_id'] !== $postId) {
        Flash::setFlash('error', 'This comment does not belong to this post.');
        header('Location: /posts/show/' . $postId);
        exit;
    }

    // 4) Delete it
    $this->comment->deleteComment($commentId);

    Flash::setFlash('success', 'Comment deleted successfully.');
    header('Location: /posts/show/' . $postId);
    exit;
    }


}