/*
  Auteur : Robin Laborde | Duparc Yohan | Gering Nathan 
  Nom du projet : Projet de gestion des demandes de commandes
  Description : Projet Html/Css/Php permetant de gerer les commandes du CFPT
  Version : 03
  Classe : I.DA.P3C | I.DA.P3B
*/

var EELAuth = (function() {

    /** @brief Contient la call-back lorsque le statut de connexion change. */
    var onStatusChanged;

    return {
        /**
         * @brief Cette fonction est appelée pour charger l'api Google People
         */
        EELClientInitialize: function(OnStatusChanged) {
            onStatusChanged = OnStatusChanged;
            // Load the API client and auth2 library
            gapi.load('client:auth2', EELAuth.initEELClient);
        },
        /**
         * @brief Initialize l'api people
         * @returns
         */
        initEELClient: function() {
            // Enter an API key from the Google API Console:
            //  https://console.developers.google.com/apis/credentials
            var apiKey = 'AIzaSyA_q-mxItDldvnhW2fBKjDCODuHsjo60tU';
            // var apiKey = 'AIzaSyCFgP0BwqSxT0wPRlkP6Wdt0J3z9NnSd-8';
            //Enter the API Discovery Docs that describes the APIs you want to
            //access. In this example, we are accessing the People API, so we load
            //Discovery Doc found here: https://developers.google.com/people/api/rest/
            var discoveryDocs = ["https://people.googleapis.com/$discovery/rest?version=v1"];
            //Enter a client ID for a web application from the Google API Console:
            //  https://console.developers.google.com/apis/credentials?project=_
            //In your API Console project, add a JavaScript origin that corresponds
            //  to the domain where you will be running the script.
            var clientId = '';
            // var clientId = '705206525398-1ceppiebmo8fgign0fu6k86hliva9b6c.apps.googleusercontent.com';
            //Enter one or more authorization scopes. Refer to the documentation for
            //the API or https://developers.google.com/people/v1/how-tos/authorizing
            //for details.
            var scopes = 'https://www.googleapis.com/auth/userinfo.profile';
            //Le domaine sur lequel l'api va se diriger pour établir la connexion
            var domain = 'eduge.ch';

            gapi.client.init({
                apiKey: apiKey,
                discoveryDocs: discoveryDocs,
                clientId: clientId,
                scope: scopes,
                hosted_domain: domain
            }).then(function() {
                // Listen for sign-in state changes.
                gapi.auth2.getAuthInstance().isSignedIn.listen(onStatusChanged);
            });
        },
        /**
         * Est-ce qu'un utilisateur est loggé
         * @returns True si loggé, autrement false
         */
        isSignedIn: function() {
            return gapi.auth2.getAuthInstance().isSignedIn.get();
        },
        /**
         * @brief 	Cette fonction execute le processus de login.
         */
        signIn: function() {
            gapi.auth2.getAuthInstance().signIn();
        },

        revokeAllScopes: function() {
            gapi.auth2.getAuthInstance().signOut();
            gapi.auth2.getAuthInstance().disconnect();
        },
        /**
         * @brief 
         * @returns
         */
        getUserInfo: function(OnReceiveInfo) {
            gapi.client.people.people.get({
                resourceName: 'people/me',
                personFields: "emailAddresses"
            }).then(function(resp) {

                // var lastname = (resp.result.names.length > 0) ? resp.result.names[0].familyName : "";
                // var firstname = (resp.result.names.length > 0) ? resp.result.names[0].givenName : "";
                var email = (resp.result.emailAddresses.length > 0) ? resp.result.emailAddresses[0].value : "";
                // var image = (resp.result.photos.length > 0) ? resp.result.photos[0].url : "";
                // var locale = (resp.result.locales.length > 0) ? resp.result.locales[0].value : "";
                var obj = {
                    // 'lastname': lastname,
                    // 'firstname': firstname,
                    'email': email
                        // 'image': image,
                        // 'locale': locale
                };
                OnReceiveInfo.call(this, obj);
            });
        },
    };
}());