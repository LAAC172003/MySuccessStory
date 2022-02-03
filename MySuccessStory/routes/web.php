<?php

// Controller class
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\Controllers\ControllerHome;
use MySuccessStory\Controllers\ControllerSubjects;
use MySuccessStory\Controllers\ControllerLogin;
use MySuccessStory\Controllers\ControllerRegister;
use MySuccessStory\Controllers\ControllerProfile;
use MySuccessStory\Controllers\ControllerEdit;
use MySuccessStory\Controllers\ControllerDelete;
use MySuccessStory\Controllers\ControllerAdd;

use MySuccessStory\Controllers\ControllerSimulation;

#region API
use MySuccessStory\api\controller\index;

// SimpleRouter::get('/api', [Index::class, 'api']);
SimpleRouter::get('/api/{data?}/{prenom?}/{nom?}/{order?}/{asc?}', [Index::class, 'apiFunctions']);
// SimpleRouter::get('/api/{data?}/{prenom?}/{nom?}', [Index::class, 'apiFunctions']);
#endregion

//Controllers functions
SimpleRouter::form('/', [ControllerHome::class, 'home']);
SimpleRouter::form('/subjects', [ControllerSubjects::class, 'subjects']);
SimpleRouter::form('/login', [ControllerLogin::class, 'login']);
SimpleRouter::form('/register', [ControllerRegister::class, 'register']);
SimpleRouter::form('/profile', [ControllerProfile::class, 'profile']);

#region Notes
// delete
SimpleRouter::form('/deleteNote', [ControllerDelete::class, 'deleteNote']);
// edit
SimpleRouter::form('/editNote', [ControllerEdit::class, 'editNote']);
// add
SimpleRouter::form('/addNote', [ControllerAdd::class, 'addNote']);
#endregion

#region Profile
// edit Profile
SimpleRouter::form('/editProfile', [ControllerEdit::class, 'editProfile']);
SimpleRouter::form('/deleteProfile', [ControllerDelete::class, 'deleteProfile']);
#endregion
SimpleRouter::form('/simulation', [ControllerSimulation::class, 'simulation']);
