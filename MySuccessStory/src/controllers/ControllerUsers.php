<?php

namespace MySuccessStory\controllers;

use MySuccessStory\models\ModelUsers;

class ControllerUsers
{
    public ModelUsers $modelUsers;

    public function __construct()
    {
        $this->modelUsers = new ModelUsers();
    }

    public function test(): string
    {
        return "test";
    }

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
    public function create(): bool|string
    {
        return json_encode($this->modelUsers->createUser());
    }

    /**
     * Read a note
     * @param $idUser
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function read($idUser): bool|string
    {
        return json_encode($this->modelUsers->readUser($idUser));
    }

    /**
     * Update a note
     * @param $idUser
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function update($idUser): bool|string
    {
        return json_encode($this->modelUsers->updateUser($idUser));
    }

    /**
     * Delete a note
     * @param $idUser
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function delete($idUser): bool|string
    {
        return json_encode($this->modelUsers->deleteUser($idUser));
    }
}