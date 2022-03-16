<?php

namespace MySuccessStory\models;

use MySuccessStory\db\DataBase;
use PDO;

class ModelNotes
{
    const TABLE_NAME = "notes";

    /**
     * Get the body
     * @return object|array
     * @author Jordan Folly
     */

    /**
     * Create a note
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function createNote()
    {
        $token = ModelMain::getAuthorization();
        if (ModelUsers::isValidTokenAccount($token)) {
            $data = ModelMain::getBody();
            $idUser = self::getIdUser($token);
            $data['idUser'] = $idUser;

            $subject = $data['idSubject'];
            $idSubject = (new DataBase())->select("SELECT idSubject from subjects where name = '$subject'");
            $data['idSubject'] = $idSubject->idSubject;
            if (!$idSubject) return "Le sujet n'est pas correct";
            if ($data['semester'] > 2) return "Il n'y a que 2 semestres";
            if ($data['note'] > 6) return "la note maximale est de 6";

            try {
                (new DataBase())->insert(self::TABLE_NAME, $data);
                return new ApiValue($data, "The note has been added");
            } catch (\Exception $e) {
                return new ApiValue(null, $e->getMessage(), $e->getCode());
            }
        }
        return "invalid token";
    }

    public static function getIdUser($token)
    {
        $email = ModelUsers::getEmailToken($token);

        $statementIdUser = (new DataBase())->select("SELECT idUser from users where email = '$email'");
        return $statementIdUser->idUser;
    }

    /**
     * Read a note
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public
    static function readNote(): ApiValue|string
    {
        $token = ModelMain::getAuthorization();
        if (ModelUsers::isValidTokenAccount($token)) {
            $data = ModelMain::getBody();
            $idUser = self::getIdUser($token);
            try {
                $orderBy = "ASC";

                if (isset($data["Order"])) {
                    if (strtoupper($data["Order"]) == "ASC" || strtoupper($data["Order"]) == "DESC") {
                        $orderBy = strtoupper($data["Order"]);
                    }
                }
                $statementResult = (new DataBase())->select("SELECT * FROM " . self::TABLE_NAME . " WHERE idUser = $idUser");

                if (isset($data["Sort"])) {
                    switch ($data["Sort"]) {
                        case "Notes":
                            $statement = (new DataBase())->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE idUser = $idUser ORDER BY note " . $orderBy);
                            $statement->execute();
                            $statementResult = $statement->fetchAll(PDO::FETCH_ASSOC);
                            break;
                    }
                } else {
                    $statement = (new DataBase())->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE idUser = $idUser");
                    $statement->execute();
                    $statementResult = $statement->fetchAll(PDO::FETCH_ASSOC);
                }
                if ($statementResult) {
                    return new ApiValue($statementResult);
                } else {
                    echo 33;
                    return new ApiValue();
                }
            } catch (\Exception $exists) {
                return new ApiValue(null, $exists->getMessage(), $exists->getCode());
            }
        }
        return "invalid token";
    }

    /**
     * Update a note
     * @return ApiValue|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public
    static function updateNote(): ApiValue|string
    {
        $token = ModelMain::getAuthorization();
        if (ModelUsers::isValidTokenAccount($token)) {
            $data = ModelMain::getBody();
            $idUser = self::getIdUser($token);
            try {
                (new DataBase())->update(self::TABLE_NAME, $data, "idUser = $idUser");

                return new ApiValue(null, "The note has been edited");
            } catch (\Exception $e) {
                return new ApiValue(null, $e->getMessage(), $e->getCode());
            }
        }
        return "invalid token";
    }

    /**
     * Delete a note
     * @return ApiValue|string
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public
    static function deleteNote(): ApiValue|string
    {
        $token = ModelMain::getAuthorization();
        if (ModelUsers::isValidTokenAccount($token)) {
            $idUser = self::getIdUser($token);
            try {
                (new DataBase())->delete(self::TABLE_NAME, "idUser = $idUser")->execute();
                return new ApiValue(null, "The note has been deleted");
            } catch (\Exception $e) {
                return new ApiValue(null, $e->getMessage(), $e->getCode());
            }
        }
        return "invalid token";
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
    public
    static function cfcAverage(float $tpi, float $ci, float $cg, float $cbe): float
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
    public
    static function average(array $notes): float|int
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
    public
    static function calculate(array $notes): float|int
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
    public
    static function cbeNote(float $english, float $economy, float $maths, float $physics): float|int
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
    public
    static function ciNote(float $cie, float $school): float
    {
        return 0.8 * $school + 0.2 * $cie;
    }
}