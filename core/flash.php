<?php

declare(strict_types=1);

class Flash{

    
    private static array $levels= ['success', 'error', 'info', 'warning'];

    public static function setFlash(string $level, string $message): void{
        
        if(in_array($level, self::$levels)===false){
            throw new \InvalidArgumentException("No such level: $level");
            
        }

        $message= trim($message);
        $_SESSION['flash']=['level'=>$level, 'message'=>$message];

    }
    
    public static function getFlash(): ?array{

        
        if (empty($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
            return null;
        }

        $flash=$_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;

    }


}