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
    <form method="post" action="">
        <table>
            <thead>
                <tr>
                    <th colspan="3">CG</th>
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($subjectsByCG as $subject) {
                    if ($subject->category == "CG") {
                ?>
                        <tr>
                            <td><?= $subject->name ?></td>
                            <td><input type="number" name="note<?= $subject->name ?>" /></td>
                            <td name="moyenne<?= $subject->name ?>" value="<?= $subject->name?>">moyenne</td>
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
                    <th colspan="3">CFC</th>
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($subjectsByCG as $subject) {
                    if ($subject->category == "CFC") {
                ?>
                        <tr>
                            <td><?= $subject->name ?></td>
                            <td><input type="number" name="note<?= $subject->name ?>" /></td>
                            <td name="moyenne<?= $subject->name ?>" value="<?= $subject->name?>">moyenne</td>
                        </tr>
                <?php
                    }
                }


                ?>
            </tbody>
        </table>
    </form>
</body>

</html>