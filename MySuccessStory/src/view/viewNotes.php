<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes</title>
</head>

<body>
    <h1>Notes</h1>
    <br>
    <form method="GET">
        <label>Trier par :
            <select name="Order">
                <?php
                    session_start();

                    foreach ($notes[0] as $field => $value)
                    {
                ?>

                <option value="<?= $field ?>" <?php if (isset($_GET["Order"])) { if ($_GET["Order"] == $field) { echo "selected"; } } ?>> <?= $field ?> </option>

                <?php
                    }
                ?>
            </select>
        </label>

        <br>

        <label>Ordre croissant :
            <input type="radio" name="isASC" <?php if (isset($_GET["isASC"])) { if ($_GET["isASC"] != "DESC") { echo "checked"; } } else { echo "checked"; } ?> value="ASC">
        </label>
        <label>Ordre décroissant :
        <input type="radio" name="isASC" <?php if (isset($_GET["isASC"])) { if ($_GET["isASC"] == "DESC") { echo "checked"; } } ?> value="DESC">
        </label>
        <br>

        <input type="submit" value="submit">
    </form>

    <p>
        <a href="http://mysuccessstory/addNote">Ajouter une note</a>
    </p>

    <a href="<?= AddGetParameter("Period=semester1"); ?>">Premier semestre</a>

    <table class="table">
        <th>Matières</th>
        <?php
            if (isset($_SESSION["fakeNotes"]))
            {
                foreach ($_SESSION["fakeNotes"] as $fakeNote)
                {
                    array_push($notes, $fakeNote);
                }
            }

            $counts = array();

            foreach ($notes as $note)
            {
                if (!array_key_exists($note->subject, $counts))
                {
                    $counts[$note->subject] = 0;
                }

                $counts[$note->subject]++;
            }

            for ($i=1; $i <= max($counts); $i++)
            {
        ?>
        <th></th>
        <?php
            }

            ksort($counts);
        ?>
        <th>Moyenne</th>

        <?php
            $averages = array();

            foreach ($counts as $subject => $count)
            {
        ?>
        <tr>
            <td><?= $subject ?></td>
            <?php
                $numberTd = 0;
                $subjectNotes = array();
                foreach ($notes as $note)
                {
                    if ($note->subject == $subject)
                    {
                        $numberTd++;
                        array_push($subjectNotes, $note->note);
            ?>
            <td <?php if (property_exists($note, "fake")) { echo 'style="color: cornflowerblue"'; } ?>><?= $note->note; ?></td>
            <?php
                    }
                }

                while ($numberTd < max($counts))
                {
                    $numberTd++;
            ?>
            <td></td>
            <?php
                }
            ?>
            <td>
            <?php
                array_push($averages, array_sum($subjectNotes) / count($subjectNotes));
                echo end($averages);
            ?>
            </td>
        </tr>

        <?php
            }
        ?>
    </table>
    <p>Moyenne du semestre : <?= array_sum($averages) / count($averages); ?></p>
</body>

</html>