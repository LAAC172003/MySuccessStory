<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My account</title>
</head>

<body>
    <header>
        <h1>Bienvenue <?= $getUserInformation["firstName"]; ?></h1>
    </header>
    <main>
        <h2>informations</h2>
        <div>
            <form method="POST">
                <p>
                    <label>Nom</label>
                    <input type="text" name="lastName" value="<?= $getUserInformation["lastName"]; ?>">
                </p>

                <p>
                    <label>Prénom</label>
                    <input type="text" name="firstName" value="<?= $getUserInformation["firstName"]; ?>">
                </p>

                <p>
                    <label>Email :</label>
                    <?= $getUserInformation["email"]; ?>
                </p>

                <p>
                    <label>mot de passe</label>
                    <input type="password" name="password" value="">
                </p>

                <p>
                    <label>Année d'entré</label>
                    <input type="number" name="entryYear" value="<?= $getUserInformation["entryYear"]; ?>">
                </p>

                <p>
                    <label>Année de sortie</label>
                    <input type="number" name="exitYear" value="<?= $getUserInformation["exitYear"]; ?>">
                </p>
                
                <!-- Submit and Cancel -->
                <p>
                    <input type="submit" name="submit" value="Modifier">   
                    <input type="submit" name="submit" value="Retour">
                </p>
            </form>
        </div>
    </main>
</body>

</html>