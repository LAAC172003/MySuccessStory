<?php


//Controller class
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\ControllerClasses\ControllerHome;
use MySuccessStory\ControllerClasses\ControllerSubjects;

///API///
use MySuccessStory\api\ControllerApi;

SimpleRouter::get('/api', [ControllerApi::class, 'indexApi']);
///FIN API///

//Controllers functions
SimpleRouter::get('/', [ControllerHome::class, 'home']);
// SimpleRouter::get('/', [IndexController::class, 'index']);
SimpleRouter::get('/subjects', [ControllerSubjects::class, 'subjects']);
