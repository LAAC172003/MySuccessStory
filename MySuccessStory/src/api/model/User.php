<?php

namespace MySuccessStory\Api\Model;
//class who contains all the functions that are related to the user
class User
{
    //return a user using its email (index.php(api))
    public static function user($db, $sql)
    {
        $user = $db->query("$sql");
        $GLOBALS["user"] = $user->fetchAll();
        if (count($GLOBALS['user']) > 0) {
            $db->close();
            echo json_encode($GLOBALS["user"], JSON_UNESCAPED_UNICODE);
        } else {
            echo "Vous n'avez pas renseigné le bon email !";
        }
    }
    //create a new user
    public function createNewAccount($email, $pwd, $salt, $firstName, $lastName, $entryYear = 2000, $exitYear = 2001)
    {
        $db = new SqlConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $compte = $db->query('INSERT INTO user (email,password,salt,firstName,lastName,entryYear, exitYear) VALUES (?,?,?,?,?,?,?)', $email . "@eduge.ch", $pwd, $salt, $firstName, $lastName, $entryYear, $exitYear);
    }

    /**
     * Get User Information
     *
     * @author Flavio Soares Rodrigues <flavio.srsrd@eduge.ch>
    */
    public function getUserInformation($idUser)
    {
        $db = new SqlConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $user = $db->query(
            "SELECT `idUser`, `email`, `password`, `firstName`, `lastName`, `entryYear`, `exitYear` FROM `user` WHERE `idUser` = $idUser");
        return $user->fetchAll(json_encode($user, JSON_UNESCAPED_UNICODE))[0];
    }

    public function updateUser($idUser, $firstName, $lastName, $password, $entryYear, $exitYear)
    {
        $db = new SqlConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->query(
            "CALL updateUser($idUser, $firstName, $lastName, $password, $entryYear, $exitYear)"
        );
    }

    /* $sql = "UPDATE utilisateurs SET mdp = :mdp, salt = :salt WHERE idUtilisateur = :idUtilisateur";
            $update = $pdo->prepare($sql);

            $salt = bin2hex(random_bytes(10));
            $mdp = hash("sha256", $mdp . $salt);

            $update->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
            $update->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $update->bindParam(':salt', $salt, PDO::PARAM_STR); */
}
