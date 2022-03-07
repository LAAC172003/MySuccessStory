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
        // $statement = $this->db->prepare("SELECT * FROM $this->tableName JOIN subjects on subjects.idSubject=notes.idSubject WHERE subjects.name = '$subject'");
        // $statement->execute();

        // return json_encode
        //     (
        //         new ApiValue
        //             (
        //                 $statement->fetchAll(),
        //                 $statement->errorInfo[2] ?? null,
        //                 $statement->errorCode ?? null
        //             )
        //     );
    }


    /**
     * Calculates the pass mark to success the CFC
     *
     * @param float $tpi
     * @param float $cbe
     * @param float $ci
     * @param float $cg
     * @return float returns result
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function cfcAverage(float $tpi, float $ci, float $cg, float $cbe): float
    {
        return 0.3 * $tpi + 0.2 * $cbe + 0.3 * $ci + 0.2 * $cg;
    }

    /**
     * calculates the pass mark of 8 values
     *
     * @param array $notes
     * @return float|int returns the result of the average of all the notes
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function average(array $notes): float|int
    {
        if ($notes[0] == null) {
            return $result = 4;
        } else {
            $result = 0.0;
            for ($i = 0; $i < count($notes[0]); $i++) {
                $result += $notes[0][$i]->note;
            }
            return ($result / count($notes[0]));
        }
    }

    /**
     * calculates an average
     *
     * @param array $notes
     * @return float|int result
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function calculate(array $notes): float|int
    {
        if ($notes[0] == null) {
            return $result = 4;
        } else {
            $result = $notes[0] / 5;
            return round($result * 2) / 2;
        }
    }

    /**
     * calculates the average of the 4 subjects to success the CBE
     *
     * @param float $english
     * @param float $economy
     * @param float $maths
     * @param float $physics
     * @return float|int returns the rounded result
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function cbeNote(float $english, float $economy, float $maths, float $physics): float|int
    {

        $result = (round($english * 2) / 2 + round($economy * 2) / 2 + round($maths * 2) / 2 + round($physics * 2) / 2) / 4;
        return round($result * 2) / 2;
    }

    /**
     * calculates the note of the ci
     *
     * @param float $cie
     * @param float $school
     * @return float return the result of the CI
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function ciNote(float $cie, float $school): float
    {
        return $result = 0.8 * $school + 0.2 * $cie;

    }
}