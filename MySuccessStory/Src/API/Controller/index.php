<?php

namespace MySuccessStory\api\controller;

use MySuccessStory\Api\Model\Subject;
use MySuccessStory\Api\Model\Year;
use MySuccessStory\Api\Model\User;
use MySuccessStory\Api\Model\Note;
use MySuccessStory\Api\Model\SqlConnection;
use MySuccessStory\Api\Model\Functions;
use Exception;

/**
 * contain all the api methods
 */
class Index
{
    /**
     * start message
     */
    public function apiHome()
    {
        echo "Welcome to the api";
    }

    /**
     * method who contains the api functions
     *
     * @param string $data element to query
     * @param string $firstname beginning of the email
     * @param string $lastname ending of the email
     * @return json return informations in an array in json
     */
    public function apiFunctions($data = "", $firstname = "", $lastname = "")
    {
        //initialize classes
        $functionsSubjects = new Subject();
        $functionsYears = new Year();
        $functionsUsers = new User();
        $functionNotes = new Note();
        $email = "$firstname.$lastname@eduge.ch";
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
                                $response_json = $functionsSubjects->getSubjects($db, "SELECT idSubject,s.name,c.name AS 'category' FROM subject s INNER JOIN category c ON s.idCategory=c.idCategory");
                                //201 Created
                                http_response_code(201);
                                break;
                            case 'year':
                                $response_json = $functionsYears->getYears($db, "SELECT idYear, year FROM year");
                                //201 Created
                                http_response_code(201);
                                break;

                            case 'user':
                                if (!empty($firstname) && !empty($lastname)) {
                                    $response_json = $functionsUsers->user($db, "SELECT email from user where email = '$email'");
                                    http_response_code(201);
                                } else {
                                    $response_json = $functionsUsers->user($db, "SELECT email FROM user");
                                    http_response_code(201);
                                }
                                break;
                            case 'userID':
                                $response_json = $functionsUsers->user($db, "SELECT idUser from user where email = '$email'");

                                http_response_code(201);
                                break;

                            case 'login':
                                ////
                                //revoir propreté code !
                                ////

                                $response_json = $functionsUsers->user(
                                    $db,
                                    "SELECT email, `password`, `salt`
                                    FROM `user`
                                    WHERE email = '$email' AND `password` = (
                                        SELECT `password`
                                        FROM `user`
                                        WHERE email = '$email'
                                    )"
                                );
                                http_response_code(201);
                                break;

                            case 'notes':
                                $response_json = $functionNotes->notes(
                                    $db,
                                    "SELECT `idNote`,`note`, `year`.`year`, `semester`, `subject`.`name` AS 'subject', `subject`.`description`, `user`.`firstname`, `user`.`lastName`
                                    FROM `note`
                                    JOIN `year` ON `note`.`idYear` = `year`.`idYear`
                                    JOIN `subject` ON `subject`.`idSubject` = `note`.`idSubject`
                                    JOIN `user` ON `note`.`idUser` = `user`.`iduser`
                                    WHERE `user`.`email` = '$email'
                                "
                                );
                                http_response_code(201);
                                break;

                            case 'getPhysics':
                                $response_json = $functionNotes->notes(
                                    $db,
                                    $functions->selectQueryCG("Physique", $firstname, $lastname)
                                );
                                http_response_code(201);
                                break;

                            case 'getMaths':
                                $response_json = $functionNotes->notes(
                                    $db,
                                    $functions->selectQueryCG("Mathématique", $firstname, $lastname)
                                );
                                http_response_code(201);
                                break;

                            case 'getEconomy':
                                $response_json = $functionNotes->notes(
                                    $db,
                                    $functions->selectQueryCG("Economie", $firstname, $lastname)
                                );
                                http_response_code(201);
                                break;

                            case 'getEnglish':
                                $response_json = $functionNotes->notes(
                                    $db,
                                    $functions->selectQueryCG("Anglais", $firstname, $lastname)
                                );
                                http_response_code(201);
                                break;

                            case 'getPhysicalEducation':
                                $response_json = $functionNotes->notes(
                                    $db,
                                    $functions->selectQueryCG("Education Physique", $firstname, $lastname)
                                );
                                http_response_code(201);
                                break;

                            case 'getCIENotes':
                                $response_json = $functionNotes->notes(
                                    $db,
                                    "SELECT
                                    note
                                FROM
                                    note
                                JOIN `subject` ON note.idSubject = `subject`.idSubject
                                JOIN user ON note.idUser = user.idUser 
                                WHERE
                                    `subject`.isCIE = TRUE AND user.email = '$email'"
                                );
                                http_response_code(201);
                                break;

                            case 'getCINotes':
                                $response_json = $functionNotes->notes(
                                    $db,
                                    "SELECT
                                        note
                                    FROM
                                        note
                                    JOIN `subject` ON note.idSubject = `subject`.idSubject
                                    JOIN user ON note.idUser = user.idUser 
                                    WHERE
                                        `subject`.isCIE = false AND user.email = '$email'"
                                );
                                http_response_code(201);
                                break;
                            case 'getSubjectsByCategoryCG':
                                $response_json = $functionNotes->notes(
                                    $db,
                                    $functionNotes->getSubjectByCategory("CG")
                                );
                                break;
                                case 'getSubjectsByCategoryCFC':
                                    $response_json = $functionNotes->notes(
                                        $db,
                                        $functionNotes->getSubjectByCategory("CFC")
                                    );
                                    break;
                            default:
                                // 400 Bad Request
                                http_response_code(400);
                                throw new Exception("la demande n'est pas valide, vérifiez l'url");
                        }
                        return $response_json;
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

    /**
     * doesn't work
     */

    public function __call($method, $arguments)
    {
        if ($method == 'api') {

            if (count($arguments) == 0) {
                return call_user_func_array($this->apiHome, array('apiHome'), $arguments);
            } else if (count($arguments) == 1) {
                return call_user_func_array($this->apiFunctions, array('apiFunctions'), $arguments);
            }
        }
    }
}
