<?php

// Controller class
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\Controllers\Users;
//Controller ModelUsers
//SimpleRouter::get('api/login/{user}/{pwd}', []);//-> returns token
SimpleRouter::get('/token', [Users::class,'Token']);//-> returns token

//Controller Notes
//SimpleRouter::get('api/login/{token}/notes', []);
//SimpleRouter::get('api/login/{token}/notes/{subject}', []);