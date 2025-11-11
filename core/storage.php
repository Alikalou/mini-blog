<?php

class FileStorage{

    public function __construct(private string $path){
        //The constructor job here is to check if the file doesn't exist, if yes then it creates it.
        if(!file_exists($this->path))
        {
            file_put_contents($this->path,json_encode([]));
        }
    }

    public function save(array $data):void{
        //Convert the data from the array form to json form and save it to the file.
        file_put_contents($this->path, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function retrieve():array{
        
        //Get the content of the file and store it in content variable.
        //then deecode that variable from json to array form and return it.
        $content=file_get_contents($this->path);
        return json_decode($content, true)?? [];
    }

}