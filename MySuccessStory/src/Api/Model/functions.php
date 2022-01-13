<?php

namespace MySuccessStory\Api\Model;

/**
 * class who contains all the global methods on the website
 */
class Functions
{
    #region Constants
    public const DAYS_WEEK = 7;
    public const DAYS_MONTH = 30;
    #endregion

    /**
     * an hour in seconds
     */
    private const TIME = 3600;

    /**
     * encode the url in base64
     * @param string $str
     * @return string return the encoded code in base64
     * @link author https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
     */
    public function urlEncode($str)
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    /**
     * refresh the cookie when it's expired, creates a new one and refresh the page
     * @return bool true if the cookie don't have to be recreated
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     * @author Folly Jordan <ekoue-jordan.fllsd@eduge.ch>
     */
    public function refreshCookie()
    {
        if (isset($_COOKIE['BearerCookie'])) {
            return true;
        } else {
            setcookie("BearerCookie", $this->jwtGenerator(), time() + 3600);
            // setcookie("BearerCookie", $this->jwtGenerator(), time() + $this->TIME * $this->DAYS_MONTH);
            header("Refresh:0");
            return false;
        }
    }

    /**
     * generates a token
     * @param string $secret key of the hash value
     * @return string the jwt token
     * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
     */
    public function jwtGenerator($secret = 'secret')
    {
        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
        $payload = array('sub' => '1587426934', 'name' => 'nameHere', 'admin' => true, 'exp' => time() + 3600);

        $encodedHeaders = $this->urlEncode(json_encode($headers));
        $encodedPayload = $this->urlEncode(json_encode($payload));

        $signature = hash_hmac('SHA256', "$encodedHeaders.$encodedPayload", $secret, true);
        $encodedSignature = $this->urlEncode($signature);

        $jwt = "$encodedHeaders.$encodedPayload.$encodedSignature";

        return $jwt;
    }

    /**
     * check if the token is valid
     * @param string $jwt
     * @param string $secret
     * @return boolean if the token is valid - "jwtTest" is always a valid token
     * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
     */
    public function isJwtValid($jwt, $secret = 'secret')
    {
        if ($jwt == "jwtTest") {
            return true;
        }

        // split the jwt
        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature_provided = $tokenParts[2];

        // check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
        $expiration = json_decode($payload)->exp;
        $isTokenExpired = ($expiration - time()) < 0;

        // build a signature based on the header and payload using the secret
        $base64UrlHeader = $this->urlEncode($header);
        $base64UrlPayload = $this->urlEncode($payload);
        $signature = hash_hmac('SHA256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = $this->urlEncode($signature);

        // verify it matches the signature provided in the jwt
        $isSignatureValid = ($base64UrlSignature === $signature_provided);

        if ($isTokenExpired || !$isSignatureValid) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Send a cURL request to the API
     *
     * @param string $url URL of the API
     * @return mixed result of the request
     * @author Flavio Soares Rodrigues <flavio.srsrd@eduge.com>
     */
    function curl($url)
    {
        $bearer = $_COOKIE['BearerCookie'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$url",
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
        return json_decode(curl_exec($curl));
    }

    /**
     * Select a CG note
     *
     * @param string $subject
     * @param string $firstname beginning of the email
     * @param string $lastname ending of the email
     * @return string query
     */
    function selectQueryCG($subject, $firstname, $lastname)
    {
        return "SELECT
        `note`
    FROM
        `note`
    JOIN `subject` ON `note`.idSubject = `subject`.idSubject
    WHERE
        idCategory =(
        SELECT
            `idCategory`
        FROM
            category
        WHERE
            `name` = 'cg'
    ) AND idUser =(
        SELECT
            idUser
        FROM
            `user`
        WHERE
            `user`.email = '$firstname.$lastname@eduge.ch'
    ) AND subject.idSubject =(
        SELECT
            idSubject
        FROM
            `subject`
        WHERE
            `name` = '$subject'
    )";
    }
}
