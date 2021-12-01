<?php

namespace MySuccessStory\Controleur;
// use MySuccessStory\Modele\Notes;
// use MySuccessStory\Modele\SqlConnetionClass;
class ControleurAccueil
{
    public function accueil()
    {
        require_once '../src/Modele/Sujets.php';

        require '../src/Vue/VueAccueil.php';
    }
}
