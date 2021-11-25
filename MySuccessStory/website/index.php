<?php
    use Router\Router;
    require './vendor/autoload.php';
    require('./controller/controller.php');
    $router = new Router($_GET['url']);
    $router->show();
