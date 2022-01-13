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
            $resultTPI = 5.7;

            //CBE

            $resultCBE =  $functionsNotes->noteCBE($resultEnglish, $resultEconomy, $resultMaths, $resultPhysics);

            //CI
            $resultCI = $functionsNotes->noteCI($functionsNotes->passMark($CIENotes), $functionsNotes->passMark($CINotes));

            //CG
            $notesCG[] = $resultEnglish + $resultEconomy + $resultMaths + $resultPhysics + $resultPhysicalEducation;
            $resultCG = $functionsNotes->calculate($notesCG);

            //resultat CFC
            $resultatCFC = $functionsNotes->passMarkCFC($resultTPI, $resultCBE, $resultCI, $resultCG);
            return "Ton resultat final est de " . round($resultatCFC, 1);
        }
        require '../src/view/viewSimulation.php';
    }
}
