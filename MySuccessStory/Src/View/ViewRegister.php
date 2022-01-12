<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <title>Inscription</title>
</head>

<body>
    <h1>Inscription</h1>
    <form method="post" action="">
        <label>Prenom: <input type="text" name="firstName" /></label> <br>
        <label>Nom: <input type="text" name="lastName" /></label> <br>
        <label>Mot de passe: <input type="password" name="pwd" /></label> <br>
        <label>Confirmation du mot de passe: <input type="password" name="pwd2" /></label> <br>
        <label>Adresse e-mail: <input type="text" name="email" /></label>@eduge.ch<br>
        <!-- <label>Annéee d'entrée: <input type="date" name="entryYear" /></label><br>
        <label>Annéee de sortie: <input type="date" name="exitYear" /></label><br> -->
        <!-- <p>Date: <input type="text" id="datepicker" /></p> <br> -->

        <!-- <script>
            $(function() {
                $('#datepicker').datepicker({
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'yy',
                    onClose: function(dateText, inst) {
                        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                        $(this).datepicker('setDate', new Date(year, 1));
                    }
                });
                $("#datepicker").focus(function() {
                    $(".ui-datepicker-month").hide();
                    $(".ui-datepicker-calendar").hide();
                });

            });
        </script> -->


        <br>
        <br>




        <input type="submit" name="validate" />
    </form>
</body>

</html>