<?php

namespace MySuccessStory\controllers;

use MySuccessStory\db\DataBase;
use MySuccessStory\models\ApiValue;
use MySuccessStory\models\ModelMain;
use MySuccessStory\models\ModelUsers;

class ControllerUsers
{
    /**
     * Create a user
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     * @author Beaud Rémy <remy.bd@eduge.ch>
     */
    public function login(): bool|string
    {
        return json_encode(ModelUsers::getToken());
    }

    /**
     * Create a user
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function create(): bool|string
    {
        return json_encode(ModelUsers::createUser());
    }

    /**
     * Read a note
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function read(): bool|string
    {
        return json_encode(ModelUsers::readUser());
    }

    /**
     * Update a note
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function update(): bool|string
    {
        return json_encode(ModelUsers::updateUser());
    }

    /**
     * Delete a note
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function delete(): bool|string
    {
        return json_encode(ModelUsers::deleteUser());
    }
}