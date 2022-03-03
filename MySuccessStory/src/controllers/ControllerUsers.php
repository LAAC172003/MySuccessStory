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

    public static function token()
    {
        return json_encode(ModelUsers::jwtGenerator());
    }

    public function create()
    {
        return json_encode($this->modelUsers->createUser());
    }

    public function read($idUser = 0)
    {
        return json_encode($this->modelUsers->readUser($idUser));
    }

    public function update($idUser = 0)
    {
        return json_encode($this->modelUsers->updateUser($idUser));
    }

    public function delete($idUser = 0)
    {
        return json_encode($this->modelUsers->deleteUser($idUser));
    }
}