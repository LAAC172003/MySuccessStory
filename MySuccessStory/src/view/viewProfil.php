<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
</head>

<body>
    <h1>Profil</h1>
    <br>
        <table class="table">
            <th>Id note</th>
            <th>note</th>
            <th>Sujet</th>
            <?php
            for ($i = 0; $i < count($notes); $i++) {
                $notes = $note[$i];
            ?>
                <tr>
                    <!-- <td><?= $subject->idSubject ?></td> -->
                    <!-- <td><?= $subject->name ?></td> -->
                    <!-- <td><?= $subject->category ?></td> -->
                </tr>
            <?php
            }
            ?>
        </table>
</body>

</html>