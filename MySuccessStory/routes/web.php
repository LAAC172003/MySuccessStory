<?php


//Controller class
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\controllerClasses\ControllerHome;
use MySuccessStory\controllerClasses\ControllerSubjects;

///API///
use MySuccessStory\api\controller\index;

SimpleRouter::get('/api/{data?}', [index::class, 'indexApi']);
// SimpleRouter::get('/test/show/{name?}', [TestController::class, 'show']);
///FIN API///

//Controllers functions
SimpleRouter::get('/', [ControllerHome::class, 'home']);
// SimpleRouter::get('/', [IndexController::class, 'index']);
SimpleRouter::get('/subjects', [ControllerSubjects::class, 'subjects']);
