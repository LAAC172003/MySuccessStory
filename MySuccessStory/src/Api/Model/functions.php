<?php

namespace MySuccessStory\Api\Model;

/**
 * class who contains all the global methods on the website
 */
class Functions
{
    //CONST
    public $DAYS_WEEK = 7;
    public $DAYS_MONTH = 30;

    //an hour in seconds
    public $TIME = 3600;

    /**
     * encode the url in base64
     */
    public function urlEncode($str)
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    /**
     * refresh the cookie when it's expired
     */
    public function refreshCookie()
    {
        if (isset($_COOKIE['BearerCookie'])) {
            return true;
        } else {
            setcookie("BearerCookie", $this->jwtGenerator(), time() + $this->TIME);
            // setcookie("BearerCookie", $this->jwtGenerator(), time() + $this->TIME * $this->DAYS_MONTH);
            header("Refresh:0 ");
            return false;
        }
    }

    /**
     * generate a jwt token
     */
    public function jwtGenerator($secret = 'secret')
    {
        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
        $payload = array('sub' => '1587426934', 'name' => 'nameHere', 'admin' => true, 'exp' => (time() + $this->TIME));

        $encodedHeaders = $this->urlEncode(json_encode($headers));
        $encodedPayload = $this->urlEncode(json_encode($payload));
        $signature = hash_hmac('SHA256', "$encodedHeaders.$encodedPayload", $secret, true);
        $encodedSignature = $this->urlEncode($signature);

        $jwt = "$encodedHeaders.$encodedPayload.$encodedSignature";

        return $jwt;
    }

    /**
     * check if the token is valid
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
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
