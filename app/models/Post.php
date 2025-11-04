<?php

class Post{

    private $arrayPost=[];
    public static function all():array
    {
        return $this->arrayPost;
    }
    /*
    Pass 2 – Slightly more real

    Add Post model (even if data is an array in Post::all())

    Add “show” route: /posts/{id}

    Pass data to views
    */

}