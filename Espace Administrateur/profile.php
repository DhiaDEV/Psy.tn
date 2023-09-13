<?php
session_start();
//Connexion a la Base De Données
$bdd= new PDO('mysql:host=localhost; dbname=psy.tn;','root','');

//Sécurité... 
if(!$_SESSION['auth']){
    header('Location: login.php');

}
if(isset($_GET['AdminID']) AND $_GET['AdminID']>0){
    $getid= intval($_GET['AdminID']); //intval pour sécuriser l'id 
    //Récupérer les données de client par l id qui entrer
    $recupAdmin= $bdd->prepare('SELECT * FROM administrateurs WHERE AdminID= ?');
    $recupAdmin->execute(array($getid));
    $AdminInfo= $recupAdmin->fetch();

	if(isset($_SESSION['AdminID'])){
			//Modification MDP
			if (isset($_POST['changeMDP'])) {
				$ancienMDP = $_POST['Amdp'];
				$nouvMDP = $_POST['Nmdp'];
				$AdminID = $AdminInfo['AdminID'];
				$hashed_ancienMDP = password_hash($ancienMDP, PASSWORD_DEFAULT);
		
				//Vérifiez si l'ancien mot de passe saisi correspond à celui de la base de données
				if (password_verify($ancienMDP, $AdminInfo['MotDePasse'])) {
					// Hash the new password
					$hashed_nouvMDP = password_hash($nouvMDP, PASSWORD_DEFAULT);
						
					// Mettre à jour le mot de passe dans la base de données
					$sql = "UPDATE administrateurs SET MotDePasse = '$hashed_nouvMDP' WHERE AdminID = $AdminID";
					// Exécutez la requête et vérifiez si elle a réussi
					 if ($bdd->query($sql) === TRUE) {
							
					 } 
			}   }

		$reqAdmin= $bdd->prepare('SELECT * FROM administrateurs WHERE AdminID= ? ');
		$reqAdmin->execute(array($_SESSION['AdminID']));
		
		$admin= $reqAdmin->fetch();
	
		//Modification Nom 
		if(isset($_POST['nom']) AND !empty($_POST['nom']) AND $_POST['nom'] != $admin['Nom']){
			$nouvNom= htmlspecialchars($_POST['nom']);
			$insertNom= $bdd->prepare("UPDATE administrateurs SET Nom =? WHERE AdminID= ?");
			$insertNom->execute(array($nouvNom , $_SESSION['AdminID']));
			header('Location: profile.php?AdminID='. $_SESSION['AdminID']);
		} 
	
		 //Modification Prenom
		 if(isset($_POST['prenom']) AND !empty($_POST['prenom']) AND $_POST['prenom'] != $admin['Prenom']){
			$nouvNom= htmlspecialchars($_POST['prenom']);
			$insertNom= $bdd->prepare("UPDATE administrateurs SET Prenom =? WHERE AdminID= ?");
			$insertNom->execute(array($nouvNom , $_SESSION['AdminID']));
			header('Location: profile.php?AdminID='. $_SESSION['AdminID']);
		} 
		//Modification Email
		if(isset($_POST['email']) AND !empty($_POST['email']) AND $_POST['email'] != $admin['Email']){
			$nouvEmail= htmlspecialchars($_POST['email']);
			$insertEmail= $bdd->prepare("UPDATE administrateurs SET Email =? WHERE AdminID= ?");
			$insertEmail->execute(array($nouvEmail , $_SESSION['AdminID']));
			header('Location: profile.php?AdminID='. $_SESSION['AdminID']);
		}
		    //Modification Image Profile     
			if(isset($_POST['Simage']) ){
        
				$ImageTypeNouv= $_FILES["imageprofile"]["type"];
				$ImageNameNouv= $_FILES["imageprofile"]["name"];
				$ImageNouv=$_FILES["imageprofile"]["tmp_name"];
				move_uploaded_file($ImageNouv,"assets/img/PhotoProfile/". $ImageNameNouv);
				$positionNouv="assets/img/PhotoProfile/". $ImageNameNouv;
		
				//Requête Modifier Image
				$ModifierImage =$bdd->prepare('UPDATE administrateurs SET PhotoProfile=? WHERE AdminID=?');
				$ModifierImage->execute(array($ImageNameNouv,$_SESSION['AdminID']));
		
				header('Location: profile.php?AdminID='. $_SESSION['AdminID']);
		
			}
	}


?>
<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/profile.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:46 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Profile</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/logoF.png">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
		
		<!-- Feathericon CSS -->
        <link rel="stylesheet" href="assets/css/feathericon.min.css">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
		
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
    </head>
    <body>
	
		<!-- Main Wrapper -->
        <div class="main-wrapper">
		
			<!-- Header -->
            <div class="header">
			
				<!-- Logo -->
                <div class="header-left">
                    <a href="index.php?AdminID=<?= $AdminInfo['AdminID'] ?>" class="logo">
						<img src="assets/img/logoF.png" alt="Logo">
					</a>
					<a href="index.php?AdminID=<?= $AdminInfo['AdminID'] ?>" class="logo logo-small">
						<img src="assets/img/logoF.png" alt="Logo" width="30" height="30">
					</a>
                </div>
				<!-- /Logo -->
				
				<a href="javascript:void(0);" id="toggle_btn">
					<i class="fe fe-text-align-left"></i>
				</a>
				
				<!-- Mobile Menu Toggle -->
				<a class="mobile_btn" id="mobile_btn">
					<i class="fa fa-bars"></i>
				</a>
				<!-- /Mobile Menu Toggle -->
				
				<!-- Header Right Menu -->
				<ul class="nav user-menu">
					<!-- User Menu -->
					<li class="nav-item dropdown has-arrow">
						<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
							<span class="user-img"><img class="rounded-circle" src="assets/img/PhotoProfile/<?php echo $AdminInfo['PhotoProfile'] ?>" width="31" alt="Ryan Taylor"></span>
						</a>
						<div class="dropdown-menu">
							<div class="user-header">
								<div class="avatar avatar-sm">
									<img src="assets/img/PhotoProfile/<?php echo $AdminInfo['PhotoProfile'] ?>" alt="User Image" class="avatar-img rounded-circle">
								</div>
								<div class="user-text">
									<h6><?php echo $AdminInfo['Prenom']. ' ',''. $AdminInfo['Nom'] ?></h6>
									<p class="text-muted mb-0">Administrateur</p>
								</div>
							</div>
							<a class="dropdown-item" href="profile.php?AdminID=<?= $AdminInfo['AdminID'] ?>">Mon Profile</a>
							<a class="dropdown-item" href="deconnexion.php">Déconnexion</a>
						</div>
					</li>
					<!-- /User Menu -->
					
				</ul>
				<!-- /Header Right Menu -->
				
            </div>
			<!-- /Header -->
			<!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <div class="sidebar-inner slimscroll">
					<div id="sidebar-menu" class="sidebar-menu">
						<ul>
							<li class="menu-title"> 
								<span>Principale</span>
							</li>
							<li> 
								<a href="index.php?AdminID=<?= $AdminInfo['AdminID'] ?>"><i class="fe fe-home"></i> <span>Accueil</span></a>
							</li>
							<li> 
								<a href="doctor-list.php?AdminID=<?= $AdminInfo['AdminID'] ?>"><i class="fe fe-user"></i> <span>Psychiatres</span></a>
							</li>
							<li> 
								<a href="patient-list.php?AdminID=<?= $AdminInfo['AdminID'] ?>"><i class="fe fe-user"></i> <span>Rendez-Vous</span></a>
							</li>
							<li> 
								<a href="utilisateurs.php?AdminID=<?= $AdminInfo['AdminID'] ?>"><i class="fe fe-user"></i> <span>Patients</span></a>
							</li>
							<li>
								<a href="questions.php?AdminID=<?= $AdminInfo['AdminID'] ?>"><i class="fe fe-question"></i> <span>Questions</span></a>
							</li>
							<li class="menu-title"> 
								<span>Pages</span>
							</li>
							<li class="active"> 
								<a href="profile.php?AdminID=<?= $AdminInfo['AdminID'] ?>"><i class="fe fe-user"></i> <span>Profile</span></a>
							</li>
					</div>
                </div>
            </div>
			<!-- /Sidebar -->
			
			<!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
					
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col">
								<h3 class="page-title">Profile</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.php?AdminID=<?= $AdminInfo['AdminID'] ?>">Accueil</a></li>
									<li class="breadcrumb-item active">Profile</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Header -->
					
					<div class="row">
						<div class="col-md-12">
							<div class="profile-header">
								<div class="row align-items-center">
									<div class="col-auto profile-image">
										<a href="#">
											<img class="rounded-circle" alt="User Image" src="assets/img/PhotoProfile/<?php echo $AdminInfo['PhotoProfile'] ?>">
										</a>
									</div>
									<div class="col ml-md-n2 profile-user-info">
										<h4 class="user-name mb-0"><?php echo $AdminInfo['Prenom']. ' ',''. $AdminInfo['Nom'] ?></h4>
										<h6 class="text-muted"><?php echo $AdminInfo['Email']?></h6>
									</div>
									<div class="col-auto profile-btn">
										<form action="" method="POST" enctype="multipart/form-data" >
										<button type="submit" name="Simage" class="btn btn-primary">
											Modifier
										</button>
										<input type="file" name="imageprofile" class="btn btn-primary">
										</input>
										</form>
									</div>
								</div>
							</div>
							<div class="profile-menu">
								<ul class="nav nav-tabs nav-tabs-solid">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#per_details_tab">À propos</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#password_tab">Mot De Passe</a>
									</li>
								</ul>
							</div>	
							<div class="tab-content profile-tab-cont">
								
								<!-- Personal Details Tab -->
								<div class="tab-pane fade show active" id="per_details_tab">
								
									<!-- Personal Details -->
									<div class="row">
										<div class="col-lg-12">
											<div class="card">
												<div class="card-body">
													<h5 class="card-title d-flex justify-content-between">
														<span>Détails personnels</span> 
														<a class="edit-link" data-toggle="modal" href="#edit_personal_details"><i class="fa fa-edit mr-1"></i>Modifier</a>
													</h5>
													<div class="row">
														<p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Nom</p>
														<p class="col-sm-10"><?php echo $AdminInfo['Prenom']. ' ',''. $AdminInfo['Nom'] ?></p>
													</div>
													<div class="row">
														<p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Email</p>
														<p class="col-sm-10"><?php echo $AdminInfo['Email']?></p>
													</div>
												</div>
											</div>
											
											<!-- Edit Details Modal -->
											<div class="modal fade" id="edit_personal_details" aria-hidden="true" role="dialog">
												<div class="modal-dialog modal-dialog-centered" role="document" >
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title">Détails personnels</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body">
															<form method="POST">
																<div class="row form-row">
																	<div class="col-12 col-sm-6">
																		<div class="form-group">
																			<label>Prénom</label>
																			<input type="text" name="prenom" class="form-control" value="<?php echo $AdminInfo['Prenom'] ?>">
																		</div>
																	</div>
																	<div class="col-12 col-sm-6">
																		<div class="form-group">
																			<label>Nom</label>
																			<input type="text" name="nom"  class="form-control" value="<?php echo $AdminInfo['Nom'] ?>">
																		</div>
																	</div>
																	<div class="col-12 col-sm-6">
																		<div class="form-group">
																			<label>Email</label>
																			<input type="email" name="email" class="form-control" value="<?php echo $AdminInfo['Email'] ?>">
																		</div>
																	</div>
																<button type="submit" name="valider" class="btn btn-primary btn-block">Enregistrer</button>
															</form>
														</div>
													</div>
												</div>
											</div>
											<!-- /Edit Details Modal -->
											
										</div>

									
									</div>
									<!-- /Personal Details -->

								</div>
								<!-- /Personal Details Tab -->
								
								<!-- Change Password Tab -->
								<div id="password_tab" class="tab-pane fade">
								
									<div class="card">
										<div class="card-body">
											<h5 class="card-title">Changer Mot De Passe </h5>
											<div class="row">
												<div class="col-md-10 col-lg-6">
													<form method="POST">
														<div class="form-group">
															<label>Ancien Mot De Passe</label>
															<input type="password" name="Amdp" class="form-control">
														</div>
														<div class="form-group">
															<label>Nouveau Mot De Passe </label>
															<input type="password" name="Nmdp" class="form-control">
														</div>
														<button class="btn btn-primary" name="changeMDP" type="submit">Enregistrer</button>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- /Change Password Tab -->
								
							</div>
						</div>
					</div>
				
				</div>			
			</div>
			<!-- /Page Wrapper -->
		
        </div>
		<!-- /Main Wrapper -->
		
		<!-- jQuery -->
        <script src="assets/js/jquery-3.2.1.min.js"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
		
		<!-- Slimscroll JS -->
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		
		<!-- Custom JS -->
		<script  src="assets/js/script.js"></script>
		
    </body>

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/profile.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:46 GMT -->
</html>
<?php } ?>