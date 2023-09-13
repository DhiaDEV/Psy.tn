<?php
session_start();
//Connexion a la Base De Données
$bdd = new PDO('mysql:host=localhost; dbname=psy.tn;', 'root', '');

//Sécurité... 
if (!$_SESSION['auth']) {
    header('Location: connexionPsy.php');
}

if (isset($_GET['userID']) and $_GET['userID'] > 0) {
    $getid = intval($_GET['userID']); //intval pour sécuriser l'id 
    //Récupérer les données de client par l id qui entrer
    $recupPsy = $bdd->prepare('SELECT * FROM compte_utilisateurs u , psychiatres p WHERE u.userID = p.PsyID AND userID= ?');
    $recupPsy->execute(array($getid));
    $psyInfo = $recupPsy->fetch();

    if (isset($_GET['IDPatient'])) {
        $getidP = intval($_GET['IDPatient']);

        if(isset($_POST['enregistrer'])){
            if(!empty($_POST['note'])){

                $note = nl2br($_POST['note']);

                $insertDossier = $bdd->prepare('INSERT INTO dossier_medical(IDPsychiatre ,IDPatient ,note ,date_creation ) VALUES (?, ?, ?, NOW())');
                $insertDossier->execute(array($getid , $getidP , $note  ));
                echo '<script>alert("Dossier enregistré avec succès !");</script>';
                echo '<script>window.location.href = "my-patients.php?userID=' . $psyInfo['userID'] . '";</script>'; 
                }
        }
    }    


?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Dossier Médical</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <!-- Favicons -->
        <link href="assets/img/logoF.png" rel="icon">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">

        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
        <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

        <!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">


    </head>

    <body>
        <br><br>
        <div class="container">
            <form method="POST">
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Notes</label>
                    <textarea class="form-control" name="note" id="exampleFormControlTextarea1" rows="10"></textarea>
                </div>
                <button type="submit" name="enregistrer" class="btn btn-success">Enregistrer</a>
            </form>
        </div>



        <!-- jQuery -->
        <script src="assets/js/jquery.min.js"></script>

        <!-- Bootstrap Core JS -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>

        <!-- Sticky Sidebar JS -->
        <script src="assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
        <script src="assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>

        <!-- Custom JS -->
        <script src="assets/js/script.js"></script>

    </body>

    </html>
<?php } ?>