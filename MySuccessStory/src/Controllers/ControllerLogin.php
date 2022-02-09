<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;


class ControllerLogin
{
    /**
     * method to login in the website
     *
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function login()
    {
        $functions = new Functions();

        //if a user is logged
        if (isset($_COOKIE['email']))
        {
            // Redirect the user to home page
            $functions->redirect("");
        }

        //Login
        if (isset($_POST['loginValidate'])) {
            $email = $_POST['email'];

            $pwd = $_POST['pwd'];
            if ($functions->refreshCookie()) {
                //collect the first part (firstname) and the second part (lastname)
                $emailParts = explode(".", $email);
                $login = $functions->curl("http://mysuccessstory/api/login/$emailParts[0]/$emailParts[1]");

                ////////////////////////////////////////////////////////////////
                ////////////SECURISER!!!!!!/////////////////////////////////////////////
                ////////////////////////////////////////////////////////////////

                if ($login == null) {
                    echo "mauvais identifiants";
                } else {
                    for ($i = 0; $i < count($login); $i++) {
                        $user = $login[$i];
                        var_dump($user);
                        if ($user->password != hash("sha256", $pwd . $user->salt)) {
                            echo "mauvais mdp";
                        } else {
                            setcookie("email", $email, time() + 3600);
                            setcookie("password", hash("sha256", $pwd . $user->salt), time() + 3600);

                            // Redirect the user to profile
                            $functions->redirect("");
                        }
                    }
                }
            }
        }

        require '../src/view/viewLogin.php';
    }
}
