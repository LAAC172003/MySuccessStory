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
        //if a user is logged
        if (isset($_COOKIE['email'])) {
            header("Location:http://mysuccessstory/");
        }
        $functions = new Functions();
        //Login 

        if (isset($_POST['loginValidate'])) {
            $email = $_POST['email'];

            $pwd = $_POST['pwd'];
            if ($functions->refreshCookie()) {
                //collect the first part (firstname) and the second part (lastname)
                $emailParts = explode(".", $email);
                $login = $functions->curl("http://mysuccessstory/api/login/$emailParts[0]/$emailParts[1]");
                // var_dump($emailParts);
                // var_dump($login);

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
                            header("Location:http://mysuccessstory/");
                        }
                    }
                }
            }
        }

        require '../src/view/viewLogin.php';
    }
}
