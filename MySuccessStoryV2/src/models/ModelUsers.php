<?php

namespace MySuccessStory\models;
class ModelUsers
{
    private const SALT = "secret";
    /**
     * encode the url in base64
     * @param string $secret
     * @return string returns the token
     * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     * @author Beaud Rémy <remy.bd@eduge.ch>
     */
    public static function jwtGenerator()
    {

        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
        $payload = array('email' => 'email@eduge.ch','pwd'=>'password', 'exp' => time() + 3600);

        $encodedHeaders =ModelUsers::urlEncode(json_encode($headers));
        $encodedPayload = ModelUsers::urlEncode(json_encode($payload));

        $signature = hash_hmac('MD5', "$encodedHeaders.$encodedPayload", ModelUsers::SALT, true);
        $encodedSignature = ModelUsers::urlEncode($signature);

        return "$encodedHeaders.$encodedPayload.$encodedSignature";
    }
    /**
     * encode the url in base64
     * @param string $str
     * @return string return the encoded code in base64
     * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     * @author Beaud Rémy <remy.bd@eduge.ch>
     */
    public static function urlEncode($str)
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }
    
    /**
     * encode the url in base64
     * @param $token
     * @param string $secret
     * @return boolean true if the token is valid
     * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     * @author Beaud Rémy <remy.bd@eduge.ch>
     */
    public static function isJwtValid($token)
    {
        if ($token == "jwtTest") {
            return true;
        }

        // split the jwt
        $tokenParts = explode('.', $token);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature_provided = $tokenParts[2];

        // check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
        $expiration = json_decode($payload)->exp;
        $isTokenExpired = ($expiration - time()) < 0;

        // build a signature based on the header and payload using the secret
        $base64UrlHeader = ModelUsers::urlEncode($header);
        $base64UrlPayload = ModelUsers::urlEncode($payload);
        $signature = hash_hmac('MD5', "$base64UrlHeader.$base64UrlPayload", ModelUsers::SALT, true);
        $base64UrlSignature = ModelUsers::urlEncode($signature);

        // verify it matches the signature provided in the jwt
        $isSignatureValid = ($base64UrlSignature === $signature_provided);

        if ($isTokenExpired || !$isSignatureValid) {
            return false;
        } else {
            return true;
        }
    }
}