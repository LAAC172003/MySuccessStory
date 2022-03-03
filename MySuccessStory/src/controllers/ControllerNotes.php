<?php

namespace MySuccessStory\controllers;


use MySuccessStory\models\ModelNotes;

class ControllerNotes
{
    public ModelNotes $modelNotes;
    
    public function create()
    {

        return json_encode($this->modelNotes->createNote());
    }

    public function read($idNote)
    {
        $notes = new ModelNotes();
        return json_encode($this->modelNotes->readNote($idNote));
    }

    public function update($idNote)
    {
        $notes = new ModelNotes();
        return json_encode($this->modelNotes->updateNote($idNote));
    }

    public function delete($idNote)
    {
        $notes = new ModelNotes();
        return json_encode($this->modelNotes->deleteNote($idNote));
    }
}