<?php

namespace MySuccessStory\Controllers;

class ControllerHome
{
    /**
     * Home of the website
     *
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function home()
    {
        if (isset($_COOKIE['email'])) {
            echo "Salut " . $_COOKIE['email'] . " ton mot de passe est " . $_COOKIE['password'];
            echo '<form action="" method="post"><input type="submit" name="deco" value="Se deconecter" /></form>';
            // var_dump($_POST['deco']);
            if (isset($_POST['deco']))
            {
                setcookie("email", "", time() - 3600);
                setcookie("password", "", time() - 3600);
            }

            //mieux faire la deco
        } else {
            //afficher connecter / inscription
        }
        require '../src/view/viewHome.php';
    }
}
