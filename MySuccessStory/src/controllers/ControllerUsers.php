<?php

namespace MySuccessStory\controllers;

use MySuccessStory\models\ModelUsers;

class ControllerUsers
{
    /**
     * Create a token
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function token(): bool|string
    {
        return json_encode(ModelUsers::jwtGenerator());
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
     * @param $idUser
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