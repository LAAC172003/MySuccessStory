<?php

#region Use
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\Controllers\ControllerUsers;
use MySuccessStory\controllers\ControllerNotes;

#endregion

#region Connection
SimpleRouter::get('/api/token', [ControllerUsers::class, 'token']); // Create a token
SimpleRouter::post('/api/test', [ControllerUsers::class, 'test']); // Create a token
//SimpleRouter::get('/api/login', [ControllerUsers::class, 'token' /* "login" */]); // allow the user to log in to his account
SimpleRouter::get('/api/profile', [ControllerUsers::class, 'profile']); // shows the profile of a user
SimpleRouter::put('/api/register', [ControllerUsers::class, 'register']); // crearte a new account
#endregion

#region CRUD Notes
// SimpleRouter::get('/api/notes', [ControllerNotes::class, 'read']); // read all notes
SimpleRouter::get('/api/notes/{idNote}', [ControllerNotes::class, 'read']);// read a single note
SimpleRouter::post('/api/notes/{note}/{semester}/{idUser}/{idSubject}', [ControllerNotes::class, 'create'], ["defaultParameterRegex" => "[\w\-\.]+"]); // add a new note to the database
SimpleRouter::patch('/api/notes/{idNote}/{note}', [ControllerNotes::class, 'update']);// change the value of a note
SimpleRouter::delete('/api/notes/{idNote}', [ControllerNotes::class, 'delete']);// delete a note
#endregion

SimpleRouter::post('/api/users', [ControllerUsers::class, 'create']);// create
SimpleRouter::get('/api/users/{idUser}', [ControllerUsers::class, 'read']);//read
SimpleRouter::patch('/api/users/{idUser}/', [ControllerUsers::class, 'update']);//update
SimpleRouter::delete('/api/users/{idUser}', [ControllerUsers::class, 'delete']);//delete


#region Subjects
SimpleRouter::get('/api/subjects', [ControllerUsers::class, 'token']); // shows all the subjects
#endregion
