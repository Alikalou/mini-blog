<?php


class Controller{


    //The render method here of the controller handles the skeleton of the html pages.
    //The layout is our standrad of how our pages should look like.

    protected function render($path, $data=[])
    {
        //The data here is sent from the model to the controller.
        //Now the post data is accessible in the view files as vairable.
        extract($data);

        //open an output buffer
        ob_start();

        //The html of the page specified in the path variable will be loaded in the ob.
        require $path;

        //empty the buffer in content.
        $content=ob_get_clean();

        //import the layout.
        require __DIR__.'/../app/views/layout.php';
    }

    protected function viewPosts($view, $data=[]){
        $path=__DIR__.'/../app/views/posts/'.$view.'.php';

        $this->render($path, $data);
        //Require here literally imports the code and place it in the place it was written.    
    }
    
    protected function viewHome($view, $data=[]){

        $path= __DIR__.'/../app/views/'.$view.'.php';

        $this->render($path, $data);
    }

}