<?php

namespace MySuccessStory\models;

use MySuccessStory\db\DataBase;
use MySuccessStory\exception\ApiException;

class ModelUsers
{
    const TABLE_NAME = "users";

    /**
     * Return a token
     * @param $token
     * @return bool
     * @author Beaud Rémy <remy.bd@eduge.ch>
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function isValidTokenAccount($token): bool
    {
        $email = self::getEmailToken($token);
        $password = self::getPasswordToken($token);

        if ($email != null && $password != null) {
            return self::isValidAccount($email, $password);
        }
        return false;
    }

    /**
     * Return a token
     * @param $email
     * @param $password
     * @return bool
     * @author Beaud Rémy <remy.bd@eduge.ch>
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function isValidAccount($email, $password): bool
    {
        $statement = (new DataBase())->prepare("SELECT password from " . self::TABLE_NAME . " where email = '$email'");
        $statement->execute();
        $result = $statement->fetchObject();
        if (password_verify($password, $result->password)) return true;
        return false;
    }


    /**
     * Return a token
     * @author Beaud Rémy <remy.bd@eduge.ch>
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function getToken(): ApiValue|string
    {
        $data = ModelMain::getBody();
        if (isset($data['email']) && isset($data['password'])) {
            if (ModelUsers::isValidAccount($data['email'], $data['password'])) {
                return ModelMain::generateJwt($data['email'], $data['password']);
            }
            return "invalid user";
        }
        return "the sent body doesn't contain email and password";

    }


    /**
     * Creates a user
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function createUser()
    {
        $data = ModelMain::getBody();
        try {
            $data['password'] = password_hash($data['password'], CRYPT_SHA256);
            (new DataBase())->insert(self::TABLE_NAME, $data);
            return new ApiValue(null, "The user has been created");
        } catch (\Exception $e) {
            return new ApiValue(null, $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Returns the email
     * @param $token
     * @return string|null
     * @author Beaud Rémy <remy.bd@eduge.ch>
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function getEmailToken($token): ?string
    {
        $parts = ModelMain::decryptJwt($token);
        if (isset($parts['payload']->email)) return $parts['payload']->email;
        return "invalid token";
    }

    /**
     * Returns the password
     * @param $token
     * @return string|null
     * @author Beaud Rémy <remy.bd@eduge.ch>
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function getPasswordToken($token): ?string
    {
        $parts = ModelMain::decryptJwt($token);
        if (isset($parts['payload']->password)) return $parts['payload']->password;
        return null;
    }

    /**
     * Shows a user
     * @return ApiValue
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     * @author Beaud Rémy <remy.bd@eduge.ch>
     *
     */
    public static function readUser(): ApiValue
    {
        $token = ModelMain::getAuthorization();
        if (self::isValidTokenAccount($token)) {
            $email = self::getEmailToken($token);
            try {
                $statement = (new DataBase())->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE email = '" . $email . "'");
                $statement->execute();
                $statementResult = $statement->fetchObject();
                if ($statementResult) {
                    return new ApiValue($statementResult);
                } else {
                    return new ApiValue();
                }
            } catch (\Exception $e) {
                return new ApiValue(null, $e->getMessage(), $e->getCode());
            }
        } else {
            return new ApiValue(null, "invalid token");
        }
    }

    /**
     * updates a user
     * @return ApiValue
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     * @author Beaud Rémy <remy.bd@eduge.ch>
     */
    public static function updateUser(): ApiValue
    {
        $token = ModelMain::getAuthorization();
        if (self::isValidTokenAccount($token)) {
            $email = self::getEmailToken($token);
            $data = ModelMain::getBody();
            if (isset($data['password'])) $data['password'] = password_hash($data['password'], CRYPT_SHA256);
            try {
                (new DataBase())->update(self::TABLE_NAME, $data, "email = '$email'");
                return new ApiValue(null, "The user has been edited");
            } catch (\Exception $e) {
                return new ApiValue(null, $e->getMessage(), $e->getCode());
            }
        } else {
            return new ApiValue(null, "invalid token");
        }
    }

    /**
     * deletes an user
     * @return ApiValue
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function deleteUser(): ApiValue
    {
        $token = ModelMain::getAuthorization();
        if (self::isValidTokenAccount($token)) {
            $email = self::getEmailToken($token);
            try {
                (new DataBase())->delete(self::TABLE_NAME, "email= '$email'")->execute();
                return new ApiValue(null, "The user has been deleted");
            } catch (\Exception $e) {
                return new ApiValue(null, $e->getMessage(), $e->getCode());
            }
        } else {
            return new ApiValue(null, "invalid token");
        }
    }
}