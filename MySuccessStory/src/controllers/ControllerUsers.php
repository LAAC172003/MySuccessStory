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

    public function test($user,$pwd): string
    {
        $user = "test2@gmail.com";
        $pwd = "pwd";
       if ($user == "test2@gmail.com" AND $pwd == "pwd"){
           return "Salut $user";
       }
       else{
           return "Nah";
       }
    }


    public static function token(): bool|string
    {
        return json_encode(ModelUsers::jwtGenerator());
    }

    public function create(): bool|string
    {
        return json_encode($this->modelUsers->createUser());
    }

    public function read($idUser): bool|string
    {
        return json_encode($this->modelUsers->readUser($idUser));
    }

    public function update($idUser): bool|string
    {
        return json_encode($this->modelUsers->updateUser($idUser));
    }

    public function delete($idUser): bool|string
    {
        return json_encode($this->modelUsers->deleteUser($idUser));
    }
}