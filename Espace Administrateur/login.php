<?php 
session_start();
//Connexion a la Base De Données
$bdd= new PDO('mysql:host=localhost; dbname=psy.tn;','root','');
//-----------------SE CONNECTER---------------------//

//validation de formulaire
if(isset($_POST['connecter'])){
    //vérifier si le patient a completer  tous les champs 
    if(!empty($_POST['email']) AND !empty($_POST['mdp']) ){
        //Les données de patient
        $AdminEmail = htmlspecialchars($_POST['email']);
        $AdminPasse=htmlspecialchars($_POST['mdp']);
        //verifier si le patient existe(si le CIN est correcte)
        $AdminExist =$bdd->prepare('SELECT * FROM administrateurs WHERE Email= ?');
        $AdminExist->execute(array($AdminEmail));

            if($AdminExist->rowCount()> 0){
                //Récuperer les données de l'user
                $AdminInfo= $AdminExist->fetch();
                //Vérifier si le mot de passe est correcte
                if(password_verify($AdminPasse, $AdminInfo['MotDePasse'])){

                // Authentifier l'utilisateur sur le site et récupérer ses données dans des variables globales sessions
                $_SESSION['auth']= true;
                $_SESSION['AdminID']= $AdminInfo['AdminID'];
                $_SESSION['nom']= $AdminInfo['Nom'];
                $_SESSION['prenom']= $AdminInfo['Prenom'];
                $_SESSION['email']= $AdminInfo['Email'];
                
                //Rediriger l'utilisateur sur la page d'accueil
                header("Location: index.php ?AdminID=".$_SESSION['AdminID']);

            }


        }else{
            $errorMsg="<div class='alert alert-danger coloor' role='alert' style=' font-weight: 700;'>
            Votre mot de passe ou pseudo est incorrect
            </div> ";
        }
        } else {
            $errorMsg="<div class='alert alert-danger coloor' role='alert' style=' font-weight: 700;'>
            Veuillez completer tous les champs
            </div> ";

    }
        
         
}

?>


<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:46 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Se Connecter</title>
		
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
								<h1>Se Connecter</h1>
								<p class="account-subtitle">Accéder à notre tableau de bord</p>
								
								<!-- Form -->
								<form action="" method="POST">
									<div class="form-group">
										<input class="form-control" name="email" type="text" placeholder="Email">
									</div>
									<div class="form-group">
										<input class="form-control" name="mdp" type="password" placeholder="Mot De Passe">
									</div>
									<div class="form-group">
										<button class="btn btn-primary btn-block" name="connecter" type="submit">Connecter</button>
									</div>
								</form>
								<!-- /Form -->
								
								<div class="text-center dont-have">Vous n'avez pas de compte ? <a href="register.php">S'inscrire</a></div>
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

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:46 GMT -->
</html>