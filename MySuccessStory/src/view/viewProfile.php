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
    <form method="GET">
        <label>Trier par :
            <select name="Order">
                <!-- <option value="<?php  ?>"><?php  ?></option> -->
                <option value="note">note</option>
                <option value="subject">sujet</option>
                <!-- <option value="">moyenne</option> -->
            </select>
        </label>

        <input type="submit" value="submit">
    </form>

    <p>
        <a href="http://mysuccessstory/addNote">Ajouter une note</a>
    </p>
    
    <table class="table">
        <th>Numéro</th>
        <th>note</th>
        <th>sujet</th>
        <th>description</th>
        <th>année</th>
        <th>semestre</th>

        <?php
            $numero = 0;
            foreach ($notes as $note)
            {
                $numero += 1;
        ?>
                <tr>
                    <td><?=$numero?></td>
                    <td><?=$note->note?></td>
                    <td><?=$note->subject?></td>
                    <td><?=$note->description?></td>
                    <td><?=$note->year?></td>
                    <td><?=$note->semester?></td>
                    <td>
                        <a href="http://mysuccessstory/editNote?idNote=<?= $note->idNote ?>">Modifier</a>
                    </td>
                    <td>
                        <a href="http://mysuccessstory/deleteNote?idNote=<?=$note->idNote ?>">Supprimer</a>
                    </td>
                </tr>
        <?php } ?>

    </table>
</body>

</html>