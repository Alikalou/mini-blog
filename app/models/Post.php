<?php

require_once __DIR__.'/../../core/storage.php';

/*
1. Add a Model layer (instead of hardcoded arrays)

You’re still defining posts manually inside your controller:
*/

class Post{

    private FileStorage $storage;
    private array $posts=[];

    public function __construct(){
        $this->storage=new FileStorage(__DIR__.'/../../data/posts.json');
        $this->posts=$this->storage->retrieve();
    }
    
    public function all():array{
        return array_values($this->posts);
    }

    public function findPost(int|string $id): ?array
    {
        $id = (int) $id;

        foreach ($this->posts as $post) {
            if ((int)($post['id'] ?? 0) === $id) {
                return $post;
            }
        }

        return null;
    }

    public function saveToArray(string $title, string $body):void{

        $newId=count($this->posts)+1;
        
        $this->posts[]=[
            'id'=>$newId,
            'title'=>$title,
            'body'=>$body,
        ];


    }

    public function saveToStorage():void{
        $this->storage->save($this->posts);
    }

}