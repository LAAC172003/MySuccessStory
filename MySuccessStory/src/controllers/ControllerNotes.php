<?php

namespace MySuccessStory\controllers;


use MySuccessStory\models\ModelNotes;

class ControllerNotes
{
    public ModelNotes $modelNotes;

    public function __construct()
    {
        $this->modelNotes = new ModelNotes();
    }

    /**
     * Create a note
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function create(): bool|string
    {
        return json_encode($this->modelNotes->createNote($note));
    }

    /**
     * Read a note
     * @param $idNote
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function read($idNote): bool|string
    {
        return json_encode($this->modelNotes->readNote($idNote));
    }

    /**
     * Update a note
     * @param $idNote
     * @param $note
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function update($idNote,$note): bool|string
    {
        return json_encode($this->modelNotes->updateNote($idNote,$note));
    }

    /**
     * Delete a note
     * @param $idNote
     * @return bool|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function delete($idNote): bool|string
    {
        return json_encode($this->modelNotes->deleteNote($idNote));
    }
}