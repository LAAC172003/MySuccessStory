<?php


//Controller class
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\Controllers\ControllerHome;
use MySuccessStory\Controllers\ControllerSubjects;
use MySuccessStory\Controllers\ControllerLogin;
use MySuccessStory\Controllers\ControllerRegister;
use MySuccessStory\Controllers\ControllerProfil;

///API///
use MySuccessStory\api\controller\index;

// SimpleRouter::get('/api', [Index::class, 'api']);
SimpleRouter::get('/api/{data?}/{prenom?}/{nom?}', [Index::class, 'apiFunctions']);
// SimpleRouter::get('/api/{data?}/{prenom?}/{nom?}', [Index::class, 'apiFunctions']);
///FIN API///

//Controllers functions
SimpleRouter::form('/', [ControllerHome::class, 'home']);
SimpleRouter::form('/subjects', [ControllerSubjects::class, 'subjects']);
SimpleRouter::form('/login/{email?}', [ControllerLogin::class, 'login']);
SimpleRouter::form('/register', [ControllerRegister::class, 'register']);
SimpleRouter::form('/profil', [Controllerprofil::class, 'profil']);
