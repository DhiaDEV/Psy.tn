<?php
session_start();
//Connexion a la Base De Données
$bdd = new PDO('mysql:host=localhost; dbname=psy.tn;', 'root', '');

//Sécurité... 
if (!$_SESSION['auth']) {
    header('Location: index.php');
}
if (isset($_GET['userID']) and $_GET['userID'] > 0) {
    $getid = intval($_GET['userID']); //intval pour sécuriser l'id 
    //Récupérer les données de Patient par l id qui entrer
    $recupPatient = $bdd->prepare('SELECT * FROM compte_utilisateurs WHERE userID= ?');
    $recupPatient->execute(array($getid));
    $patientInfo = $recupPatient->fetch();



?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Prendre Rendez-Vous</title>
        <!-- Favicon -->
        <link href="img/logoF.png" rel="icon">

        <!-- Font Icon -->
        <link rel="stylesheet" href="Rendez-Vous/fonts/material-icon/css/material-design-iconic-font.min.css">

        <!-- Main css -->
        <link rel="stylesheet" href="Rendez-Vous/css/style.css">

        <!-- CSS pour Button Confirmer et Annuler-->
        <style>
            body{
                background-image: url("./Rendez-Vous/images/bg.jpg");
                backdrop-filter: blur(2px);
            }
       
            .form-submit {
                display: flex;

            }

            .submit {
                margin-right: 15px;

            }

            .submit {
                background-color: #66CDAA;
                color: black;
            }

            .submit:hover {
                background-color: black;
                color: white;
            }

            .Ann {
                color: red;
                text-decoration: none;
                padding-top: 15px;

            }

            .Ann:hover {
                color: black;
            }
        </style>
    </head>

    <body>
        <?php
        if (isset($_GET['PsyID'])) {
            $getidP = intval($_GET['PsyID']);


            if (isset($_POST['valider'])) {
                if (
                    !empty($_POST['nom']) and !empty($_POST['prenom']) and !empty($_POST['email']) and !empty($_POST['numt'])
                    and !empty($_POST['date']) and !empty($_POST['heure'])and !empty($_POST['type'])
                ) {
                    $nomP = htmlspecialchars($_POST['nom']);
                    $prenomP = htmlspecialchars($_POST['prenom']);
                    $emailP = htmlspecialchars($_POST['email']);
                    $numP = htmlspecialchars($_POST['numt']);
                    $dateR = htmlspecialchars($_POST['date']);
                    $timeR = htmlspecialchars($_POST['heure']);
                    $typeR = htmlspecialchars($_POST['type']);

                    $insertRDV = $bdd->prepare('INSERT INTO rendez_vous (IDPatient,IDPsy,NomP , PrenomP , EmailP , NumeroP , Date , Time,Type,Statut) VALUES (?,?,?,?,?,?,?,?,?,"Nouveau")');
                    $insertRDV->execute(array($getid, $getidP, $nomP, $prenomP, $emailP, $numP, $dateR, $timeR, $typeR));

                    echo '<script>alert("Votre rendez-vous a été pris avec succès !");</script>';
                    echo '<script>window.location.href = "profile.php?userID=' . $patientInfo['userID'] . '";</script>';
                    }
            }
        }
        ?>
        <div class="main">
            <div class="container">
                <form method="POST" class="appointment-form" id="appointment-form">
                    <h2>Prendre Rendez-Vous</h2>
                    <div class="form-group-1">
                        <input type="text" name="nom" id="title" placeholder="Nom" required />
                        <input type="text" name="prenom" id="name" placeholder="Prénom" required />
                        <input type="email" name="email" id="email" placeholder="Email" required />
                        <input type="number" name="numt" id="phone_number" placeholder="Numéro Téléphone" required />
                        <label for="type" style="color:black; font-weight:bold; ">Choisissez Votre Type de rendez-vous:</label>
                        <br><br>
                        <select name="type" id="type">
                            <option value="Presentiel">Présentiel</option>
                            <option value="En_ligne">En Ligne</option>
                        </select>
                        <h3>Veuillez choisir la date du rendez-vous</h3>
                        <input type="date" id="date-input" name="date">
                        <input type="time" id="hour-input" name="heure">

                    </div>
                    <div class="form-submit">
                        <input type="submit" name="valider" id="submit" class="submit" value="Confirmer" />
                        <a href="psychologue.php?userID=<?php echo $patientInfo['userID'] ?>" class="Ann">Annuler</a>
                    </div>

                </form>
            </div>

        </div>

        <!-- JS -->
        <script src="Rendez-Vous/vendor/jquery/jquery.min.js"></script>
        <script src="Rendez-Vous/js/main.js"></script>
    </body>

    </html>
<?php } ?>