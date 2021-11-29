<?php

use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\Controleur\ControleurAccueil;

SimpleRouter::form('/', [ControleurAccueil::class, 'accueil']);