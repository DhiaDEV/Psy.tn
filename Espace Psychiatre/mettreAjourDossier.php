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
    ob_start();


    if (isset($_GET['IDDossier'])) {
        $getidD = intval($_GET['IDDossier']);


        $getinfoDossier= $bdd->prepare('SELECT * FROM dossier_medical WHERE idDM=?');
        $getinfoDossier->execute(array($getidD));

        $resultat = $getinfoDossier->fetch();
        
        if (isset($_POST['enregistrer'])) {
            $nouvNote = nl2br($_POST['notes']);
            
            $modifierDossier = $bdd->prepare('UPDATE dossier_medical SET note = ?, date_maj = NOW() WHERE idDM = ?');
            $modifierDossier->execute([$nouvNote, $getidD]);

            header('Location: my-patients.php?userID=' . $psyInfo['userID']);
            ob_end_flush();        
        }
    }    


?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Mettre à jour le dossier médical</title>
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
                    <textarea class="form-control" name="notes" id="exampleFormControlTextarea1" rows="10"><?php echo $resultat['note'] ?></textarea>
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