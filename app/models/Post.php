<?php

require_once __DIR__.'/../../core/storage.php';

/*
1. Add a Model layer (instead of hardcoded arrays)

You’re still defining posts manually inside your controller:
*/

class Post{

    private FileStorage $storage;
    private array $posts=[];

    //Instantiate the storage and retrieve posts from it and assign it to the array property $posts
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

    public function update(int $id, string $title, string $body): bool
{
    foreach ($this->posts as $index => $post) {
        if ((int)($post['id'] ?? 0) === $id) {

            $this->posts[$index]['title'] = $title;
            $this->posts[$index]['body']  = $body;

            $this->saveToStorage();

            return true;
        }
    }

    return false;
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

    public function delete(int $id):bool{

        foreach($this->posts as $index=>$post){
            if((int)($post['id']??0)===$id){
                unset($this->posts[$index]);

                   $this->posts = array_values($this->posts);

            // Save changes
            $this->saveToStorage();

            return true;
            }
        }
        return false;

    }

    
}