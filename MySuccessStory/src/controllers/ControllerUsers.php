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

    public function test()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

        // Instantiate DB & connect

        // Get raw posted data
        $data = json_decode(file_get_contents("php://input"));
        $modelUsers = new ModelUsers();
        $modelUsers->email = $data->email;
        $modelUsers->password = $data->password;
        $modelUsers->firstName = $data->firstName;
        $modelUsers->lastName = $data->lastName;

        // Create post
        if ($modelUsers->test()) {
            echo json_encode(
                array('message' => ' Created')
            );
        } else {
            echo json_encode(
                array('message' => ' Not Created')
            );
        }
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