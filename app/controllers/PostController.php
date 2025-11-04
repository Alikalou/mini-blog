<?php
require_once __DIR__.'\..\..\core\controller.php';

class PostController extends Controller {
    public function index() {
        $posts = [
            ['title' => 'First Post'],
            ['title' => 'Second Post'],
        ];
        
        // ✅ Correct: use key 'posts'
        $this->viewPosts('index', ['posts' => $posts]);
    }



    public function show(){
        $post=[
            'title'=>'A single post page'
        ];
        $this->viewPosts('show', $post);
    }

    //So, we need two functions in the post
    //controller, since post controller will be responsible for the control
    //of two interfaces, the index and the show pages.

}