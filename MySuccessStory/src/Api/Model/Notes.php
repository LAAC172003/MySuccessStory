<?php

// function getNotes($db)
// {
//     $getNotes = $db->query("");
//     $GLOBALS['notes'] = $getNotes->fetchAll();
//     $db->close();
// }
// function getSubjectsByCategory($category, $db)
// {
//     $getNotes = $db->query("SELECT idSubject,s.name,c.name as 'category' from subject s inner join category c on s.idCategory=c.idCategory where c.name = ?", array($category));
//     $GLOBALS['subjects'] = $getNotes->fetchAll();
//     $db->close();
// }
// function getSubjectById($id, $db)
// {
//     $getNotes = $db->query("SELECT idSubject,s.name,c.name as 'category' from subject s inner join category c on s.idCategory=c.idCategory where s.idSubject = ?", array($id));
//     $GLOBALS['subjects'] = $getNotes->fetchAll();
//     $db->close();
// }
