<?php

namespace MySuccessStory\Modele;

class Subjects
{
    public static function getSubjects($db,$sql)
    {
        $getSubjects = $db->query("$sql");
        $result = $getSubjects->fetchAll();
        $db->close();
        return $result;
    }
}
