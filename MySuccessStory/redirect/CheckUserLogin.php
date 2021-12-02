<?php
/**
 * @description : fichier check si le user existe.
 * @version : 1.0.0
 * @since : 13.12.18
 * @author : Cuthbert Sébastien, Bytyci Qendrim
 * @copyright : Entreprise Ecole CFPT-I © 2019
 */

// require_once $_SERVER['DOCUMENT_ROOT'].'/php/includes/incAll/inc.all.php';

// Nécessaire lorsqu'on retourne du json
header('Content-Type: application/json');

// Je récupère les paramètres
$email = "";
if (isset($_POST['useremail'])){
    $email = filter_input(INPUT_POST, 'useremail', FILTER_SANITIZE_EMAIL);
    
    // Récupération de l'email de l'utilisateur connecté pour le mettre en session
    ESession::setEmail($email);
}

$name = "";
if (isset($_POST['username']))
    $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

// $url = "";
// if (isset($_POST['userurlimage'])){
//     $url = filter_input(INPUT_POST, 'userurlimage', FILTER_SANITIZE_STRING);

//     // Récupération de l'image de profil de l'utilisateur connecté pour le mettre en session
//     ESession::setUrlImage($url);
// }

$user = null;

if (strlen($email) > 0 && strlen($name) > 0 && strlen($url) > 0)
{
    // Faire le job pour le email
    if (EUserHelper::UserExists($email) === false)
    {
        $usr = new EUser($email,$name,$url);
        if (EUserHelper::CreateUser($usr) === false)
        {
            // Si j'arrive ici, c'est pas bon
            echo '{ "ReturnCode": 2, "Message": "Impossible de créer l\'utilisateur."}';
            exit();    
        }
    }
    else
    {
        $u = new EUser($email,$name,$url);

        // Test pour mettre à jour la photo de profil si la photo de profil de la SESSION
        // est différente de celle qui est stocké en base de donnée
        if (EUserHelper::updateUserPic($u) == true)
        {
            // @brief Controle si les conditions d'utilisation sont validées
            if (EUserHelper::UserConditions($email) == false)
            {
                echo '{ "ReturnCode": 3, "Message": ""}'; 
                exit();
            }
            // Si les conditions sont accéptés, on traite la situation si un utilisateur est banni ou pas
            else{
                $user = EUserHelper::GetUserByEmail($email);
                $bann = $user->bann;
                if($bann == 1){
                    echo '{ "ReturnCode": 4, "Message": "Votre compte a été banni"}';     
                    exit();
                }
                elseif ($bann==0){
                    echo '{ "ReturnCode": 0, "Message": ""}'; 
                    exit();
                }
                else{
                    echo '{ "ReturnCode": 5, "Message": "Impossible de récupérer le statut"}'; 
                    exit();
                }
            }
            exit();
        }
    }
    // OK, redirection sur la page d'accueil
    echo '{ "ReturnCode": 0, "Message": ""}';

    // Envoie d'email à un administrateur pour le mettre au courant d'une nouvelle inscription
    if(sendEmailNewUser($users) === false){
        echo '{ "ReturnCode": 1, "Message" : Problème de mail. Contactez le support" }';
        exit();
    }
    exit();
}

// Si j'arrive ici, c'est pas bon
echo '{ "ReturnCode": 1, "Message": "Il manque des paramètres"}';
?>