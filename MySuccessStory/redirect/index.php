<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://apis.google.com/js/platform.js"></script>
    <script type="text/javascript" src="./js/eelauth.js"></script>

    <title>Index Login EEL</title>
</head>

<body>
    <h1>Login</h1>
    <form method="post" action="">
        <div class="text-center">
            <a id="ConnectionButton" class="btn" href="" role="button"><span>Se connecter avec Eduge</span></a>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            // Initialiser l'api people de google
            // en lui passant une call-back pour la mise à jour
            // du status de la personne connectée.
            EELAuth.EELClientInitialize(updateSigninStatus);

            // On enregistre le click sur le bouton de connexion
            $("#ConnectionButton").click(handleAuthClick);

            // Initialise le particle système
            // Particles.init({
            //     selector: '.background',
            //     connectParticles: true,
            //     color: '#56C26B',
            // });
        });

        // si connecté alors on peut envoyer les infos, 
        // sinon on relance la connexion
        function updateSigninStatus(isSignedIn) {
            if (isSignedIn) {
                EELAuth.getUserInfo(onReceiveUserInfo);
            } else {
                EELAuth.signIn();
            }
        }

        function handleAuthClick(event) {
            updateSigninStatus(EELAuth.isSignedIn());
        }

        /** @todo On a une variable globale pour conserver l'email loggé
         *        Après, on va placer l'email du user dans la session
         */
        var loggedEmail = "";

        function onReceiveUserInfo(info) {

            // Affichage les infos de l'utilisateur
            // $("#profilImage").attr("src", info.image);
            // $("#profilName").html(info.lastname);
            $("#profilEmail").html(info.email);

            /* @todo Supprimer cela après avoir mis dans la session */
            loggedEmail = info.email;

            // Ici on ajoute du code ajax afin de récupérer des informations
            // Et on les comparent à la base de données
            $.ajax({
                method: 'POST',
                url: './CheckUserLogin.php',
                //Passe en paramètre l'utilisateur connecté
                data: {
                    'useremail': info.email,
                    // 'username': info.lastname,
                    // 'userurlimage': info.image
                },
                dataType: 'json',
                success: function(data) {
                    var msg = '';
                    switch (data.ReturnCode) {
                        case 0: // tout bon
                            window.location.href = "index.php";
                            break;
                        case 3: // Valider les conditions
                            window.location.href = "conditions.php?user=" + loggedEmail;
                            break;
                        case 4: // user banni
                            window.location.href = "banned.php";
                            break;
                        case 1: // problème paramètres
                        case 2: // problème insertion user
                        case 5: // problème de récupération du statut
                        default:
                            msg = data.Message;
                            break;
                    }
                    if (msg.length > 0) {
                        $('#alert-connection #msg').text(msg);
                        $('#alert-connection').removeClass('d-none');
                        $("#info").html(msg);
                    }
                },
                error: function(jqXHR) {
                    var msg = '';
                    console.log(jqXHR);
                    switch (jqXHR.status) {
                        case 404:
                            msg = "page pas trouvée. 404";
                            break;
                        case 200:
                            msg = "probleme avec json. 200";
                            break;

                    } // End switch
                    if (msg.length > 0) {
                        $('#alert-connection #msg').text(msg);
                        $('#alert-connection').removeClass('d-none');
                        // $("#info").html(msg);
                    }
                }

            });
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>

</body>

</html>