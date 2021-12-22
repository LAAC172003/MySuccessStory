<?php

namespace MySuccessStory\Api\Model;

/**
 * 
 *
 * @author Flavio Soares Rodrigues <flavio.srsrd@eduge.ch>
 */
class Period
{
    //return period
    public static function getPeriods($db, $sql)
    {
        $getPeriods = $db->query("$sql");
        $GLOBALS["periods"] = $getPeriods->fetchAll();
        $db->close();
        echo json_encode($GLOBALS["periods"], JSON_UNESCAPED_UNICODE);
    }
}
