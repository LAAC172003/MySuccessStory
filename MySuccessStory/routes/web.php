<?php

use Pecee\SimpleRouter\SimpleRouter;
//Controlleurs
use MySuccessStory\Controleur\ControleurAccueil;
use MySuccessStory\Controleur\ControleurSujets;
// use MySuccessStory\Controleur\ControleurConnexion;

SimpleRouter::form('/', [ControleurAccueil::class, 'accueil']);
SimpleRouter::form('/', [ControleurSujets::class, 'subjects']);
// SimpleRouter::form('/', [ControleurConnexion::class, 'connexion']);
