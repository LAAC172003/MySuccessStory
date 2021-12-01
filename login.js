/*
  Auteur : Robin Laborde | Duparc Yohan | Gering Nathan 
  Nom du projet : Projet de gestion des demandes de commandes
  Description : Projet Html/Css/Php permetant de gerer les commandes du CFPT
  Version : 03
  Classe : I.DA.P3C | I.DA.P3B
*/

$(document).ready(function() {
    // Initialiser l'api people de google
    // en lui passant une call-back pour la mise à jour
    // du status de la personne connectée.
    EELAuth.EELClientInitialize(updateSigninStatus);

    // On enregistre le click sur le bouton de connexion
    $("#ConnectionButton").click(handleAuthClick);

    // Initialise le particle système
    /*Particles.init({
      selector: ".background",
      connectParticles: true,
      color: "#56C26B",
    });*/
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
    $("#profilImage").attr("src", info.image);
    $("#profilName").html(info.lastname);
    $("#profilEmail").html(info.email);

    /* @todo Supprimer cela après avoir mis dans la session */
    loggedEmail = info.email;

    // Ici on ajoute du code ajax afin de récupérer des informations
    // Et on les comparent à la base de données
    $.ajax({
        method: "POST",
        url: "?controller=users&action=verifyLogin",
        //Passe en paramètre l'utilisateur connecté
        data: {
            useremail: info.email,
        },
        dataType: "json",
        success: function(data) {},
        error: function(jqXHR) {
            var msg = "";
            switch (jqXHR.status) {
                case 404:
                    msg = "page pas trouvée. 404";
                    break;
                case 200:
                    msg = "probleme avec json. 200";
                    break;
            } // End switch
            if (msg.length > 0) {
                $("#alert-connection #msg").text(msg);
                $("#alert-connection").removeClass("d-none");
                // $("#info").html(msg);
            }
        },
    });

    location.href = "?controller=users&action=displayVerifToken";
}