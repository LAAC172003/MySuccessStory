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
        $data = array(
            'note' => 4,
            'semester' => 1,
            'idUser' => 6,
            'idSubject' => 2
        );
        try {
            $this->db->insert("$this->tableName", $data);
            return [
                'Success' => true,
                "Note created" => $data
            ];
            //return new ModelApiValue($data, "La note a bien été ajoutée");
        } catch (\Exception $e) {
            return [
                'Error message' => $e->getMessage(),
                'Error code' => $e->getCode()
            ];
            //return new ModelApiValue("", $e->getMessage(), $e->getCode());
        }
    }

    public function readNote($idNote)
    {
        try {
            $statement = $this->db->prepare("SELECT * FROM $this->tableName WHERE idNote = $idNote");
            $statement->execute();
            $statementResult = $statement->fetchObject();
            if ($statementResult) {
                return [
                    'Success' => true,
                    'Note' => $statementResult
                ];
            } else {
                return ['Success' => false];
            }

        } catch (\Exception $e) {
            return [
                'Error message' => $e->getMessage(),
                'Error code' => $e->getCode()
            ];
        }
    }

    public function updateNote($idNote)
    {
        try {
            $this->db->update($this->tableName, ['note' => 1], "idNote = $idNote");
            return [
                'Update' => true,
                'Updated note' => '{FIELD} = {VALUE} where idNote = {IDNOTE}'
            ];
        } catch (\Exception $e) {
            return [
                'Error message' => $e->getMessage(),
                'Error code' => $e->getCode()
            ];
        }
    }

    public function deleteNote($idNote)
    {
        try {
            return [
                'Success' => $this->db->delete($this->tableName, "idNote = $idNote")->execute(),
                'Deleted note' => "idNote = $idNote"
            ];
        } catch (\Exception $e) {
            return [
                'Error message' => $e->getMessage(),
                'Error code' => $e->getCode()
            ];
        }
    }
}