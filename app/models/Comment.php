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

    public function createComment(int $postId,string $author,string $body)
    {
        $this->comments[]=[
            'id'=>count($this->comments)+1,
            'post_id'=>$postId,
            'author'=>$author,
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


}
