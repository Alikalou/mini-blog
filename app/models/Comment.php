<?php
class Comment{
    private array $comments=[];
    private Filestorage $storage;

    public function __construct(){
        $this->storage=new FileStorage(__DIR__.'/../../data/comments.json');
        $this->comments=$this->storage->retrieve();
    }

    public function forPost(int $postId): array{
        $result=[];
        foreach($this-> comments as $comment)
        {
            if( (int) ($comment['post_id']?? null) === $postId){
                $result[]=$comment;

            }
        }   
        return $this->sortComments($result)?? [];
    }

    public function createComment(int $postId, string $body, string $authorName, int $authorId )
    {
        $existingIds= array_column($this->comments, 'id');
        $maxId= $existingIds ? max($existingIds) : 0;
        $maxId++;
        

        $this->comments[]=[
            'id'=>$maxId,
            'post_id'=>$postId,
            'author_id'=> $authorId,
            'author'=>$authorName,
            'body'=>$body,
            'created_at'=>date('Y-m-d H:i:s'),
        ];
            
        $this->storage->save($this->comments);

        
    }

    public function deleteComment($commentId){
        foreach($this->comments as $index=>$comment){
            if( (int) ($comment['id']?? null) === $commentId){
                unset($this->comments[$index]);
                $this->storage->save(array_values($this->comments));
                
            }
        }

    }

    public function findComment($commentId):?array
    {
        foreach($this->comments as $comment){
            if( (int) $comment['id'] ===$commentId){
                return $comment;;
            }
        }
        return null;
    }

    public function sortComments(array $comments): array
    {
        usort($comments, function ($a, $b) {
            // Newest first → compare datetime strings in reverse
            return strcmp($b['created_at'], $a['created_at']);
        });

        return $comments;
    }

    public function getAuthorId(int|string $commentId): ?int
    {
        $commentId= (int) $commentId;

        foreach( $this->comments as $comment)
            if( (int) ($comment['id'] ?? 0) ===$commentId)
               
                return $comment['author_id']?? null;
                 //If the comment is found, make sure that it has an author id, otherwise notify 
                //the controller by returning null

        return null;
    }


}
