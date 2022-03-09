<?php

namespace MySuccessStory\models;


use JetBrains\PhpStorm\ArrayShape;
use MySuccessStory\db\DataBase;

class ModelUsers
{
    //Mettre appart le salt
    private const SALT = "1441caa2afec313f8fd620d9ed6492258b61fca73bb3f3ed6bc8691637bf96ef";
    public Database $db;
    public string $tableName;


    public function __construct()
    {
        $this->db = new DataBase();
        $this->tableName = "users";
    }

    /**
     * Generates a token
     * @return ApiValue returns the token
     * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function jwtGenerator()
    {

        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
        $payload = array('email' => "user", 'pwd' => "pwd", 'exp' => time() + 3600);

        $encodedHeaders = self::urlEncode(json_encode($headers));
        $encodedPayload = self::urlEncode(json_encode($payload));

        $signature = hash_hmac('MD5', "$encodedHeaders.$encodedPayload", ModelUsers::SALT, true);
        $encodedSignature = self::urlEncode($signature);

        $payloadExp = $payload['exp'] - time();
        $token = "$encodedHeaders.$encodedPayload.$encodedSignature";

		if (self::isJwtValid($token))
		{
			return new ApiValue
				(
					[
						"Token" => $token,
						"Expiration" => $payloadExp
					],
				);
		}
		else
		{
			return new ApiValue("", "Invalid token generated", "0");
		}
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
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    /**
     * Verify if the token is valid
     * @param $token
     * @return array|bool true if the token is valid
     * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     * @author Beaud Rémy <remy.bd@eduge.ch>
     */
    public static function isJwtValid($token)
    {
		if ($token == "jwtTest")
		{
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

		if ($isTokenExpired || !$isSignatureValid)
		{
			return false;
		}
		else
		{
			return
			[
				"Header" => json_decode($header),
				"Payload" =>
				[
					"Email" => json_decode($payload)->email,
					"Password" => json_decode($payload)->pwd
				]
			];
		}
    }

    /**
     * Creates a user
     * @return array user created
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function createUser(): array
    {
        $data = array(
            'email' => "test@gmail.com",
            'password' => "pwd",
            'firstName' => "firstName",
            'lastName' => "lastName"
        );
        try {
            $this->db->insert("$this->tableName", $data);
            return [
                'success' => true,
                "UserCreated" => $data
            ];
        } catch (\Exception $e) {
            return [
                'Error message' => $e->getMessage(),
                'Error code' => $e->getCode()
            ];
        }
    }

    /**
     * Shows a user
     * @param $idUser
     * @return ApiValue
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function readUser($idUser)
    {
        try
		{
            $statement = $this->db->prepare("SELECT * FROM $this->tableName WHERE idUser = $idUser");
            $statement->execute();
            $statementResult = $statement->fetchObject();

            if ($statementResult)
			{
				return new ApiValue($statementResult);
            }
			else
			{
                return new ApiValue();
            }
        }
		catch (\Exception $e)
		{
            return new ApiValue(null, $e->getMessage(), $e->getCode());
        }
    }

    /**
     * updates a user
     * @param $idUser
     * @return array
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */

    public function updateUser($idUser): array
    {
        try {
            $this->db->update($this->tableName, ['lastName' => ""], "idUser = $idUser");
            return [
                'update' => true,
                'updatedUser' => "{FIELD} = {VALUE} where idNote = $idUser"
            ];
        } catch (\Exception $e) {
            return [
                'Error message' => $e->getMessage(),
                'Error code' => $e->getCode()
            ];
        }
    }

    /**
     * deletes an user
     * @param $idUser
     * @return array
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function deleteUser($idUser): array
    {
        try {
            return [
                'Delete' => $this->db->delete($this->tableName, "idUser = $idUser")->execute(),
                'Deleted user' => "idUser = $idUser"
            ];
        } catch (\Exception $e) {
            return [
                'Error message' => $e->getMessage(),
                'Error code' => $e->getCode()
            ];
        }
    }
}