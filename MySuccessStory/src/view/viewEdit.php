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
            <legend>Modifier la note</legend>
            <form action="" method="POST" enctype="multipart/form-data">

                <!-- Note -->
                <div>
                    <label>Note : </label>
                    <input type="number" min="1" max="6" step="0.5" name="note" value="<?= $noteById["note"] ?>" required>
                </div>

                <!-- Subject -->
                <div>
                    <label>Matière : </label>
                    <select name="subject" id="subject">
                        <?php
                            foreach ($subjects as $subject)
                            {
                                if ($subject->idSubject == $noteById["idSubject"])
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
                </div>

                <!-- Year -->
                <div>
                    <label>Année : </label>
                    <select name="year" id="year">
                        <?php
                            foreach ($years as $year)
                            {
                                if ($year->idYear == $noteById["idYear"])
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
                </div>

                <!-- Semester -->
                <div>
                    <label>Semestre : </label>
                    <select id="semester" name="semester">
                        <?php
                            foreach ($semesters as $semester)
                            {
                                if ($semester == $noteById["semester"])
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
                </div>

                <!-- Submit and Cancel -->
                <div>
                    <input type="reset" class="btn btn-outline-primary" name="delete" value="Effacer">
                    <input type="submit" class="btn btn-primary" name="validate" value="Valider">
                </div>
            </form>
        </fieldset>
    </body>
</html>