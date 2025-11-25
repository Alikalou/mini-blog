<?php
//Load core files

session_start();
require_once __DIR__.'/../core/controller.php';
require_once __DIR__.'/../core/router.php';

//Load controller
require_once __DIR__.'/../app/controllers/PostController.php';
require_once __DIR__.'/../app/controllers/HomeController.php';
require_once __DIR__.'/../app/controllers/CommentsController.php';
require_once __DIR__.'/../app/controllers/AuthController.php';


$router=new Router();
$controller= new Controller();
$postController=new PostController();
$homeController= new HomeController();
$commentsController=new CommentsController();
$authController= new AuthController();

$router->get('/', [$homeController, 'index']);


$router->get('/posts', [$postController, 'index']);
$router->get('/posts/create', [$postController, 'form'], 'auth');
$router->post('/posts', [$postController, 'store'], 'auth');

//dynamic route with {id} parameter
$router->get('/posts/show/{id}', [$postController, 'show']);
$router->get('/posts/edit/{id}', [$postController, 'editForm'], 'auth');
$router->post('/posts/save/{id}', [$postController, 'store'], 'auth');
$router->post('/posts/delete/{id}', [$postController, 'destroy'], 'auth');

//dynamic route with {id} parameter for comments
$router->post('/posts/{postId}/comments', [$commentsController, 'store'], 'auth');
$router->post('/posts/{postId}/comments/delete/{commentId}', [$commentsController, 'destroy'], 'auth');


//routes for authentication can be added here
//We need a route for login, getting its form and posting it
$router->get('/login', [$authController, 'showLoginForm']);
$router->post('/login', [$authController, 'login']);

//we need a route for logout
$router->get('/logout', [$authController, 'logout']);

//we need two routes for registration: one for showing the form and another for posting it
$router->get('/register', [$authController, 'showRegistrationForm']);
$router->post('/register', [$authController, 'register']);



$method=$_SERVER['REQUEST_METHOD']?? 'GET';
$uri=parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->dispatch($method, $uri);

