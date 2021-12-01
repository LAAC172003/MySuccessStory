<?php

use MySuccessStory\Api\Model\Subjects;

require_once '../Model/SqlConnectionClass.php';
require_once '../Model/Sujets.php';
require_once '../Model/functions.php';

$subjects = new Subjects();
$db = new SqlConnectionClass(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (isset($_SERVER['HTTP_BEARER'])) {
    $authHeader = $_SERVER['HTTP_BEARER'];
    if (is_jwt_valid($authHeader)) {
        try {
            if (!empty($_GET['demande'])) {
                $url = explode("/", filter_var($_GET['demande'], FILTER_SANITIZE_URL));
                switch ($url[1]) {
                    case 'subjects':
                        if (empty($url[2])) {
                            $subjects->getSubjects($db, "SELECT idSubject,s.name,c.name as 'category' from subject s inner join category c on s.idCategory=c.idCategory");
                            http_response_code(201);
                        }
                        break;
                    default:
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
    http_response_code(401);
    echo "Error : No Authentification token";
}
