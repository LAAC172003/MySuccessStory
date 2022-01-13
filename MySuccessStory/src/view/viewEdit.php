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
            <label>Sujet : </label>
            <!-- <input type="text" name="subject" value="<?= $notes[0]->subject ?>" required> -->
            <select name="subject" id="subject">
                <?php
                foreach ($subjects as $subject) {
                    if ($subject->name == $notes[0]->subject) {
                        echo "<option value=$subject->name selected>$subject->name</option>";
                    } else {
                        echo "<option value=$subject->name>$subject->name</option>";
                    }
                }
                ?>
            </select>
            <br>
            <text>Description : </text>
            <!--
                Changer la description

                si on change le sujet changer aussi la description
            -->

            <textarea name="description" required> <?= $notes[0]->description ?> </textarea>
            <br>
            <label>Année : </label>
            <!-- <input type="text" name="year" value="<?= $notes[0]->year ?>" required> -->
            <select name="year" id="year">
                <?php
                for ($i = 0; $i < count($years); $i++) {
                    $year = $years[$i];
                    if ($year->year == $notes[0]->year) {
                        echo " <option value='$year->year' selected>$year->year</option>";
                    } else {
                        echo " <option value='$year->year'>$year->year</option>";
                    }
                }
                ?>
            </select>
            <br>
            <label>Semestre : </label>
            <!-- <input type="number" name="semester" value="<?= $notes[0]->semester ?>" required> -->
            <select id="semester" name="semester">
                <?php
                foreach ($semesters as $semester) {
                    if ($semester == $notes[0]->semester) {
                        echo "<option value=$semester selected>$semester</option>";
                    } else {
                        echo "<option value=$semester>$semester</option>";
                    }
                }
                ?>
            </select>
            <br>

            <input type="reset" class="btn btn-outline-primary" name="delete" value="Effacer">
            <input type="submit" class="btn btn-primary" name="validate" value="Valider">
        </form>
        <br>
        <!-- <a href="voirAnnonce.php?idVehicule=<?php echo $idVehicule; ?>"><button class="btn btn-info">Retour</button></a> -->
    </fieldset>
</body>

</html>