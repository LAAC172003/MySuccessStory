<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sujets</title>
</head>

<body>
    <center>
        <h1>Liste de tous les sujets</h1>
        <table class="table">
            <th>Id Sujet</th>
            <th>Sujet</th>
            <th>Categorie</th>
            <?php
            for ($i = 0; $i < count($subjects); $i++) {
                $subject = $subjects[$i];

            ?>
                <tr>
                    <td><?= $subject->idSubject ?></td>
                    <td><?= $subject->name ?></td>
                    <td><?= $subject->category ?></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </center>
</body>

</html>