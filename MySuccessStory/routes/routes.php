<?php
// Controller class
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\Controllers\ControllerUsers;
use MySuccessStory\controllers\ControllerNotes;

//Controller ModelUsers

SimpleRouter::get('/api/subjects', [ControllerUsers::class, 'token']);//-> returns token
SimpleRouter::post('/api/login', [ControllerUsers::class, 'token']);//-> returns token
SimpleRouter::get('/api/profile', [ControllerUsers::class, 'profile']);//-> returns token
SimpleRouter::get('/api/notes', [ControllerNotes::class, 'notes']);

SimpleRouter::post('/api/notes', [ControllerNotes::class, 'addNote']);// create
SimpleRouter::get('/api/notes/{subject}', [ControllerNotes::class, 'notes']);//read
SimpleRouter::patch('/api/notes/{subject}', [ControllerNotes::class, 'addNote']);//update
SimpleRouter::delete('/api/notes/{subject}', [ControllerNotes::class, 'addNote']);//delete

//Controller Notes
//SimpleRouter::get('api/login/{token}/notes', []);
//SimpleRouter::get('api/login/{token}/notes/{subject}', []);