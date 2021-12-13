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
    public function createNewAccount($email, $pwd, $firstName, $lastName)
    {
        $db = new SqlConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $compte = $db->query('INSERT INTO user (email,password,firstName,lastName) VALUES (?,?,?,?)', $email . "@eduge.ch", $pwd, $firstName, $lastName);
    }
    //return all the emails in the database (index.php(api))
   
    //return the user logged (index.php(api))
    
}
