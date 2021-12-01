<?php


namespace MySuccessStory\Controleur;

use MySuccessStory\Modele\SqlConnetionClass;
use MySuccessStory\Modele\Subjects;

class ControleurSujets
{
    public function subjects()
    {
        $db = new SqlConnetionClass(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $subjects = Subjects::getSubjects($db,"SELECT idSubject,s.name,c.name as 'category' from subject s inner join category c on s.idCategory=c.idCategory");
        require '../src/Vue/VueSujets.php';
    }
}
