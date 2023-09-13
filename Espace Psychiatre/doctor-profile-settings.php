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



	if (isset($_SESSION['userID'])) {

		$reqPsy = $bdd->prepare('SELECT * FROM compte_utilisateurs u , psychiatres p WHERE u.userID = p.PsyID  AND userID= ? ');
		$reqPsy->execute(array($_SESSION['userID']));

		$Psy = $reqPsy->fetch();


		//Modification Image Profile     
		if (isset($_POST['Simage'])) {

			$ImageTypeNouv = $_FILES["photoProfile"]["type"];
			$ImageNameNouv = $_FILES["photoProfile"]["name"];
			$ImageNouv = $_FILES["photoProfile"]["tmp_name"];
			move_uploaded_file($ImageNouv, "images/profileImage/" . $ImageNameNouv);
			$positionNouv = "images/profileImage/" . $ImageNameNouv;

			//Requête Modifier Image
			$ModifierImage = $bdd->prepare('UPDATE compte_utilisateurs SET PhotoProfile=?  WHERE userID=?');
			$ModifierImage->execute(array($ImageNameNouv, $_SESSION['userID']));

			header('Location: doctor-profile-settings.php?userID=' . $_SESSION['userID']);
		}


		//Modification Nom
		if (isset($_POST['nom']) and !empty($_POST['nom']) and $_POST['nom'] != $psy['Nom']) {
			$nouvNom = htmlspecialchars($_POST['nom']);
			$insertNom = $bdd->prepare("UPDATE compte_utilisateurs SET Nom =? WHERE userID= ?");
			$insertNom->execute(array($nouvNom, $_SESSION['userID']));
			header('Location: doctor-dashboard.php?userID=' . $_SESSION['userID']);
		}


		//Modification Prenom
		if (isset($_POST['prenom']) and !empty($_POST['prenom']) and $_POST['prenom'] != $psy['Prenom']) {
			$nouvPrenom = htmlspecialchars($_POST['prenom']);
			$insertPrenom = $bdd->prepare("UPDATE compte_utilisateurs SET Prenom =? WHERE userID= ?");
			$insertPrenom->execute(array($nouvPrenom, $_SESSION['userID']));
			header('Location: doctor-dashboard.php?userID=' . $_SESSION['userID']);
		}


		//Modification Numero
		if (isset($_POST['numT']) and !empty($_POST['numT']) and $_POST['numT'] != $psy['NumeroT']) {
			$nouvNum = htmlspecialchars($_POST['numT']);
			$insertNum = $bdd->prepare("UPDATE compte_utilisateurs SET NumeroT =? WHERE userID= ?");
			$insertNum->execute(array($nouvNum, $_SESSION['userID']));
			header('Location: doctor-dashboard.php?userID=' . $_SESSION['userID']);
		}

		//Modification Email
		if (isset($_POST['email']) and !empty($_POST['email']) and $_POST['email'] != $psy['Email']) {
			$nouvEmail = htmlspecialchars($_POST['email']);
			$insertEmail = $bdd->prepare("UPDATE compte_utilisateurs SET Email =? WHERE userID= ?");
			$insertEmail->execute(array($nouvEmail, $_SESSION['userID']));
			header('Location: doctor-dashboard.php?userID=' . $_SESSION['userID']);
		}

		//Modification Cin
		if (isset($_POST['cin']) and !empty($_POST['cin']) and $_POST['cin'] != $psy['Cin']) {
			$nouvCin = htmlspecialchars($_POST['cin']);
			$insertCin = $bdd->prepare("UPDATE compte_utilisateurs u , psychiatres p SET p.Cin = ? WHERE  p.PsyID = u.userID AND p.PsyID = ?");
			$insertCin->execute(array($nouvCin, $_SESSION['userID']));
			header('Location: doctor-dashboard.php?userID=' . $_SESSION['userID']);
		}



		//Modification Gouvernorat
		if (isset($_POST['gouvernorat']) and !empty($_POST['gouvernorat']) and $_POST['gouvernorat'] != $psy['Gouvernorat']) {
			$nouvGouvernorat = htmlspecialchars($_POST['gouvernorat']);
			$insertGouvernorat = $bdd->prepare("UPDATE compte_utilisateurs u , psychiatres p SET p.Gouvernorat = ? WHERE  p.PsyID = u.userID AND p.PsyID = ?");
			$insertGouvernorat->execute(array($nouvGouvernorat, $_SESSION['userID']));
			header('Location: doctor-dashboard.php?userID=' . $_SESSION['userID']);
		}

		//Modification Lieu
		if (isset($_POST['lieu']) and !empty($_POST['lieu']) and $_POST['lieu'] != $psy['Lieu']) {
			$nouvLieu = htmlspecialchars($_POST['lieu']);
			$inserLieu = $bdd->prepare("UPDATE compte_utilisateurs u , psychiatres p SET p.Lieu = ? WHERE  p.PsyID = u.userID AND p.PsyID = ?");
			$inserLieu->execute(array($nouvLieu, $_SESSION['userID']));
			header('Location: doctor-dashboard.php?userID=' . $_SESSION['userID']);
		}
		//Modification Adresse
		if (isset($_POST['adr']) and !empty($_POST['adr']) and $_POST['adr'] != $psy['Adresse']) {
			$nouvAdr = htmlspecialchars($_POST['adr']);
			$inserAdr = $bdd->prepare("UPDATE compte_utilisateurs u , psychiatres p SET p.Adresse = ? WHERE  p.PsyID = u.userID AND p.PsyID = ?");
			$inserAdr->execute(array($nouvAdr, $_SESSION['userID']));
			header('Location: doctor-dashboard.php?userID=' . $_SESSION['userID']);
		}

		//Modification MDP
		if (isset($_POST['valider'])) {
			$ancienMDP = $_POST['mdp'];
			$nouvMDP = $_POST['mdpn'];
			$PsyID = $psyInfo['userID'];
			$hashed_ancienMDP = password_hash($ancienMDP, PASSWORD_DEFAULT);

			//Vérifiez si l'ancien mot de passe saisi correspond à celui de la base de données
			if (password_verify($ancienMDP, $psyInfo['MotDePasse'])) {
				// Hash the new password
				$hashed_nouvMDP = password_hash($nouvMDP, PASSWORD_DEFAULT);

				// Mettre à jour le mot de passe dans la base de données
				$sql = "UPDATE compte_utilisateurs SET MotDePasse = '$hashed_nouvMDP' WHERE userID = $PsyID";
				// Exécutez la requête et vérifiez si elle a réussi
				if ($bdd->query($sql) === TRUE) {
				}
			}
		}


		//Modification Description
		if (isset($_POST['description']) and !empty($_POST['description']) and $_POST['description'] != $Psy['Description']) {
			$nouvdescription = nl2br($_POST['description']);
			$inserdescription = $bdd->prepare("UPDATE  compte_utilisateurs u , psychiatres p SET p.Description = ? WHERE  p.PsyID = u.userID AND p.PsyID = ?");
			$inserdescription->execute(array($nouvdescription, $_SESSION['userID']));
			header('Location: doctor-dashboard.php?userID=' . $_SESSION['userID']);
		}




?>
		<!DOCTYPE html>
		<html lang="en">

		<head>
			<meta charset="utf-8">
			<title>Paramétre Profile</title>
			<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

			<!-- Favicons -->
			<link href="assets/img/logoF.png" rel="icon">

			<!-- Bootstrap CSS -->
			<link rel="stylesheet" href="assets/css/bootstrap.min.css">

			<!-- Fontawesome CSS -->
			<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
			<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

			<!-- Select2 CSS -->
			<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

			<!-- Bootstrap CSS -->
			<link rel="stylesheet" href="assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css">

			<link rel="stylesheet" href="assets/plugins/dropzone/dropzone.min.css">

			<!-- Main CSS -->
			<link rel="stylesheet" href="assets/css/style.css">

		</head>

		<body>

			<!-- Main Wrapper -->
			<div class="main-wrapper">

				<!-- Header -->
				<header class="header">
					<nav class="navbar navbar-expand-lg header-nav">
						<div class="navbar-header">
							<a id="mobile_btn" href="javascript:void(0);">
								<span class="bar-icon">
									<span></span>
									<span></span>
									<span></span>
								</span>
							</a>
							<a href="doctor-dashboard.php?userID=<?= $psyInfo['userID'] ?>" class="navbar-brand logo">
								<img src="assets/img/logoF.png" class="img-fluid" alt="Logo">
							</a>
						</div>
						<div class="main-menu-wrapper">
							<div class="menu-header">
								<a href="doctor-dashboard.php?userID=<?= $psyInfo['userID'] ?>" class="menu-logo">
									<img src="assets/img/logoF.png" class="img-fluid" alt="Logo">
								</a>
								<a id="menu_close" class="menu-close" href="javascript:void(0);">
									<i class="fas fa-times"></i>
								</a>
							</div>
							<ul class="main-nav">
								<li>
									<a href="doctor-dashboard.php?userID=<?php echo $psyInfo['userID'] ?>">Accueil</a>
								</li>
								<li class="has-submenu active">
									<a href="#">Liens <i class="fas fa-chevron-down"></i></a>
									<ul class="submenu">
										<li><a href="doctor-dashboard.php?userID=<?= $psyInfo['userID'] ?>">Accueil</a></li>
										<li><a href="appointments.php?userID=<?= $psyInfo['userID'] ?>">Rendez-Vous En Ligne</a></li>
										<li><a href="rendez-vousP.php?userID=<?= $psyInfo['userID'] ?>">Rendez-Vous Présentiel</a></li>
										<li><a href="my-patients.php?userID=<?= $psyInfo['userID'] ?>">Mes Patients</a></li>
										<li><a href="questions.php?userID=<?= $psyInfo['userID'] ?>">Questions</a></li>
										<li class="active"><a href="doctor-profile-settings.php?userID=<?= $psyInfo['userID'] ?>">Paramétre Profile</a></li>
									</ul>
								</li>
								<li class="has-submenu">
									<a href="#">Mes Articles <i class="fas fa-chevron-down"></i></a>
									<ul class="submenu">
										<li><a href="ajouterArticle.php?userID=<?= $psyInfo['userID'] ?>">Ajouter des Articles </a></li>
									</ul>
								</li>

							</ul>
						</div>
						<ul class="nav header-navbar-rht">
							<li class="nav-item contact-item">
							</li>

							<!-- User Menu -->
							<li class="nav-item dropdown has-arrow logged-item">
								<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
									<span class="user-img">
										<img class="rounded-circle" src="images/profileImage/<?php echo $psyInfo['PhotoProfile'] ?>" width="31" alt="Darren Elder">
									</span>
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<div class="user-header">
										<div class="avatar avatar-sm">
											<img src="images/profileImage/<?php echo $psyInfo['PhotoProfile'] ?>" alt="User Image" class="avatar-img rounded-circle">
										</div>
										<div class="user-text">
											<h6><?php echo $psyInfo['Prenom'], ' ' . '' . $psyInfo['Nom'] ?></h6>
											<p class="text-muted mb-0 text-capitalize"><?php echo $psyInfo['Specialite'] ?></p>
										</div>
									</div>
									<a class="dropdown-item" href="doctor-dashboard.php?userID=<?= $psyInfo['userID'] ?>">Accueil</a>
									<a class="dropdown-item" href="doctor-profile-settings.php?userID=<?= $psyInfo['userID'] ?>">Paramétre Profile</a>
									<a class="dropdown-item" href="deconnexionPsy.php">Déconnexion</a>
								</div>
							</li>
							<!-- /User Menu -->

						</ul>
					</nav>
				</header>
				<!-- /Header -->
				<br>

				<!-- Breadcrumb -->
				<div class="breadcrumb-bar">
					<div class="container-fluid">
						<div class="row align-items-center">
							<div class="col-md-12 col-12">
								<nav aria-label="breadcrumb" class="page-breadcrumb">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="doctor-dashboard.php?userID=<?= $psyInfo['userID'] ?>">Accueil</a></li>
										<li class="breadcrumb-item active" aria-current="page">Paramétre Profile</li>
									</ol>
								</nav>
								<h2 class="breadcrumb-title">Paramétre Profile</h2>
							</div>
						</div>
					</div>
				</div>
				<!-- /Breadcrumb -->

				<!-- Page Content -->
				<div class="content">
					<div class="container-fluid">

						<div class="row">
							<div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">

								<!-- Profile Sidebar -->
								<div class="profile-sidebar">
									<div class="widget-profile pro-widget-content">
										<div class="profile-info-widget">
											<a href="#" class="booking-doc-img">
												<img src="images/profileImage/<?php echo $psyInfo['PhotoProfile'] ?>" alt="User Image">
											</a>
											<div class="profile-det-info">
												<h3>Dr.<?php echo $psyInfo['Prenom'], ' ' . '' . $psyInfo['Nom'] ?></h3>

												<div class="patient-details">
													<!-- <h5 class="mb-0">BDS, MDS - Oral & Maxillofacial Surgery</h5> -->
												</div>
											</div>
										</div>
									</div>
									<div class="dashboard-widget">
										<nav class="dashboard-menu">
											<ul>
												<li>
													<a href="doctor-dashboard.php?userID=<?= $psyInfo['userID'] ?>">
														<i class="fas fa-columns"></i>
														<span>Accueil</span>
													</a>
												</li>
												<li>
													<a href="appointments.php?userID=<?= $psyInfo['userID'] ?>">
														<i class="fas fa-calendar-check"></i>
														<span>Rendez-Vous En Ligne</span>
													</a>
												</li>
												<li>
												<a href="rendez-vousP.php?userID=<?= $psyInfo['userID'] ?>">
														<i class="fas fa-calendar-check"></i>
														<span>Rendez-Vous Présentiel</span>
													</a>
												</li>
												<li>
													<a href="my-patients.php?userID=<?= $psyInfo['userID'] ?>">
														<i class="fas fa-user-injured"></i>
														<span>Mes patients</span>
													</a>
												</li>
												<li>
													<a href="questions.php?userID=<?= $psyInfo['userID'] ?>">
														<i class="fas fa-star"></i>
														<span>Questions</span>
													</a>
												</li>
												<li class="active">
													<a href="doctor-profile-settings.php?userID=<?= $psyInfo['userID'] ?>">
														<i class="fas fa-user-cog"></i>
														<span>Paramétre Profile</span>
													</a>
												</li>
												<li>
													<a href="deconnexionPsy.php">
														<i class="fas fa-sign-out-alt"></i>
														<span>Déconnexion</span>
													</a>
												</li>
											</ul>
										</nav>
									</div>
								</div>
								<!-- /Profile Sidebar -->

							</div>
							<div class="col-md-7 col-lg-8 col-xl-9">

								<!-- Basic Information -->
								<form action="" method="POST" enctype="multipart/form-data">
									<div class="card">
										<div class="card-body">
											<h4 class="card-title">Informations de base</h4>
											<div class="row form-row">
												<div class="col-md-12">
													<div class="form-group">
														<div class="change-avatar">
															<div class="profile-img">
																<img src="images/profileImage/<?php echo $psyInfo['PhotoProfile'] ?>" alt="User Image">
															</div>
															<div class="upload-img">
																<div class="change-photo-btn">
																	<span><i class="fa fa-upload"></i> Télécharger Photo</span>
																	<input type="file" name="photoProfile" class="upload">
																</div>
																<button style="font-size:10px; margin-left:25px;" class="btn btn-success" type="submit" name="Simage">Modifier Photo</button>

																<small class="form-text text-muted">JPG, GIF ou PNG autorisés. Taille maximale de 2 Mo</small>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Nom <span class="text-danger">*</span></label>
														<input type="text" name="nom" value="<?php echo $psyInfo['Nom'] ?>" class="form-control">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Prénom <span class="text-danger">*</span></label>
														<input type="text" name="prenom" value="<?php echo $psyInfo['Prenom'] ?>" class="form-control">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Numéro Téléphone <span class="text-danger">*</span></label>
														<input type="number" name="numT" value="<?php echo $psyInfo['NumeroT'] ?>" class="form-control">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Email <span class="text-danger">*</span></label>
														<input type="email" name="email" value="<?php echo $psyInfo['Email'] ?>" class="form-control">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Carte D'identité</label>
														<input type="text" name="cin" value="<?php echo $psyInfo['Cin'] ?>" class="form-control">
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group mb-0">
														<label>Gouvernorat</label>
														<input type="text" name="gouvernorat" value="<?php echo $psyInfo['Gouvernorat'] ?>" class="form-control">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group mb-0">
														<label>Lieu</label>
														<input type="text" name="lieu" value="<?php echo $psyInfo['Lieu'] ?>" class="form-control">
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group mb-0">
														<label>Adresse de Cabiner</label>
														<input type="text" name="adr" value="<?php echo $psyInfo['Adresse'] ?>" class="form-control">
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group mb-0">
														<label>Entrez votre mot de passe existant</label>
														<input type="password" name="mdp" class="form-control">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group mb-0">
														<label>Entrez votre nouveau mot de passe</label>
														<input type="password" name="mdpn" class="form-control">
													</div>
												</div>

											</div>
										</div>
									</div>
									<!-- /Basic Information -->

									<!-- About Me -->
									<div class="card">
										<div class="card-body">
											<h4 class="card-title">Moi</h4>
											<div class="form-group mb-0">
												<label>Description</label>
												<textarea name="description" class="form-control" rows="5"><?php echo $psyInfo['Description'] ?></textarea>
											</div>
										</div>
									</div>
									<!-- /About Me -->
									<div class="submit-section submit-btn-bottom">
										<button type="submit" name="valider" class="btn btn-primary submit-btn">Modifier</button>
									</div>

							</div>
							</form>
						</div>

					</div>

				</div>
				<!-- /Page Content -->

				<!-- Footer -->
				<footer class="footer">

					<!-- Footer Top -->
					<div class="footer-top">
						<div class="container-fluid">
							<div class="row">
								<div class="col-lg-3 col-md-6">

									<!-- Footer Widget -->
									<div class="footer-widget footer-about">
										<div class="footer-logo">
											<img src="assets/img/logoF.png" width="100%" alt="logo">
										</div>
									</div>
									<!-- /Footer Widget -->

								</div>

								<div class="col-lg-3 col-md-6">
									<!-- Footer Widget -->
									<div class="footer-widget footer-menu">
										<h2 class="footer-title">Pour les patients</h2>
										<ul>
											<li><a href="appointments.php?userID=<?php echo $psyInfo['userID'] ?>"><i class="fas fa-angle-double-right"></i> Rendez-Vous En Ligne</a></li>
											<li><a href="rendez-vousP.php?userID=<?php echo $psyInfo['userID'] ?>"><i class="fas fa-angle-double-right"></i> Rendez-Vous Présentiel</a></li>
											<li><a href="my-patients.php?userID=<?php echo $psyInfo['userID'] ?>"><i class="fas fa-angle-double-right"></i> Mes patients</a></li>
											<li><a href="questions.php?userID=<?php echo $psyInfo['userID'] ?>"><i class="fas fa-angle-double-right"></i> Questions</a></li>
										</ul>
									</div>
									<!-- /Footer Widget -->
								</div>
								<div class="col-lg-3 col-md-6">
									<!-- Footer Widget -->
									<div class="footer-widget footer-menu">
										<h2 class="footer-title">Pour Vous</h2>
										<ul>
											<li><a href="doctor-dashboard.php?userID=<?php echo $psyInfo['userID'] ?>"><i class="fas fa-angle-double-right"></i> Accueil</a></li>
											<li><a href="ajouterArticle.php?userID=<?php echo $psyInfo['userID'] ?>"><i class="fas fa-angle-double-right"></i> Ajouter Article</a></li>
											<li><a href="doctor-profile-settings.php?userID=<?php echo $psyInfo['userID'] ?>"><i class="fas fa-angle-double-right"></i> Paramétre Profile</a></li>
										</ul>
									</div>
									<!-- /Footer Widget -->
								</div>
							</div>
						</div>
					</div>
					<!-- /Footer Top -->
				</footer>
				<!-- /Footer -->

			</div>
			<!-- /Main Wrapper -->

			<!-- jQuery -->
			<script src="assets/js/jquery.min.js"></script>

			<!-- Bootstrap Core JS -->
			<script src="assets/js/popper.min.js"></script>
			<script src="assets/js/bootstrap.min.js"></script>

			<!-- Sticky Sidebar JS -->
			<script src="assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
			<script src="assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>

			<!-- Select2 JS -->
			<script src="assets/plugins/select2/js/select2.min.js"></script>

			<!-- Dropzone JS -->
			<script src="assets/plugins/dropzone/dropzone.min.js"></script>

			<!-- Bootstrap Tagsinput JS -->
			<script src="assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js"></script>

			<!-- Profile Settings JS -->
			<script src="assets/js/profile-settings.js"></script>

			<!-- Custom JS -->
			<script src="assets/js/script.js"></script>

		</body>
		
		</html>
	<?php
	} else {
		header('Location: index.php ');
	}

	?>
<?php } ?>