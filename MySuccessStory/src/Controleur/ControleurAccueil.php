<?php

namespace MySuccessStory\Controleur;

use MySuccessStory\Modele\Fonctions;

class ControleurAccueil
{
    public function accueil()
    {
        $test = Fonctions::test();

        require '../src/Vue/VueAccueil.php';
    }
}
