<?php


class Controller{


    protected function render($view, $path, $data=[])
    {
        extract($data);

        ob_start();

        require $path;

        $content=ob_get_clean();

        require __DIR__.'/../app/views/layout.php';
    }

    protected function viewPosts($view, $data=[]){
        $path=__DIR__.'/../app/views/posts/'.$view.'.php';

        $this->render($view, $path, $data);
        //Require here literally imports the code and place it in the place it was written.    
    }
    
    protected function viewHome($view, $data=[]){

        $path= __DIR__.'/../app/views/'.$view.'.php';

        $this->render($view, $path, $data);
    }

}