<?php

namespace MySuccessStory\api\controller;

use MySuccessStory\api\model\Subject;
use MySuccessStory\api\model\SqlConnectionClass;
use Exception;

require_once "../src/api/model/functions.php";

class index
{
    public static function indexApi($data)
    {
        // class subjects
        $subjects = new Subject();

        // class db
        $db = new SqlConnectionClass(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        // si l'HTTP_BEARER existe
        if (isset($_SERVER['HTTP_BEARER'])) {

            // stock HTTP_BEARER
            $authHeader = $_SERVER['HTTP_BEARER'];

            // si le token jwt est valide
            if (is_jwt_valid($authHeader)) {
                try {
                    // si la demande n'est pas vide
                    if (!empty($data)) {

                        // sépare la chaîne de caractères en un tableau de chaînes de caractères
                        switch ($data) {
                            case 'subjects':
                                // on récupère un tableau avec les subjects
                                $subjects->getSubjects($db, "SELECT idSubject,s.name,c.name AS 'category' FROM subject s INNER JOIN category c ON s.idCategory=c.idCategory");

                                // on définit le code d'état de réponse HTTP (la requête a réussi et une ressource a été créée)
                                http_response_code(201);
                                break;
                            default:
                                // on définit le code d'état de réponse HTTP (syntaxe invalide)
                                http_response_code(400);
                                throw new Exception("la demande n'est pas valide, vérifiez l'url");
                        }
                    } else {

                        // on définit le code d'état de réponse HTTP (syntaxe invalide)
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
                // on définit le code d'état de réponse HTTP (syntaxe invalide)
                http_response_code(400);
                echo "Invalid Token";
            }
        } else {
            // on définit le code d'état de réponse HTTP (access non autor)
            http_response_code(401);
            echo "Error : No Authentification token";
        }
    }
}
