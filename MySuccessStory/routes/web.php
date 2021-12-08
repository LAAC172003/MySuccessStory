<?php


//Controller class
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\Controllers\ControllerHome;
use MySuccessStory\Controllers\ControllerSubjects;
use MySuccessStory\Controllers\ControllerLogin;
use MySuccessStory\Controllers\ControllerRegister;

///API///
use MySuccessStory\api\controller\index;

// SimpleRouter::get('/api', [Index::class, 'api']);
SimpleRouter::get('/api/{data?}/{email?}', [Index::class, 'apiFunctions']);
// SimpleRouter::get('/api/{data?}', [Index::class, 'apiFunctions']);
///FIN API///

//Controllers functions
SimpleRouter::form('/', [ControllerHome::class, 'home']);
SimpleRouter::form('/subjects', [ControllerSubjects::class, 'subjects']);
SimpleRouter::form('/login', [ControllerLogin::class, 'login']);
SimpleRouter::form('/register', [ControllerRegister::class, 'register']);
