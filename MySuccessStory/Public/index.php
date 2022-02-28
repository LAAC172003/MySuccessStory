<?php

// DO NOT TOUCH !

require '../vendor/autoload.php';
require '../routes/web.php';

use Pecee\SimpleRouter\SimpleRouter;

// Registering the default namespace for controllers
SimpleRouter::setDefaultNamespace('\MySuccessStory\Controller');

// Launch of the router
SimpleRouter::start();
