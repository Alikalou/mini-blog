<?php

require_once __DIR__.'/../../core/controller.php';
require_once __DIR__.'/../../core/flash.php';
require_once __DIR__.'/../../core/auth.php';
require_once __DIR__.'/../models/User.php';


class AuthController extends Controller{

    private User $user;

    public function __construct()
    {
        parent::__construct();

        $this->user=new User();
    }
    
    public function showRegistrationForm(){

        if(Auth:: userId())
        {
            Flash:: setFlash('warning', 'You are already logged in.');
            header('Location: /posts');
            exit;

        }
        else
            $this->viewAuth('registrationForm', []);
        
    }

    
    public function register(){
        $name=$_POST['name'];
        $email=strtolower($_POST['email']);
        $password=$_POST['password'];
        $passwordConf=$_POST['passwordConf'];

        if( empty($name) )
        {
            Flash:: setFlash('warning', 'Name field is required');
            header('Location: /register');
            exit;
        }

        if( empty($email) || ! ($this->user->uniqueEmail($email)) || ! ($this->user->formatEmail($email)) )
        {
            Flash:: setFlash('warning', 'The email you entered doesn\'t follow the credentials');
            header('Location: /register');
            exit;
        }
            
        if( empty($password) || strlen($password)<10)
        {
            Flash:: setFlash('warning', 'The password should not be empty and of length less than 10');
            header('Location: /register');
            exit;
        }

        if( strcmp($password, $passwordConf))
        {
            Flash:: setFlash('warning', 'Password and its confirmation are not similar');
            header('Location: /register');
            exit;
        }   
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $id=$this->user->create($name, $email, $hashedPassword);

        Auth::login($id);

        Flash:: setFlash('success', 'Welcome to mini blog, '.$name);
        header('Location: /posts');
        exit;

    } 

    
    public function showLoginForm(){
        
        if(Auth:: userId())
        {
            Flash:: setFlash('warning', 'You are already logged in.');
            header('Location: /posts');
            exit;

        }
        else
            $this->viewAuth('loginForm', []);
        
    }

  
    public function login()
    {
        // Normalize input
        $email    = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        // Basic validation
        if (empty($email) || empty($password)) {
            Flash::setFlash('warning', 'Missing email or password.');
            header('Location: /login');
            exit;
        }

        // Optional: email format check (fail silently as "wrong credentials")
        // so you don't reveal which part is wrong.
        if (! $this->user->formatEmail($email)) {
            Flash::setFlash('warning', 'Wrong credentials.');
            header('Location: /login');
            exit;
        }

        // Attempt to find user by email
        $tempUser = $this->user->findByEmail($email);

        // If user exists AND password matches the stored hash → login
        if ($tempUser !== null && password_verify($password, $tempUser['password'])) {
            Auth::login($tempUser['id']);
            Flash::setFlash('success', 'Welcome ' . $tempUser['name'] . '!');
            header('Location: /posts');
            exit;
        }

        // Otherwise → generic error
        Flash::setFlash('warning', 'Wrong credentials.');
        header('Location: /login');
        exit;
    }


    public function logout()
    {
    Auth::logout(); // Clears session no matter what
    Flash::setFlash('success', 'You have been logged out.');
    header('Location: /posts');
    exit;
    }


}

