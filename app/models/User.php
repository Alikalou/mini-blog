<?php

require_once __DIR__.'/../../core/storage.php';

class User{

    private FileStorage $storage;
    private array $users=[];
    
    public function __construct(){

        
        $this->storage= new FileStorage(__DIR__.'/../../data/users.json');
        $this->users= $this->storage->retrieve();
    }
    //what I did is that I retreived the users data and assigned it to $users array.

    public function create(string $name, string $email, string $password): int{
        

        if($this->emailIsTaken($email))
            return false;

        $existingIds = array_column($this->users, 'id');
        $maxId = $existingIds ? max($existingIds) : 0;
        $newId = $maxId + 1;
        
        $this->users[]=[
            'id'=> $newId,
            'name'=> $name,
            'email'=> $email,
            'password'=> $password,// already hashed BEFORE passing here
        ];
        
        $this->storage->save($this->users);

        return $newId;
    }

    public function findById(int|string $id): ?array{
        //two things here, the function expects either a string or an int
        //then it cast either of the types to int anyway
        $id= (int) $id;

        //We loop over the array users, taking its indecises as a user
        //Inside that user (index), we check if the id is equal to the passed parameter.
        foreach($this->users as $user)
        {
            if( (int) ($user['id'] ?? 0) === $id)
                return $user;
        }
        
        return null;
    }


    public function findByEmail(string $email): ?array{

        foreach($this->users as $user)
            if( ($user['email']?? '') === $email)
                return $user;
        
        return null;
    }

    public function emailIsTaken(string $email): bool{
        return $this->findByEmail($email) !== null ;
            
    }

    public function formatEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function uniqueEmail(string $email): bool
    {
        foreach ($this->users as $user) {
            if (isset($user['email']) && strtolower($user['email']) === strtolower($email)) {
                return false;
            }
        }

        return true;
    }

}


