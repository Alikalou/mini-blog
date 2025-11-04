<?php

class Controller{


    protected function viewPosts($view, $data=[]){

        extract($data);
        require __DIR__.'/../app/views/posts/'.$view.'.php';
        //Require here literally imports the code and place it in the place it was written.    
    }
    
    protected function viewHome($view, $data=[]){

        extract($data);
        require __DIR__.'/../app/views/'.$view.'.php';

    }

}