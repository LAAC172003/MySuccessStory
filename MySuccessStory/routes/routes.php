<?php
// Controller class
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\Controllers\ControllerUsers;
use MySuccessStory\controllers\ControllerNotes;


SimpleRouter::get('/api/subjects', [ControllerUsers::class, 'token']);//-> returns token
SimpleRouter::get('/api/login', [ControllerUsers::class, 'token']);

SimpleRouter::get('/api/profile', [ControllerUsers::class, 'profile']);//


SimpleRouter::post('/api/notes', [ControllerNotes::class, 'create']);// create
SimpleRouter::get('/api/notes/{idNote}', [ControllerNotes::class, 'read']);//read
SimpleRouter::patch('/api/notes/{idNote}', [ControllerNotes::class, 'update']);//update
SimpleRouter::delete('/api/notes/{idNote}', [ControllerNotes::class, 'delete']);//delete

SimpleRouter::post('/api/users', [ControllerUsers::class, 'create']);// create
SimpleRouter::get('/api/users/{idUser}', [ControllerUsers::class, 'read']);//read
SimpleRouter::patch('/api/users/{idUser}', [ControllerUsers::class, 'update']);//update
SimpleRouter::delete('/api/users/{idUser}', [ControllerUsers::class, 'delete']);//delete
