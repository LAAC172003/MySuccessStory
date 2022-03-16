<?php

namespace MySuccessStory\models;

class ModelMain
{
    const SALT = "1441caa2afec313f8fd620d9ed6492258b61fca73bb3f3ed6bc8691637bf96ef";
    const EXPIRATION_TIME = 3600;


    /**
     * returns the body
     * @author Beaud Rémy <remy.bd@eduge.ch>
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function getBody()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data) return $data;
        return null;
    }

    /**
     * Returns the token
     * @return string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     * @author Beaud Rémy <remy.bd@eduge.ch>
     */
    public static function getAuthorization(): string
    {
        $tokens = apache_request_headers();
        return explode(" ", $tokens['Authorization'])[1];
    }

    /**
     * Generates a token
     * @return ApiValue returns the token
     * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     * @author Beaud Rémy <remy.bd@eduge.ch>
     */
    public static function generateJwt(string $email, string $password): ApiValue
    {
        $headers = array("alg" => "HS256", "typ" => "JWT");
        $payload = array("email" => $email, "password" => $password, "expiration" => time() + self::EXPIRATION_TIME);
        $encodedHeaders = self::urlEncode(json_encode($headers));
        $encodedPayload = self::urlEncode(json_encode($payload));
        $signature = hash_hmac("MD5", "$encodedHeaders.$encodedPayload", self::SALT, true);
        $encodedSignature = self::urlEncode($signature);
        $token = "$encodedHeaders.$encodedPayload.$encodedSignature";
        return new ApiValue(
            [
                "token" => $token,
                "expiration" => self::EXPIRATION_TIME
            ]
        );
    }

    /**
     * decode the url in base64
     * @param string $token
     * @return array return the decoded jwt
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     * @author Beaud Rémy <remy.bd@eduge.ch>
     */
    public static function decryptJwt(string $token): array
    {
        $tokenParts = explode(".", $token);
        return array(
            "headers" => json_decode(base64_decode($tokenParts[0])),
            "payload" => json_decode(base64_decode($tokenParts[1])),
            "signature" => $tokenParts[2]
        );
    }

    /**
     * encode the url in base64
     * @param string $str
     * @return string return the encoded code in base64
     * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function urlEncode(string $str): string
    {
        return rtrim(strtr(base64_encode($str), "+/", "-_"), "=");
    }
}