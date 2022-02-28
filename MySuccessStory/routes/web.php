<?php

// Controller class
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\Controllers\ControllerHome;
use MySuccessStory\Controllers\ControllerSubjects;
use MySuccessStory\Controllers\ControllerLogin;
use MySuccessStory\Controllers\ControllerUser;
use MySuccessStory\Controllers\ControllerNotes;
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
SimpleRouter::form('/register', [ControllerUser::class, 'register']);
SimpleRouter::form('/notes', [ControllerNotes::class, 'showNotes']);

#region Notes
// delete
SimpleRouter::form('/deleteNote', [ControllerDelete::class, 'deleteNote']);
// edit
SimpleRouter::form('/editNote', [ControllerEdit::class, 'editNote']);
// add
SimpleRouter::form('/addNote', [ControllerAdd::class, 'addNote']);
#endregion

#region User
// edit User
SimpleRouter::form('/myAccount', [ControllerUser::class, 'edit']);
SimpleRouter::form('/deleteUser', [ControllerUser::class, 'delete']);
SimpleRouter::form('/myAccount', [ControllerUser::class, 'showUserAccountInformation']);
#endregion
SimpleRouter::form('/simulationYear', [ControllerSimulation::class, 'simulationYear']);
SimpleRouter::form('/simulationSemester', [ControllerSimulation::class, 'simulationSemester']);
