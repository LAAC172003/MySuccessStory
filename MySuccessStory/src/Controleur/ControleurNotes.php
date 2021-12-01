<?php

namespace MySuccessStory\Controleur;

use MySuccessStory\Modele\Notes;

class ControleurNotes
{
    public function notes()
    {
        require_once '../src/Modele/identifiants.php';
        require '../src/Vue/VueNotes.php';
    }
}
