<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;
use MySuccessStory\Api\Model\Note;

class ControllerSimulation
{
    /**
     * simulation to know if the student has graduated or not
     *
     * @return string message to let him know if he has graduated
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function simulation()
    {
        if (!isset($_COOKIE['email']))
        {
            header('Location:http://mysuccessstory/');
        }
        $functions = new Functions();
        $functionsNotes = new Note();
        $emailParts = explode(".", $_COOKIE['email']);
        if ($functions->refreshCookie()) {
            $english = $functions->curl("http://mysuccessstory/api/getEnglish/$emailParts[0]/$emailParts[1]");
            $economy = $functions->curl("http://mysuccessstory/api/getEconomy/$emailParts[0]/$emailParts[1]");
            $maths = $functions->curl("http://mysuccessstory/api/getMaths/$emailParts[0]/$emailParts[1]");
            $physics = $functions->curl("http://mysuccessstory/api/getPhysics/$emailParts[0]/$emailParts[1]");
            $physicalEducation = $functions->curl("http://mysuccessstory/api/getPhysicalEducation/$emailParts[0]/$emailParts[1]");
            $CIE = $functions->curl("http://mysuccessstory/api/getCIENotes/$emailParts[0]/$emailParts[1]");
            $CI = $functions->curl("http://mysuccessstory/api/getCINotes/$emailParts[0]/$emailParts[1]");
            
            $subjectsByCFC = $functions->curl("http://mysuccessstory/api/getSubjectsByCategoryCFC");
            $subjectsByCG = $functions->curl("http://mysuccessstory/api/subjects");

            var_dump($subjectsByCG[0]);
            //Informatique
            $CIENotes[] = $CIE;
            $CINotes[] = $CI;

            //CG
            $notesEnglish[] = $english;
            $notesEconomy[] = $economy;
            $notesMaths[] = $maths;
            $notesPhysics[] = $physics;
            $notesPhysicalEducation[] = $physicalEducation;

            $resultEnglish = $functionsNotes->passMark($notesEnglish);
            $resultEconomy = $functionsNotes->passMark($notesEconomy);
            $resultMaths = $functionsNotes->passMark($notesMaths);
            $resultPhysics = $functionsNotes->passMark($notesPhysics);
            $resultPhysicalEducation = $functionsNotes->passMark($notesPhysicalEducation);

            //TPI
            $resultTPI = 2;

            //CBE

            // var_dump($resultEnglish, $resultEconomy, $resultMaths, $resultPhysics);

            $resultCBE =  $functionsNotes->noteCBE($resultEnglish, $resultEconomy, $resultMaths, $resultPhysics);

            //CI
            $resultCI = $functionsNotes->noteCI($functionsNotes->passMark($CIENotes), $functionsNotes->passMark($CINotes));

            //CG
            $notesCG[] = $resultEnglish + $resultEconomy + $resultMaths + $resultPhysics + $resultPhysicalEducation;
            $resultCG = $functionsNotes->calculate($notesCG);



            if (false) {
                $premiereAnneeCg = filter_input(INPUT_POST, 'premiereAnneeCg', FILTER_VALIDATE_INT);
                $deuxiemeAnneeCg = filter_input(INPUT_POST, 'deuxiemeAnneeCg', FILTER_VALIDATE_INT);
                $troisiemeAnneeCg = filter_input(INPUT_POST, 'troisiemeAnneeCg', FILTER_VALIDATE_INT);
                $quatriemeAnneeCg = filter_input(INPUT_POST, 'quatriemeAnneeCg', FILTER_VALIDATE_INT);
                $moyennesCg[] = $premiereAnneeCg + $deuxiemeAnneeCg + $troisiemeAnneeCg + $quatriemeAnneeCg;
                $resultCG = $functionsNotes->AverageSimulation($moyennesCg);

                $premiereAnneeCfc = filter_input(INPUT_POST, 'premiereAnneeCfc', FILTER_VALIDATE_INT);
                $deuxiemeAnneeCfc = filter_input(INPUT_POST, 'deuxiemeAnneeCfc', FILTER_VALIDATE_INT);
                $troisiemeAnneeCfc = filter_input(INPUT_POST, 'troisiemeAnneeCfc', FILTER_VALIDATE_INT);
                $quatriemeAnneeCfc = filter_input(INPUT_POST, 'quatriemeAnneeCfc', FILTER_VALIDATE_INT);
                $moyennesCfc[] = $premiereAnneeCfc + $deuxiemeAnneeCfc + $troisiemeAnneeCfc + $quatriemeAnneeCfc;
                $resultCG = $functionsNotes->AverageSimulation($moyennesCfc);
            }
            // A FAIRE : s'il y a des valeures dans les input alors faire les moyennes avec ces valeures sinon récupérer dans la base de données les notes

            //resultat CFC
            // var_dump($resultTPI, $resultCBE, $resultCI, $resultCG);
            // var_dump($moyennesCg);
            // var_dump($notesCG);
            $resultatCFC = $functionsNotes->passMarkCFC($resultTPI, $resultCBE, $resultCI, $resultCG);
            echo "Ton resultat final est de " . round($resultatCFC, 1);
        }
        require '../src/view/viewSimulation.php';
    }
}
