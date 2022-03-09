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
    public static function read($idUser): bool|string
    {
        return json_encode(ModelUsers::readUser($idUser));
    }

    /**
     * Update a note
     * @param $idUser
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function update($idUser): bool|string
    {
        return json_encode(ModelUsers::updateUser($idUser));
    }

    /**
     * Delete a note
     * @param $idUser
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function delete($idUser): bool|string
    {
        return json_encode(ModelUsers::deleteUser($idUser));
    }
}