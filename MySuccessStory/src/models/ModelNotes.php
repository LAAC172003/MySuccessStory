<?php

namespace MySuccessStory\models;

use MySuccessStory\db\DataBase;

class ModelNotes
{
    const TABLE_NAME = "notes";

    /**
     * Create a note
     * @param $note
     * @param $semester
     * @param $idUser
     * @param $idSubject
     * @return ApiValue
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function createNote($note, $semester, $idUser, $idSubject): ApiValue
    {
        $data = array
        (
            'note' => $note,
            'semester' => $semester,
            'idUser' => $idUser,
            'idSubject' => $idSubject
        );

        try {
            (new DataBase())->insert(self::TABLE_NAME, $data);
            return new ApiValue($data, "La note a bien été ajoutée");
        } catch (\Exception $e) {
            return new ApiValue(null, $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Read a note
     * @param $idNote
     * @return ApiValue
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function readNote($idNote): ApiValue
    {
        try {
            $statement = (new DataBase())->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE idNote = $idNote");
            $statement->execute();
            $statementResult = $statement->fetchObject();

            if ($statementResult) {
                return new ApiValue($statementResult);
            } else {
                return new ApiValue();
            }
        } catch (\Exception $e) {
            return new ApiValue(null, $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update a note
     * @param $idNote
     * @param $note
     * @return array
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function updateNote($idNote, $note): array
    {
        try {
            (new DataBase())->update(self::TABLE_NAME, ['note' => $note], "idNote = $idNote");
            return
                [
                    'Update' => true,
                    'Updated note' => "note = $note WHERE idNote = $idNote"
                ];
        } catch (\Exception $e) {
            return
                [
                    'Error message' => $e->getMessage(),
                    'Error code' => $e->getCode()
                ];
        }
    }

    /**
     * Delete a note
     * @param $idNote
     * @return array
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function deleteNote($idNote): array
    {
        try {
            return
                [
                    'Success' => (new DataBase())->delete(self::TABLE_NAME, "idNote = $idNote")->execute(),
                    'Deleted note' => "idNote = $idNote"
                ];
        } catch (\Exception $e) {
            return
                [
                    'Error message' => $e->getMessage(),
                    'Error code' => $e->getCode()
                ];
        }
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
    public static function cfcAverage(float $tpi, float $ci, float $cg, float $cbe): float
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
    public static function average(array $notes): float|int
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
    public static function calculate(array $notes): float|int
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
    public static function cbeNote(float $english, float $economy, float $maths, float $physics): float|int
    {
        $result = (round($english * 2) / 2 + round($economy * 2) / 2 + round($maths * 2) / 2 + round($physics * 2) / 2) / 4;
        return round($result * 2) / 2;
    }

    /**
     * calculates the note of the ci
     * @param float $cie
     * @param float $school
     * @return float return the result of the CI
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function ciNote(float $cie, float $school): float
    {
        return 0.8 * $school + 0.2 * $cie;
    }
}