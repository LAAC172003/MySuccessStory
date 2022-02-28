<?php

namespace MySuccessStory\Api\Model;

/**
 * 
 *
 * @author Flavio Soares Rodrigues <flavio.srsrd@eduge.ch>
 */
class Year
{
    //return year
    public static function getYears($db, $sql)
    {
        $getYears = $db->query("$sql");
        $GLOBALS["years"] = $getYears->fetchAll();
        $db->close();
        echo json_encode($GLOBALS["years"], JSON_UNESCAPED_UNICODE);
    }
}
