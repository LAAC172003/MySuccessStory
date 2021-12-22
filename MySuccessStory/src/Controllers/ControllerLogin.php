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
                // $login = $functions->curl("http://mysuccessstory/api/login/$emailParts[0]/$emailParts[1]");

                $bearer = $_COOKIE['BearerCookie'];
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "http://mysuccessstory/api/login/$emailParts[0]/$emailParts[1]",
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
                $login = json_decode(curl_exec($curl));

                ////////////////////////////////////////////////////////////////
                ////////////SECURISER!!!!!!/////////////////////////////////////////////
                ////////////////////////////////////////////////////////////////
                var_dump($login);
                if ($login == null) {
                    echo "mauvais identifiants";
                } else {
                    for ($i = 0; $i < count($login); $i++) {
                        $user = $login[$i];
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
