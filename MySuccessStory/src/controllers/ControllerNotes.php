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
    public static function create($note, $semester, $idUser, $idSubject)
    {
        return json_encode(ModelNotes::createNote($note, $semester, $idUser, $idSubject));
    }

    /**
     * Read a note
     * @param $idNote
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function read($idNote): bool|string
    {
        return json_encode(ModelNotes::readNote($idNote));
    }

    /**
     * Update a note
     * @param $idNote
     * @param $note
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function update($idNote,$note): bool|string
    {
        return json_encode(ModelNotes::updateNote($idNote, $note));
    }

    /**
     * Delete a note
     * @param $idNote
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function delete($idNote): bool|string
    {
        return json_encode(ModelNotes::deleteNote($idNote));
    }
}