<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete</title>
</head>
<body>
    <header>
        <h1>Effacer la Note ci-dessous</h1>
    </header>

    <main>
        <!-- Show id -->
        <p>
            ID :
            <?= $note['idNote'] ?>
        </p>

        <!-- Show note -->
        <p>
            Note :
            <?= $note['note'] ?>
        </p>

        <!-- Show the subject -->
        <p>
            Matière :
            <?= $subject['name'] ?>
        </p>

        <!-- Show year -->
        <p>
            Année :
            <?= $note['idYear'] ?>
        </p>

        <!-- Show semester -->
        <p>
            semester :
            <?= $note['semester'] ?>
        </p>

        <p>
            <form method="post">
                <button type="submit" name="submit" value="Delete">Supprimer</button>
                <button type="submit" name="submit" value="Cancel">Annuler</button>
            </form>
        </p>
    </main>
</body>
</html>