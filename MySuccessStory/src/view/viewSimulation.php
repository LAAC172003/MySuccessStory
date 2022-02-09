<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulation</title>
    <style>
        table,
        td {
            border: 1px solid #333;
            margin-top: 50px;
        }

        thead,
        tfoot {
            background-color: #333;
            color: #fff;
        }
    </style>
</head>

<body>
    <h1>Page Année</h1>
    <form method="post" action="">
        <table>
            <thead>
                <tr>
                    <th colspan="4">CG</th>
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($subjects as $subject)
                {
                    if ($subject->category == "CG")
                    {
                ?>
                        <tr>
                            <td><?= $subject->name ?></td>
                            <td><input type="number" name="noteSemester1_<?= $subject->idSubject ?>" /></td>
                            <td><input type="number" name="noteSemester2_<?= $subject->idSubject ?>" /></td>
                            <td>moyenne</td>
                        </tr>
                <?php
                    }
                }


                ?>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <th colspan="4">CFC</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($subjects as $subject)
                {
                    if ($subject->category == "CFC")
                    {
                ?>
                        <tr>
                            <td><?= $subject->name ?></td>
                            <?php
                            for ($i = 1; $i <= 2; $i++)
                            {
                            ?>
                                <td><input type="number" name="noteSemester<?= $i ?>_<?= $subject->idSubject ?>" value="<?php
                                foreach ($notesDb as $note)
                                {
                                    if ($subject->name == $note->subject)
                                    {
                                        echo $note->note;
                                    }
                                    else
                                    {
                                        echo $_POST["noteSemester$i" . "_" . $subject->idSubject];
                                    }
                                }
                                ?>" /></td>
                            <?php
                            }
                            ?>
                            <td>moyenne</td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <input type="submit" />
    </form>
</body>

</html>