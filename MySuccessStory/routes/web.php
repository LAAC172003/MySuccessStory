<?php


//Controller class
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\Controllers\ControllerHome;
use MySuccessStory\Controllers\ControllerSubjects;

///API///
use MySuccessStory\api\controller\index;

SimpleRouter::get('/api', [Index::class, 'homeApi']);
SimpleRouter::get('/api/{data?}', [Index::class, 'api']);
// SimpleRouter::get('/test/show/{name?}', [TestController::class, 'show']);
///FIN API///

//Controllers functions
SimpleRouter::get('/', [ControllerHome::class, 'home']);
// SimpleRouter::get('/', [IndexController::class, 'index']);
SimpleRouter::get('/subjects', [ControllerSubjects::class, 'subjects']);
