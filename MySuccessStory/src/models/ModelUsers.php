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
        $parts = ModelMain::decryptJwt($token);
        if (isset($parts['email']) && isset($parts['password'])) {
            return self::isValidAccount($parts['email'], $parts['password']);
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
        if ($result && $password == $result->password) return true;
        return false;
    }


    /**
     * Return a token
     * @return ApiValue
     * @author Beaud Rémy <remy.bd@eduge.ch>
     */
    public static function getToken(): ApiValue
    {
        $data = ModelMain::getBody();
        if (isset($data['email']) && isset($data['password'])) {
            if (ModelUsers::isValidAccount($data['email'], $data['password'])) {
                return ModelMain::generateJwt($data['email'], $data['password']);
            }
            return new ApiValue(null, "error : invalid login", "0");
        }
        return new ApiValue(null, "Syntax error : the sent body doesn't contain email and password", "0");
    }


    /**
     * Creates a user
     * @return ApiValue
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function createUser(): ApiValue
    {
        $data = ModelMain::getBody();

        if (!$data) {
            return new ApiValue(null, "Syntax error : the sent body is not a valid JSON object", "0");
        }

        try {
            (new DataBase())->insert(self::TABLE_NAME, $data);
            return new ApiValue(null, "The user has been created");
        } catch (\Exception $e) {
            return new ApiValue(null, $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Shows a user
     * @return ApiValue
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function readUser(): ApiValue
    {
        $data = ModelMain::getBody();

        if (!$data) {
            return new ApiValue(null, "Syntax error : the sent body is not a valid JSON object", "0");
        }
        try {
            $statement = (new DataBase())->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE idUser = " . $data['idUser']);
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
    }

    /**
     * updates a user
     * @return ApiValue
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function updateUser(): ApiValue
    {
        $data = ModelMain::getBody();

        if (!$data) {
            return new ApiValue(null, "Syntax error : the sent body is not a valid JSON object", "0");
        }

        try {
            (new DataBase())->update(self::TABLE_NAME, $data, "idUser = " . $data['idUser']);
            return new ApiValue(null, "The user has been edited");
        } catch (\Exception $e) {
            return new ApiValue(null, $e->getMessage(), $e->getCode());
        }
    }

    /**
     * deletes an user
     * @return ApiValue
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function deleteUser(): ApiValue
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            return new ApiValue(null, "Syntax error : the sent body is not a valid JSON object", "0");
        }
        try {
            (new DataBase())->delete(self::TABLE_NAME, "idUser = " . $data['idUser'])->execute();
            return new ApiValue(null, "The user has been deleted");
        } catch (\Exception $e) {
            return new ApiValue(null, $e->getMessage(), $e->getCode());
        }
    }
}