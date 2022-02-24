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
    public function simulationYear()
    {
        session_start();
        if (!isset($_COOKIE['email']))
        {
            header('Location:http://mysuccessstory/');
        }
        $functions = new Functions();

        // Note object
        $functionsNotes = new Note();

        // Redirect to the home page if not logged
        $functions->redirectIfNotLogged();

        // Split the email in 2
        $emailParts = explode(".", $_COOKIE['email']);
        if ($functions->refreshCookie())
        {
            $english = $functions->curl("http://mysuccessstory/api/getEnglish/$emailParts[0]/$emailParts[1]");
            $economy = $functions->curl("http://mysuccessstory/api/getEconomy/$emailParts[0]/$emailParts[1]");
            $maths = $functions->curl("http://mysuccessstory/api/getMaths/$emailParts[0]/$emailParts[1]");
            $physics = $functions->curl("http://mysuccessstory/api/getPhysics/$emailParts[0]/$emailParts[1]");
            $physicalEducation = $functions->curl("http://mysuccessstory/api/getPhysicalEducation/$emailParts[0]/$emailParts[1]");
            $CIE = $functions->curl("http://mysuccessstory/api/getCIENotes/$emailParts[0]/$emailParts[1]");
            $CI = $functions->curl("http://mysuccessstory/api/getCINotes/$emailParts[0]/$emailParts[1]");
            $notesDb = $functions->curl("http://mysuccessstory/api/getNotes/$emailParts[0]/$emailParts[1]/idNote/ASC");

            $subjects = $functions->curl("http://mysuccessstory/api/subjects");

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

            foreach ($subjects as $subject)
            {
                if ($subject->category == "CFC")
                {
                    for ($i = 1; $i <= 2; $i++)
                    {
                        if (isset($_POST["noteSemester$i" . "_" . $subject->idSubject]))
                        {
                            if ($_POST["noteSemester$i" . "_" . $subject->idSubject] != '')
                            {
                                $notes[$i - 1][$subject->idSubject] = filter_input(INPUT_POST, "noteSemester$i" . "_" . $subject->idSubject, FILTER_VALIDATE_INT);
                            }
                            else
                            {
                                $notes[$i - 1][$subject->idSubject] = null;
                            }
                        }
                        else
                        {
                            $notes[$i - 1][$subject->idSubject] = null;
                        }
                    }
                }
                else if ($subject->category == "CG")
                {
                    for ($i = 1; $i <= 2; $i++)
                    {
                        if (isset($_POST["noteSemester$i" . "_" . $subject->idSubject]))
                        {
                            if ($_POST["noteSemester$i" . "_" . $subject->idSubject] != '')
                            {
                                $notes[$i - 1][$subject->idSubject] = filter_input(INPUT_POST, "noteSemester$i" . "_" . $subject->idSubject, FILTER_VALIDATE_INT);
                            }
                            else
                            {
                                $notes[$i - 1][$subject->idSubject] = null;
                            }
                        }
                        else
                        {
                            $notes[$i - 1][$subject->idSubject] = null;
                        }
                    }
                }
            }

            var_dump($notesDb);

            $_SESSION["notesYear"] = $notes;
            /* var_dump($_POST);
            var_dump($notes);
            var_dump($_SESSION);*/

            //resultat CFC
            $resultatCFC = $functionsNotes->passMarkCFC($resultTPI, $resultCBE, $resultCI, $resultCG);
            echo "Ton resultat final est de " . round($resultatCFC, 1);
        }
        require '../src/view/viewSimulationYear.php';
    }
    public function simulationSemester()
    {
        require '../src/view/viewSimulationSemester.php';
    }
}
