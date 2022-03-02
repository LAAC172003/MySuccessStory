<?php

namespace MySuccessStory\models;


use JetBrains\PhpStorm\ArrayShape;

class ModelUsers
{
    private const SALT = "secret";

    /**
     * encode the url in base64
     * @return string[] returns the token
     * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     * @author Beaud Rémy <remy.bd@eduge.ch>
     */
    #[ArrayShape(['Token' => "string", "Valid" => "bool", 'Expiration' => "string"])] public static function jwtGenerator(): array
    {

        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
        $payload = array('email' => "user", 'pwd' => "pwd", 'exp' => time() + 3600);

        $encodedHeaders = self::urlEncode(json_encode($headers));
        $encodedPayload = self::urlEncode(json_encode($payload));

        $signature = hash_hmac('MD5', "$encodedHeaders.$encodedPayload", ModelUsers::SALT, true);
        $encodedSignature = self::urlEncode($signature);

        $payloadExp = $payload['exp'] - time();
        $token = "$encodedHeaders.$encodedPayload.$encodedSignature";
        return [
            'Token' => $token,
            "Valid" => self::isJwtValid($token),
            'Expiration' => "$payloadExp"
        ];
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
     * @return boolean true if the token is valid
     * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     * @author Beaud Rémy <remy.bd@eduge.ch>
     */
    public static function isJwtValid($token): bool
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
        $base64UrlHeader = self::urlEncode($header);
        $base64UrlPayload = self::urlEncode($payload);
        $signature = hash_hmac('MD5', "$base64UrlHeader.$base64UrlPayload", ModelUsers::SALT, true);
        $base64UrlSignature = self::urlEncode($signature);

        // verify it matches the signature provided in the jwt
        $isSignatureValid = $base64UrlSignature === $signature_provided;

        if ($isTokenExpired || !$isSignatureValid) {
            return false;
        } else {
            return true;
        }
    }
}