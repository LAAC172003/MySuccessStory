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

    public function create()
    {
        return json_encode($this->modelNotes->createNote());
    }

    public function read($idNote)
    {
        return json_encode($this->modelNotes->readNote($idNote));
    }

    public function update($idNote)
    {
        return json_encode($this->modelNotes->updateNote($idNote));
    }

    public function delete($idNote)
    {
        return json_encode($this->modelNotes->deleteNote($idNote));
    }
}