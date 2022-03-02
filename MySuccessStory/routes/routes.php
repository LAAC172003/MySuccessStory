<?php
// Controller class
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\Controllers\ControllerUsers;
use MySuccessStory\controllers\ControllerNotes;


SimpleRouter::get('/api/subjects', [ControllerUsers::class, 'token']);//-> returns token
SimpleRouter::get('/api/login', [ControllerUsers::class, 'token']);

SimpleRouter::get('/api/profile', [ControllerUsers::class, 'profile']);//

SimpleRouter::post('/api/register', [ControllerUsers::class, 'register']);//
//SimpleRouter::post('/api/login', [ControllerUsers::class, 'login']);//

SimpleRouter::post('/api/notes', [ControllerNotes::class, 'create']);// create
SimpleRouter::get('/api/notes/{subject}', [ControllerNotes::class, 'read']);//read
SimpleRouter::patch('/api/notes/{subject}', [ControllerNotes::class, 'update']);//update
SimpleRouter::delete('/api/notes/{subject}', [ControllerNotes::class, 'delete']);//delete
