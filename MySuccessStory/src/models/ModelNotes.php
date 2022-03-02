<?php

namespace MySuccessStory\models;

use MySuccessStory\db\DataBase;

class ModelNotes
{
    public Database $db;
    public string $tableName;

    public function __construct()
    {
        $this->db = new DataBase();
        $this->tableName = "notes";
    }

    public function createNote()
    {
        return $this->db->insert("notes", ['note' => 5, 'semester' => 1, 'idUser' => 1, 'idSubject' => 2]);
    }

    public function readNote($subject)
    {
        $statement = $this->db->prepare("SELECT * FROM $this->tableName JOIN subjects on subjects.idSubject=notes.idSubject WHERE subjects.name = '$subject'");
        $statement->execute();
        return json_encode($statement->fetchAll());
    }

    public function updateNote($subject)
    {
        return $this->db->update($this->tableName, ['note' => 4]);
    }

    public function deleteNote($subjects ="")
    {
        return $this->db->delete($this->tableName,["(SELECT name from subjects where name = $subjects)"]);
    }
}