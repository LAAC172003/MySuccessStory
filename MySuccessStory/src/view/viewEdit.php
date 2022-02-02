<?php
    var_dump($notes[0]->note);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
</head>

<body>
    <fieldset>
        <legend>Modifier le note</legend>
        <form action="" method="POST" enctype="multipart/form-data">

            <label>Note : </label>
            <input type="text" name="note" value="<?= $notes[0]->note ?>" required>
            <br>

            <label>Matière : </label>
            <select name="subject" id="subject">
                <?php

                    // Split the two-dimensional array "subjects" in one dimensional array "subject"
                    foreach ($subjects as $subject)
                    {
                        // Check if the subject name is equal to subject name of notes
                        if ($subject->name == $notes[0]->subject)
                        {
                            echo "<option value=$subject->idSubject selected>$subject->name</option>";
                        }
                        else
                        {
                            echo "<option value=$subject->idSubject>$subject->name</option>";
                        }
                    }
                ?>
            </select>
            <br>

            <label>Année : </label>
            <select name="year" id="year">
                <?php

                    // Split the two-dimensional array "subjects" in one dimensional array "subject"
                    foreach ($years as $year)
                    {
                        // Check if the subject name is equal to subject name of notes
                        if ($year->year == $notes[0]->year)
                        {
                            echo " <option value='$year->idYear' selected>$year->year</option>";
                        }
                        else
                        {
                            echo " <option value='$year->idYear'>$year->year</option>";
                        }
                    }
                ?>
            </select>
            <br>

            <label>Semestre : </label>
            <select id="semester" name="semester">
                <?php
                    foreach ($semesters as $semester)
                    {
                        if ($semester == $notes[0]->semester)
                        {
                            echo "<option value=$semester selected>$semester</option>";
                        } 
                        else
                        {
                            echo "<option value=$semester>$semester</option>";
                        }
                    }
                ?>
            </select>
            <br>

            <input type="reset" class="btn btn-outline-primary" name="delete" value="Effacer">
            <input type="submit" class="btn btn-primary" name="validate" value="Valider">

        </form>
    </fieldset>
</body>

</html>