<?php

use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\Controleur\ControleurAccueil;
use MySuccessStory\Controleur\ControleurSujets;

SimpleRouter::form('/', [ControleurAccueil::class, 'accueil']);
SimpleRouter::form('/', [ControleurSujets::class, 'subjects']);