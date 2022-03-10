<?php

namespace MySuccessStory\controllers;


use MySuccessStory\models\ModelNotes;

class ControllerNotes
{
    /**
     * Create a note
     * @return string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function create()
    {
        return json_encode(ModelNotes::createNote());
    }

    /**
     * Read a note
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function read(): bool|string
    {
        return json_encode(ModelNotes::readNote());
    }

    /**
     * Update a note
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function update(): bool|string
    {
        return json_encode(ModelNotes::updateNote());
    }

    /**
     * Delete a note
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function delete(): bool|string
    {
        return json_encode(ModelNotes::deleteNote());
    }
}