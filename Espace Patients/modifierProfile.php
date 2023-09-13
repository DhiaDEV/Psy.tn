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




    if (isset($_SESSION['userID'])) {

        $reqPatient = $bdd->prepare('SELECT * FROM compte_utilisateurs WHERE userID= ? ');
        $reqPatient->execute(array($_SESSION['userID']));

        $patients = $reqPatient->fetch();

        //Modification Nom 
        if (isset($_POST['nouvNom']) and !empty($_POST['nouvNom']) and $_POST['nouvNom'] != $patients['Nom']) {
            $nouvNom = htmlspecialchars($_POST['nouvNom']);
            $insertNom = $bdd->prepare("UPDATE compte_utilisateurs SET Nom =? WHERE userID= ?");
            $insertNom->execute(array($nouvNom, $_SESSION['userID']));
            echo '<script>alert("Modification bien enregistrée !");</script>';
            echo '<script>window.location.href = "profile.php?userID=' . $_SESSION['userID'] . '";</script>';
                    }

        //Modification Prenom
        if (isset($_POST['nouvPrenom']) and !empty($_POST['nouvPrenom']) and $_POST['nouvPrenom'] != $patients['Prenom']) {
            $nouvNom = htmlspecialchars($_POST['nouvPrenom']);
            $insertNom = $bdd->prepare("UPDATE compte_utilisateurs SET Prenom =? WHERE userID= ?");
            $insertNom->execute(array($nouvNom, $_SESSION['userID']));
            echo '<script>alert("Modification bien enregistrée !");</script>';
            echo '<script>window.location.href = "profile.php?userID=' . $_SESSION['userID'] . '";</script>';        }


        //Modification Numero
        if (isset($_POST['nouvNum']) and !empty($_POST['nouvNum']) and $_POST['nouvNum'] != $patients['NumeroT']) {
            $nouvNum = htmlspecialchars($_POST['nouvNum']);
            $insertNum = $bdd->prepare("UPDATE compte_utilisateurs SET NumeroT =? WHERE userID= ?");
            $insertNum->execute(array($nouvNum, $_SESSION['userID']));
            echo '<script>alert("Modification bien enregistrée !");</script>';
            echo '<script>window.location.href = "profile.php?userID=' . $_SESSION['userID'] . '";</script>';        }

        //Modification Email
        if (isset($_POST['nouvEmail']) and !empty($_POST['nouvEmail']) and $_POST['nouvEmail'] != $patients['Email']) {
            $nouvEmail = htmlspecialchars($_POST['nouvEmail']);
            $insertEmail = $bdd->prepare("UPDATE compte_utilisateurs SET Email =? WHERE userID= ?");
            $insertEmail->execute(array($nouvEmail, $_SESSION['userID']));
            echo '<script>alert("Modification bien enregistrée !");</script>';
            echo '<script>window.location.href = "profile.php?userID=' . $_SESSION['userID'] . '";</script>';        }


        //Modification MDP
        if (isset($_POST['valider'])) {
            $ancienMDP = $_POST['ancienMDP'];
            $nouvMDP = $_POST['nouvMDP'];
            $patientID = $patientInfo['userID'];
            $hashed_ancienMDP = password_hash($ancienMDP, PASSWORD_DEFAULT);

            //Vérifiez si l'ancien mot de passe saisi correspond à celui de la base de données
            if (password_verify($ancienMDP, $patientInfo['MotDePasse'])) {
                // Hacher le nouveau mot de passe
                $hashed_nouvMDP = password_hash($nouvMDP, PASSWORD_DEFAULT);

                // Mettre à jour le mot de passe dans la base de données
                $sql = "UPDATE compte_utilisateurs SET MotDePasse = '$hashed_nouvMDP' WHERE userID = $patientID";
                // Exécutez la requête et vérifiez si elle a réussi
                if ($bdd->query($sql) === TRUE) {
                }
            }
        }

        //Modification Image Profile     
        if (isset($_POST['Simage'])) {

            $ImageTypeNouv = $_FILES["imageprofile"]["type"];
            $ImageNameNouv = $_FILES["imageprofile"]["name"];
            $ImageNouv = $_FILES["imageprofile"]["tmp_name"];
            move_uploaded_file($ImageNouv, "img/" . $ImageNameNouv);
            $positionNouv = "img/" . $ImageNameNouv;

            //Requête Modifier Image
            $ModifierImage = $bdd->prepare('UPDATE compte_utilisateurs SET PhotoProfile=? WHERE userID=?');
            $ModifierImage->execute(array($ImageNameNouv, $_SESSION['userID']));

            header('Location: modifierProfile.php?userID=' . $_SESSION['userID']);
        }

?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="utf-8">
            <title>Modifier Profile</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
            <link href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
            <link rel="stylesheet" href="css/nv.css">

            <link href="img/logoF.png" rel="icon">

            <style>
                .ModPrf {
                    color: #111111;
                }

                .modif {
                    margin-top: 15px;
                    background-color: #66CDAA;
                    color: #111111;
                    border: #66CDAA;
                }

                .modif:hover {
                    background-color: #111111;
                    color: white;
                }

                .modiff {
                    margin-top: 15px;
                    background-color: #d53505;
                    color: white;
                    border: #d53505;
                }

                .modiff:hover {
                    background-color: #111111;
                    color: white;
                }
            </style>
        </head>

        <body>
            <div class="container bootstrap snippets bootdey">
                <h1 class="ModPrf">Modifier Profile</h1>
                <hr>
                <div class="row">
                    <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data">

                        <div class="col-md-3">
                            <div class="text-center">

                                <img src="img/<?php echo $patientInfo['PhotoProfile']; ?>" class="avatar img-circle img-thumbnail" alt="avatar">
                                <h6>Télécharger une autre photo...</h6>
                                <input type="file" name="imageprofile" class="form-control" value="">
                                <button type="submit" name="Simage" class="btn btn-warning modif">Sauvegarder Image</button>


                            </div>
                        </div>

                        <div class="col-md-9 personal-info">

                            <h3 style="font-weight: 700;">Informations personnelles</h3>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Nom:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="text" name="nouvNom" value="<?php echo $patientInfo['Nom'] ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Prenom:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="text" name="nouvPrenom" value="<?php echo $patientInfo['Prenom'] ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Numéro De Téléphone:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="number" name="nouvNum" value="<?php echo $patientInfo['NumeroT'] ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Email:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="email" name="nouvEmail" value="<?php echo $patientInfo['Email'] ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Entrez votre mot de passe existant:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="password" name="ancienMDP" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Entrez votre nouveau mot de passe:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="password" name="nouvMDP" value="">
                                    <button type="submit" name="valider" class="btn btn-warning modif">Modifier</button>
                                    <a href="profile.php?userID=<?= $patientInfo['userID'] ?>" class="btn btn-warning modiff">Annuler</a>

                                </div>
                            </div>

                            <?php if (isset($errorMsg)) {
                                echo '<p>' . $errorMsg . '</p>';
                            } ?>



                        </div>
                </div>
            </div>

            </form>
            </div>
            </div>
            </div>
            <hr>
            <style type="text/css">
                body {
                    margin-top: 20px;
                }

                .avatar {
                    width: 200px;
                    height: 200px;
                }
            </style>

            <script type="text/javascript">

            </script>
        </body>

        </html>
    <?php
    } else {
        header('Location: index.php');
    }

    ?>

<?php  } ?>