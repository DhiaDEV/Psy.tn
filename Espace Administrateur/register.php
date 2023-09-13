<?php 
session_start();
//Connexion a la Base De Données
$bdd= new PDO('mysql:host=localhost; dbname=psy.tn;','root','');
    
//---------------------S'INSCRIRE----------------------//

//validation de formulaire
if(isset($_POST['valider'])){
    //vérifier si le Administrateur a completer  tous les champs 
    if(!empty($_POST['nom']) AND(!empty($_POST['prenom'])) AND !empty($_POST['email']) AND !empty($_POST['mdp'])){
        //Les données de Administrateur
        $AdminNom = htmlspecialchars($_POST['nom']);
        $AdminPrenom = htmlspecialchars($_POST['prenom']);
        $AdminEmail=htmlspecialchars($_POST['email']);
        $AdminPasse=password_hash($_POST['mdp'],PASSWORD_DEFAULT);

        $fileType= $_FILES["file"]["type"];
        $fileName= $_FILES["file"]["name"];
        $file=$_FILES["file"]["tmp_name"];

        move_uploaded_file($file,"assets/img/PhotoProfile/". $fileName);
        $position="assets/img/PhotoProfile/". $fileName;

        // verifier si l'Administrateur existe deja dans le site
        $AdminExiste = $bdd->prepare('SELECT Email FROM administrateurs WHERE Email=?');
        $AdminExiste->execute(array($AdminEmail));

            if($AdminExiste->rowCount()==0){

                // insérer l' Administrateurs dans le BDD
                $insertAdmin = $bdd->prepare('INSERT INTO administrateurs(Prenom , Nom , Email , PhotoProfile, MotDePasse) VALUES(?,?,?,?,?)');
                $insertAdmin->execute(array($AdminPrenom,$AdminNom,$AdminEmail,$fileName,$AdminPasse));

                //Récuperer les informations de Administrateurs
                $getInfoAdmin= $bdd->prepare('SELECT AdminID ,Prenom , Nom ,Email FROM administrateurs WHERE  Prenom= ?  AND Nom=? AND Email=?');
                $getInfoAdmin->execute(array($AdminPrenom,$AdminNom,$AdminEmail));

                $AdminInfo= $getInfoAdmin-> fetch();

                // Authentifier le patient sur le site et récupérer ses données dans des variables globales sessions
                $_SESSION['auth']= true;
                $_SESSION['AdminID']= $AdminInfo['AdminID'];
                $_SESSION['nom']= $AdminInfo['Nom'];
                $_SESSION['prenom']= $AdminInfo['Prenom'];
                $_SESSION['email']= $AdminInfo['Email'];


                //Rediriger l'utilisateur sur la page d'accueil
                header('Location: register.php');

            }else{
                $errorMsg="<div class='alert alert-danger coloor' role='alert' style=' font-weight: 700;'>
                    Patient existe déja
                </div>  ";
                 }
        }   else {
                $errorMsg="<div class='alert alert-danger coloor' role='alert' style=' font-weight: 700;'>
                Veuillez complèter tous les champs
                </div> ";
        
            }
}


?>

<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/register.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:53 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>S'inscrire</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/logoF.png">

		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
		
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
    </head>
    <body>
	
		<!-- Main Wrapper -->
        <div class="main-wrapper login-body">
            <div class="login-wrapper">
            	<div class="container">
                	<div class="loginbox">
                    	<div class="login-left">
							<img class="img-fluid" src="assets/img/logoF.png" alt="Logo">
                        </div>
                        <div class="login-right">
							<div class="login-right-wrap">
								<h1>S'inscrire</h1>
								<p class="account-subtitle">Accéder à notre tableau de bord</p>
								
								<!-- Form -->
								<form action="" method="POST" enctype="multipart/form-data">
									<div class="form-group">
										<input class="form-control" name="nom" type="text" placeholder="Prénom">
									</div>
									<div class="form-group">
										<input class="form-control" name="prenom" type="text" placeholder="Nom">
									</div>
									<div class="form-group">
										<input class="form-control" name="email" type="text" placeholder="Email">
									</div>
									<div class="form-group">
										<label for="">Photo Profile</label>
										<input class="form-control" name="file" type="file">
									</div>
									<div class="form-group">
										<input class="form-control" name="mdp" type="password" placeholder="Mot De Passe">
									</div>
									<div class="form-group mb-0">
										<button class="btn btn-primary btn-block" name="valider" type="submit">S'inscrire</button>
									</div>
								</form>
								<!-- /Form -->
								<div class="text-center dont-have">Vous avez déjà un compte? <a href="login.php">Se Connecter</a></div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<!-- /Main Wrapper -->
		
		<!-- jQuery -->
        <script src="assets/js/jquery-3.2.1.min.js"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
		
		<!-- Custom JS -->
		<script src="assets/js/script.js"></script>
		
    </body>

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/register.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:53 GMT -->
</html>