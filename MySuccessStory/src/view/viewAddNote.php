<?php
/**
* get the subject name and show it in a dropdown list
*
* @param array[object] $subjects  an array of objects
* @author flavio.srsrd@eduge.ch
*/
function showSubject($subjects)
{
    $option = "";

    foreach ($subjects as $subject)
    {
        $option .= "<option value=\"$subject->idSubject\">" . $subject->name . "</option>";
    }
    return $option;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter des notes</title>
</head>

<body>
    <header>
        <h1>Ajouter une note</h1>
    </header>

    <main>
        <form method="post">
            <div>
                <label name="note" for="note">votre note </label>
                <input id="note" type="number" min="1" max="6" step="0.5" name="note" value="<?=$note?>" onchange="verifyNote()">
            </div>

            <div>
                <label name="subjects" for="subjects">nom de la matière</label>
                <select id="subjects" name="subjects">
                    <?= showSubject($subjects); ?>
                </select>
            </div>

            <div>
                <label name="year" for="year">année</label>
                <select id="year" name="year">
                    <option value="1">Première Année</option>
                    <option value="2">Deuxième Année</option>
                    <option value="3">Troisième Année</option>
                    <option value="4">Quatrième Année</option>
                </select>
            </div>

            <div>
                <label name="semester" for="semester">semestre</label>
                <select id="semester" name="semester">
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>

            <div>
                <input type="submit" name="submit" value="Ajouter">
                <input type="submit" name="submit" value="Annuler">
            </div>
        </form>
    </main>

    <footer>
        <p>footer du site</p>
    </footer>

    <script>
        function verifyNote()
        {
            let note = document.getElementById("note");

            if ($note >= 1.0 && $note <= 6.0 && fmod($note, 0.5) == 0)
            {
                
            }
        }
    </script>
</body>

</html>