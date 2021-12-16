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
                <input type="number" name="note">
            </div>

            <div>
                <label name="subjects" for="subjects">nom de la matière </label>
                <select id="subjects" name="subjects">
                    <?php
                        // FAIRE LA BOUCLE POUR AFFICHER LES SUBJECTS
                    ?>
                </select>
            </div>

            <div>
                <label name="year" for="year"> année </label>
                <select id="year" name="year">
                    <?php
                        // FAIRE LA BOUCLE POUR AFFICHER LES ANNÉES
                    ?>
                </select>
            </div>

            <div>
                <label name="semester" for="semester"> semestre </label>
                <select id="semester" name="semester">
                    <?php
                        // FAIRE LA BOUCLE POUR AFFICHER LES SEMESTRES
                    ?>
                </select>
            </div>

            <div>
                <input type="submit" name="add" value="Ajouter">
                <input type="submit" name="cancel" value="Annuler">
            </div>
            
        </form>
    </main>

    <footer>

    </footer>
    
</body>

</html>