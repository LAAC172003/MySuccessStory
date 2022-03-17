<?php

namespace MySuccessStory\controllers;

use MySuccessStory\models\ModelUsers;
use MySuccessStory\models\ModelMain;

class ControllerUsers
{
    public function getDecryptedToken()
    {
        return json_encode(ModelMain::decryptJwt(ModelMain::getAuthorization()->value));
    }

    /**
     * Create a user
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     * @author Beaud Rémy <remy.bd@eduge.ch>
     */
    public function login() : bool|string
    {
        return ModelMain::printJsonValue(ModelUsers::getToken());
    }

    /**
     * Create a user
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function create() : bool|string
    {
        return ModelMain::printJsonValue(ModelUsers::createUser());
    }

    /**
     * Read a user
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function read() : bool|string
    {
        return ModelMain::printJsonValue(ModelUsers::readUser());
    }

    /**
     * Update a user
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function update() : bool|string
    {
        return ModelMain::printJsonValue(ModelUsers::updateUser());
    }

    /**
     * Delete a user
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function delete() : bool|string
    {
        return ModelMain::printJsonValue(ModelUsers::deleteUser());
    }
}