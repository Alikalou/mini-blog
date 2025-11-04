<?php

require_once __DIR__ . '/../../core/Controller.php';

class HomeController extends Controller
{
    public function index()
    {
        $greeting = ['title' => 'Welcome to My Mini MVC Blog'];

        // This will load app/views/home.php
        $this->viewHome('home', $greeting);
    }
}
