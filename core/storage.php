<?php

class FileStorage{

    public function __construct(private string $path){

        if(!file_exists($this->path))
        {
            file_put_contents($this->path,json_encode([]));
        }
    }

    public function save(array $data):void{
        file_put_contents($this->path, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function retrieve():array{

        $content=file_get_contents($this->path);
        return json_decode($content, true)?? [];
    }

}