<?php
//Load core files
require_once __DIR__.'/../core/controller.php';
require_once __DIR__.'/../core/router.php';

//Load controller
require_once __DIR__.'/../app/controllers/PostController.php';
require_once __DIR__.'/../app/controllers/HomeController.php';




$router=new Router();
$postController=new PostController();
$homeController= new HomeController();

$router->get('/', [$homeController, 'index']);


$router->get('/posts', [$postController, 'index']);
$router->get('/posts/create', [$postController, 'form']);
$router->post('/posts', [$postController, 'store']);

//dynamic route with {id} parameter
$router->get('/posts/show/{id}', [$postController, 'show']);



$method=$_SERVER['REQUEST_METHOD']?? 'GET';
$uri=parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->dispatch($method, $uri);



