<?php

use Pecee\SimpleRouter\SimpleRouter;
//Controller class
use MySuccessStory\ControllerClasses\ControllerHome;
use MySuccessStory\ControllerClasses\ControllerSubjects;
//Controllers functions
SimpleRouter::form('/', [ControllerHome::class, 'home']);
SimpleRouter::form('/', [ControllerSubjects::class, 'subjects']);
