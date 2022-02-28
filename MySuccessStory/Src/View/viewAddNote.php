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
                <input id="note" type="number" name="note" value="<?=$note?>" min="1" max="6" step="0.5" required>
            </div>

            <div>
                <label name="subjects" for="subjects">nom de la matière</label>
                <select id="subjects" name="subjects" required>
                    <?php
                        for ($i = 0; $i < count($subjects); $i++) {
                            $subject = $subjects[$i];
                            echo " <option value='$subject->name'>$subject->name</option>";
                        }
                    ?>
                </select>
            </div>

            <div>
                <label name="year" for="year">année</label>
                <select id="year" name="year" required>
                    <?php
                    for ($i = 0; $i < count($years); $i++) {
                        $year = $years[$i];
                        echo " <option value='$year->year'>$year->year</option>";
                    }

                    ?>
                </select>
            </div>

            <div>
                <label name="semester" for="semester" required>semestre</label>
                <select id="semester" name="semester" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>

            <div>
                <label name="fakeNote">Note fictive</label>
                <input type="checkbox" name="fakeNote" <?php if (isset($_POST["fakeNote"])) { if ($_POST["fakeNote"] == true) { echo "checked"; } } ?>>
            </div>

            <div>
                <input type="submit" name="submit" value="Ajouter">
                <input type="submit" name="submit" value="Annuler">
            </div>
        </form>
    </main>
</body>

</html>