<?php

namespace MySuccessStory\api\controller;

use MySuccessStory\Api\Model\Subject;
use MySuccessStory\Api\Model\User;
use MySuccessStory\Api\Model\Note;
use MySuccessStory\Api\Model\SqlConnection;
use MySuccessStory\Api\Model\Functions;
use Exception;

//class who contains all the api methods
class Index
{
    public function apiHome()
    {
        echo "Welcome to the api";
    }

    /** 
     * description .........................................
     * 
     * @param   string 
     * @param   string 
     * @param   string 
     * 
     * @return 
     */
    public function apiFunctions($data = "", $prenom = "", $nom = "")
    {
        //initialize classes
        $functionsSubjects = new Subject();
        $functionsUsers = new User();
        $functionNotes = new Note();

        $functions = new Functions();
        $db = new SqlConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if (isset($_SERVER['HTTP_BEARER'])) {
            $authHeader = $_SERVER['HTTP_BEARER'];

            //check if the bearer token is valid
            if ($functions->isJwtValid($authHeader)) {
                try {
                    if (!empty($data)) {
                        switch ($data) {
                            case 'subjects':
                                $functionsSubjects->getSubjects($db, "SELECT idSubject,s.name,c.name AS 'category' FROM subject s INNER JOIN category c ON s.idCategory=c.idCategory");
                                //201 Created
                                http_response_code(201);
                                break;
                            case 'user':
                                if (!empty($prenom) && !empty($nom)) {
                                    $functionsUsers->user($db, "SELECT email from user where email = '$prenom.$nom@eduge.ch'");
                                    http_response_code(201);
                                } else {
                                    $functionsUsers->user($db, "SELECT email FROM user");
                                    http_response_code(201);
                                }
                                break;
                            case 'login':
                                ////
                                //revoir propreté code !
                                ////
                                $email = "$prenom.$nom@eduge.ch";
                                $functionsUsers->user(
                                    $db,
                                    "SELECT email, `password`,`salt`
                                    FROM `user`
                                    WHERE email = '$email' AND `password` = (                           
                                        SELECT `password`
                                        FROM `user`
                                        WHERE email = '$email'
                                    )
                                ");
                                http_response_code(201);
                                break;
                            case 'notes':
                                $functionNotes->notes(
                                    $db,
                                    "SELECT note, period.year, period.semester, subject.name AS subject, user.firstname, user.lastName
                                    FROM note
                                    JOIN period ON note.idPeriod = period.idPeriod
                                    JOIN `subject` ON subject.idSubject = note.idSubject
                                    JOIN user ON note.idUser = user.iduser WHERE user.email = '$prenom.$nom@eduge.ch'
                                ");
                                http_response_code(201);
                                break;
                            default:
                                //400 Bad Request
                                http_response_code(400);
                                throw new Exception("la demande n'est pas valide, vérifiez l'url");
                        }
                    } else {
                        http_response_code(400);
                        throw new Exception("Problème de récupérations de données");
                    }
                } catch (Exception $e) {
                    $erreur = [
                        "message" => $e->getMessage(),
                        "code" => http_response_code(400)
                    ];
                    echo json_encode($erreur, JSON_UNESCAPED_UNICODE);
                }
            } else {
                http_response_code(400);
                echo "Invalid Token";
            }
        } else {
            // 401 Unauthorized
            http_response_code(401);
            echo "Error : No Authentification token";
        }
    }

    public function __call($method, $arguments)
    {

        //ne marche pas
        if ($method == 'api') {

            if (count($arguments) == 0) {
                return call_user_func_array($this->apiHome, array('apiHome'), $arguments);
            } else if (count($arguments) == 1) {
                return call_user_func_array($this->apiFunctions, array('apiFunctions'), $arguments);
            }
        }
    }
}
