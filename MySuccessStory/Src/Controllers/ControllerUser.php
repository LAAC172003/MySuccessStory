<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;
use MySuccessStory\Api\Model\User;

/**
 * Class who contains the methods for the user
 */
class ControllerUser
{
    /**
     * method to register the user in the website
     *
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function register()
    {
        //variables
        $functions = new Functions();
        $users = new User();

        @$pwd = $_POST["pwd"];
        @$pwd2 = $_POST["pwd2"];
        @$validate = $_POST["validate"];
        @$email = $_POST["email"];
        @$firstName = $_POST["firstName"];
        @$lastName = $_POST["lastName"];
        @$error = "";
        $salt = rand(1, 100000);

        //if a user is logged
        if (isset($_COOKIE['email']))
        {
            // Redirect the user to home page
            $functions->redirect("");
        }

        //check if all the fields are fill with informations
        if (isset($validate))
        {
            if (empty($firstName))  echo $erreur = "prenom laissé vide!";
            elseif (empty($lastName))  echo $erreur = "nom laissé vide!";
            elseif (empty($email)) echo $erreur = "Email laissé vide!";
            elseif (empty($pwd)) echo $erreur = "Mot de passe laissé vide!";
            elseif ($pwd != $pwd2) echo $erreur = "Mots de passe non identiques!";
            else
            {
                //collect the first part (firstname) and the second part (lastname)
                $emailParts = explode(".", $email);

                //collect the data in json
                if ($functions->refreshCookie())
                {
                    $curl = curl_init();
                    $bearer = $_COOKIE['BearerCookie'];

                    curl_setopt_array($curl, array
                    (
                        CURLOPT_URL => "http://mysuccessstory/api/user/$emailParts[0]/$emailParts[1]",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            "Bearer: $bearer",
                            'Authorization: Basic'
                        ),
                    ));

                    $compte = json_decode(curl_exec($curl));
                    var_dump($compte, $_COOKIE);

                    //check if the account already exists
                    if ($compte != null)
                    {
                        echo $error = "Login existe déjà!";
                    }
                    else
                    {
                        echo getType(hash("sha256", $pwd, $salt));
                        $users->CreateNewAccount($email, hash("sha256", $pwd . $salt), $salt, $firstName, $lastName);
                        header("Location:http://mysuccessstory/login");
                    }
                }
            }
        }
        require '../src/view/viewRegister.php';
    }

    /**
     * Method to edit the user informations
     * 
     * @author Flavio Soares Rodrigues <flavio.srsrd@eduge.ch>
    */
    public function showUserAccountInformation()
    {
        $functions = new Functions();

        // Redirect to the home page if not logged
        $functions->redirectIfNotLogged();

        require '../src/view/viewMyAccount.php';
    }

    /**
     * Method to edit the user informations
     * 
     * @author Flavio Soares Rodrigues <flavio.srsrd@eduge.ch>
    */
    public function edit()
    {
        
    }

    /**
     * Method to delete user informations and data
     * 
     * @author Flavio Soares Rodrigues <flavio.srsrd@eduge.ch>
    */
    public function delete()
    {
        # code...
    }
}
