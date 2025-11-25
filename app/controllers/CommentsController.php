<?php

require_once __DIR__.'/../models/Post.php';
require_once __DIR__.'/../models/Comment.php';
require_once __DIR__.'/../../core/auth.php';
require_once __DIR__.'/../../core/flash.php';


class CommentsController extends Controller{
    private Post $post;
    private Comment $comment;
    private User $user;
    public function __construct(){

        parent::__construct();

        $this->user= new User();
        $this->post=new Post();
        $this->comment=new Comment();
    }
    
    //since we are using flashes, there is really no need to return any value from a function in case of failure.
    
    public function store(int $postId): void
    {
        
        $postId = (int) $postId;

    
        // 1) Ensure post exists
        $tempPost = $this->post->findPost($postId);
        if ($tempPost === null) {
            Flash::setFlash('error', 'There is no such post to add a comment to.');
            header('Location: /posts');
            exit;
        }

        // 2) Validate input
        //$author = trim($_POST['author'] ?? '');
        $body   = trim($_POST['body'] ?? '');

        if ( $body === '') {
            Flash::setFlash('warning', 'Body cannot be empty.');
            header('Location: /posts/show/' . $postId);
            exit;
        }

        if ( strlen($body) > 150) {
            Flash::setFlash('warning', ' Comment shouldn\'t be longer than 150 characters.');
            header('Location: /posts/show/' . $postId);
            exit;
        }

        // 3) Create the comment
        // ⛔️ Do NOT use $_SESSION['id'] here — you need the post id
        // If your model method is createComment(postId, author, body):
        $author= $this->user->findById(Auth:: userId() );
        $this->comment->createComment($postId, $body, $author['name'], $author['id'] );

        // 4) PRG redirect
        Flash::setFlash('success', 'Comment added.');
        header('Location: /posts/show/' . $postId); // optionally add '#comments'
        exit;


    }

    public function destroy(int $postId, int $commentId): void
    {
        $postId= (int) $postId;
        $commentId= (int) $commentId;
         
        
        //check author id attribute of the comment against the logged in user id.
        $tempPost= $this->post->findPost( $postId );
        $tempComment= $this->comment->findComment( $commentId );

        if( $tempPost === null){
            Flash::setFlash('warning', 'There is no such post to remove a comment from');
            header('Location: /posts');
            exit;
        }

        if( $tempComment === null){
            Flash::setFlash('warning', 'There is no such comment to remove');
            header('Location: /posts/show/' . $postId);
            exit;
        }
        
        // 3) Verify ownership
        if ( (int) $tempComment['post_id'] !== $postId) {
            Flash::setFlash('warning', 'This comment does not belong to this post.');
            header('Location: /posts/show/' . $postId);
            exit;
        }

        if( (int) $tempComment['author_id'] !== (int) Auth::userId()  ){
            Flash::setFlash('warning', 'You can\'t destroy a comment that is not yours');
            header('Location: /posts/show/'.$postId);
            exit;
        }


        // 4) Delete it
        $this->comment->deleteComment($commentId);

        Flash::setFlash('success', 'Comment deleted successfully.');
        header('Location: /posts/show/' . $postId);
        exit;
    }


}