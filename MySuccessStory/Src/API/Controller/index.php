<?php

use MySuccessStory\api\Model\Subjects;

require_once '../Model/SqlConnectionClass.php';
require_once '../Model/Sujets.php';
require_once '../Model/functions.php';

// class subjects
$subjects = new Subjects();

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
            if (!empty($_GET['demande']))
            {
                // sépare la chaîne de caractères en un tableau de chaînes de caractères
                $url = explode("/", filter_var($_GET['demande'], FILTER_SANITIZE_URL));
                
                switch ($url[1]) {
                    case 'subjects':

                        // si il n'y a pas de valeur après API/subjects/
                        if (empty($url[2])) {

                            // on récupère un tableau avec les subjects
                            $subjects->getSubjects($db, "SELECT idSubject,s.name,c.name as 'category' from subject s inner join category c on s.idCategory=c.idCategory");

                            // on définit le code d'état de réponse HTTP (la requête a réussi et une ressource a été créée)
                            http_response_code(201);
                        }
                        break;
                        // case 'user':
                        //     if (!empty($url[2])) {
                        //         $mail = $url[2];
                        //         $user->getUserByEmail($db, "SELECT email FROM user WHERE email = $mail");
                        //     } else {
                        //         http_response_code(400);
                        //         throw new Exception("la demande n'est pas valide, vérifiez l'url");
                        //     }
                        //     break;
                    default:

                        // on définit le code d'état de réponse HTTP (syntaxe invalide)
                        http_response_code(400); 
                        throw new Exception("la demande n'est pas valide, vérifiez l'url");
                }
            }
            else
            {
                http_response_code(400);
                throw new Exception("Problème de récupérations de données");
            }
        }
        catch (Exception $e) {
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
    http_response_code(401);
    echo "Error : No Authentification token";
}
