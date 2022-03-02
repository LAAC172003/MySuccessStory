<?php

namespace MySuccessStory\controllers;


use MySuccessStory\models\ModelNotes;

class ControllerNotes
{

    public function create()
    {
        $notes = new ModelNotes();
        $notes->createNote();
    }

    public function read($subject = "")
    {
        $notes = new ModelNotes();
        return $notes->readNote($subject);
    }

    public function update($subject = "")
    {
        $notes = new ModelNotes();
        return $notes->updateNote($subject);
    }

    public function delete($subject = "")
    {
        $notes = new ModelNotes();
        return $notes->deleteNote($subject);
    }
}