<?php
session_start();
//Connexion a la Base De Données
$bdd = new PDO('mysql:host=localhost; dbname=psy.tn;', 'root', '');

//---------------------S'INSCRIRE----------------------//

//validation de formulaire
if (isset($_POST['valider'])) {
    //vérifier si le patient a completer  tous les champs 
    if (!empty($_POST['pseudo']) and !empty($_POST['nom']) and (!empty($_POST['prenom'])) and !empty($_POST['tlph']) and !empty($_POST['email']) and !empty($_POST['age']) and !empty($_POST['sexe']) and !empty($_POST['Passe'])) {
        //Les données de patient
        $patientPseudo = htmlspecialchars($_POST['pseudo']);
        $patientNom = htmlspecialchars($_POST['nom']);
        $patientPrenom = htmlspecialchars($_POST['prenom']);
        $patientTlph = htmlspecialchars($_POST['tlph']);
        $patientEmail = htmlspecialchars($_POST['email']);
        $patientAge = htmlspecialchars($_POST['age']);
        $patientSexe = htmlspecialchars($_POST['sexe']);
        $patientPasse = password_hash($_POST['Passe'], PASSWORD_DEFAULT);

        // Photo Profile de patient
        $fileType = $_FILES["file"]["type"];
        $fileName = $_FILES["file"]["name"];
        $file = $_FILES["file"]["tmp_name"];

        move_uploaded_file($file, "./Espace Patients/img/" . $fileName);
        $position = "./Espace Patients/img/" . $fileName;

        // verifier si le patient existe deja dans le site
        $patientExist = $bdd->prepare('SELECT username , Email FROM compte_utilisateurs WHERE username =? OR Email=? ');
        $patientExist->execute(array($patientPseudo, $patientEmail));

        if ($patientExist->rowCount() == 0) {

            // insérer le patient dans le BDD
            $insertPatient = $bdd->prepare('INSERT INTO compte_utilisateurs(username,Nom , Prenom , Email ,NumeroT , PhotoProfile, MotDePasse,Role) VALUES(?,?,?,?,?,?,?,0)');
            $insertPatient->execute(array($patientPseudo, $patientNom, $patientPrenom, $patientEmail, $patientTlph, $fileName, $patientPasse));


            $user_id = $bdd->lastInsertId();
            $insertPatientP = $bdd->prepare('INSERT INTO patients(idPatient	, age , sexe) VALUES (?,?,?)');
            $insertPatientP->execute(array($user_id, $patientAge, $patientSexe));


            //Récuperer les informations de patient
            $getInfosPatient = $bdd->prepare('SELECT userID ,Nom , Prenom ,Email FROM compte_utilisateurs WHERE  Nom= ?  AND Prenom=? AND Email=?');
            $getInfosPatient->execute(array($patientNom, $patientPrenom, $patientEmail));

            $patientInfo = $getInfosPatient->fetch();

            // Authentifier le patient sur le site et récupérer ses données dans des variables globales sessions
            $_SESSION['auth'] = true;
            $_SESSION['userID'] = $patientInfo['userID'];
            $_SESSION['nom'] = $patientInfo['Nom'];
            $_SESSION['prenom'] = $patientInfo['Prenom'];
            $_SESSION['email'] = $patientInfo['Email'];


            //Rediriger l'utilisateur sur la page d'accueil

            echo "<script>alert('compte créé avec succès');
            window.location='connexion.php';</script>";
        } else {
            $errorMsg = "<div class='alert alert-danger coloor' role='alert' style=' font-weight: 700;'>
                    Patient existe déja
                </div>  ";
        }
    } else {
        $errorMsg = "<div class='alert alert-danger coloor' role='alert' style=' font-weight: 700;'>
                Veuillez complèter tous les champs
                </div> ";
    }
}

//-----------------SE CONNECTER---------------------//

//validation de formulaire
if (isset($_POST['connecter'])) {
    //vérifier si le patient a completer  tous les champs 
    if (!empty($_POST['emaill']) and !empty($_POST['mdp'])) {
        //Les données de patient
        $patientEmail = htmlspecialchars($_POST['emaill']);
        $patientMDP = htmlspecialchars($_POST['mdp']);
        //verifier si le patient existe
        $patientExiste = $bdd->prepare('SELECT * FROM compte_utilisateurs WHERE Email= ? AND Role = 0 ');
        $patientExiste->execute(array($patientEmail));

        if ($patientExiste->rowCount() > 0) {
            //Récuperer les données de l'user
            $patientInfos = $patientExiste->fetch();
            //Vérifier si le mot de passe est correcte
            if (password_verify($patientMDP, $patientInfos['MotDePasse'])) {

                // Authentifier l'utilisateur sur le site et récupérer ses données dans des variables globales sessions
                $_SESSION['auth'] = true;
                $_SESSION['userID'] = $patientInfos['userID'];
                $_SESSION['nom'] = $patientInfos['Nom'];
                $_SESSION['prenom'] = $patientInfos['Prenom'];
                $_SESSION['email'] = $patientInfos['Email'];

                //Rediriger l'utilisateur sur la page d'accueil
                header("Location:./Espace Patients/profile.php ?userID=" . $_SESSION['userID']);
            } else {
                $errorMsg = "<div class='alert alert-danger coloor' role='alert' style=' font-weight: 700;'>
                    Le mot de passe que vous avez saisi est incorrect.
                    </div> ";
            }
        } else {
            $errorMsg = "<div class='alert alert-danger coloor' role='alert' style=' font-weight: 700;'>
                    Aucun utilisateur n'a été trouvé avec cette adresse email.
                    </div> ";
        }
    } else {
        $errorMsg = "<div class='alert alert-danger coloor' role='alert' style=' font-weight: 700;'>
        Veuillez complèter tous les champs
        </div> ";
    }
}

?>

<!------------------------Code HTML------------------------>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/connexion.css">
    <link rel="stylesheet" href="css/connexionStyle.css">
    <link href="img/logoF.png" rel="icon">
    <title>Psy.tn</title>
    <style>
        .login-wrap {
            width: 100%;
            height: 1000px;
            margin: auto;
            max-width: 525px;
            min-height: 670px;
            position: relative;
            background: rgb(255, 255, 255);
            box-shadow: 0 12px 15px 0 rgba(0, 0, 0, 0.24), 0 17px 50px 0 rgba(0, 0, 0, .19);
        }
    </style>
</head>

<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="login-wrap">
            <div class="login-html">
                <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Se connecter</label>
                <input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">S'inscrire </label>
                <div class="login-form">
                    <!-----------------SE CONNECTER----------------->
                    <div class="sign-in-htm">
                        <div class="group">
                            <label for="user" class="label">Email</label>
                            <input id="user" name="emaill" type="email" class="input">
                        </div>
                        <div class="group">
                            <label for="pass" class="label">Mot de passe</label>
                            <input id="pass" name="mdp" type="password" class="input" data-type="password">
                        </div>

                        <div class="group">
                            <input type="submit" name="connecter" class="button" value="Se connecter">
                        </div>
                        <a href="index.php" class="retourne">Retourne a la page d'accueil</a>

                        <div class="hr"></div>
                        <div class="group1">
                            <a href="./Espace Psychiatre/connexionPsy.php" class="button" style="text-align: center; font-weight: 700; font-size:12px ;">VOUS ÊTES PROFESSIONNEL DE SANTÉ ?</a>
                        </div>
                        <?php if (isset($errorMsg)) {
                            echo '<p>' . $errorMsg . '</p>';
                        } ?>

                    </div>

                    <!---------------------S'INSCRIRE---------------------->
                    <div class="sign-up-htm">

                        <div class="group">
                            <label for="user" class="label">Pseudo <span style="font-size: 10px;"> (Votre nom et prénom sans espace)</span></label>
                            <input id="user" name="pseudo" type="text" class="input">
                        </div>

                        <div class="group">
                            <label for="user" class="label">Nom</label>
                            <input id="user" name="nom" type="text" class="input">
                        </div>

                        <div class="group">
                            <label for="user" class="label">Prénom</label>
                            <input id="user" name="prenom" type="text" class="input">
                        </div>

                        <div class="group">
                            <label for="pass" class="label">Numéro De Téléphone</label>
                            <input id="pass" name="tlph" type="number" class="input">
                        </div>
                        <div class="group">
                            <label for="pass" class="label">Email</label>
                            <input id="pass" name="email" type="email" class="input">
                        </div>
                        <div class="group">
                            <label for="pass" class="label">Age</label>
                            <input id="pass" name="age" type="number" class="input">
                        </div>

                        <div class="group">
                            <label for="pass" class="label">Genre :</label><br>
                            <input type="radio" id="homme" name="sexe" value="homme">
                            <label for="homme">Homme</label><br>
                            <input type="radio" id="femme" name="sexe" value="femme">
                            <label for="femme">Femme</label>

                        </div>

                        <div class="group">
                            <label for="pass" class="label">Mot de passe</label>
                            <input id="pass" name="Passe" type="password" class="input" data-type="password">
                        </div>

                        <div class="group">
                            <label for="user" class="label">Photo De Profile</label>
                            <input id="user" name="file" type="file" accept="image/*" class="input">
                        </div>

                        <div class="group">
                            <input type="submit" name="valider" class="button" value="S'inscrire">
                        </div>
                        <div class="group1">
                            <a href="./Espace Psychiatre/sinscrire.php" class="button" style="text-align: center; font-weight: 700; font-size:12px ;">VOUS ÊTES PROFESSIONNEL DE SANTÉ ?</a>
                        </div>
                        <?php if (isset($errorMsg)) {
                            echo '<p class="ErrorMsg>' . $errorMsg . '</p>';
                        } ?>

                    </div>
                </div>
            </div>
        </div>
    </form>
</body>

</html>