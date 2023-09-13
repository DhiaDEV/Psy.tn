<?php
session_start();
//Connexion a la Base De Données
$bdd = new PDO('mysql:host=localhost; dbname=psy.tn;', 'root', '');

require_once "mail.php";

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

?>

	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>Rendez-Vous Présentiel</title>
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
								<a href="doctor-dashboard.php?userID=<?= $psyInfo['userID'] ?>">Accueil</a>
							</li>
							<li class="has-submenu active">
								<a href="#">Liens <i class="fas fa-chevron-down"></i></a>
								<ul class="submenu">
									<li><a href="doctor-dashboard.php?userID=<?= $psyInfo['userID'] ?>">Accueil</a></li>
									<li><a href="appointments.php?userID=<?= $psyInfo['userID'] ?>">Rendez-Vous En Ligne</a></li>
									<li class="active"><a href="rendez-vousP.php?userID=<?= $psyInfo['userID'] ?>">Rendez-Vous Présentiel</a></li>
									<li><a href="my-patients.php?userID=<?= $psyInfo['userID'] ?>">Mes Patients</a></li>
									<li><a href="questions.php?userID=<?= $psyInfo['userID'] ?>">Questions</a></li>
									<li><a href="doctor-profile-settings.php?userID=<?= $psyInfo['userID'] ?>">Paramétre Profile</a></li>
								</ul>
							</li>
							<li class="has-submenu">
								<a href="#">Mes Articles <i class="fas fa-chevron-down"></i></a>
								<ul class="submenu">
									<li><a href="ajouterArticle.php?userID=<?= $psyInfo['userID'] ?>">Ajouter des Articles </a></li>

								</ul>
							</li>
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
									<li class="breadcrumb-item active" aria-current="page">Rendez-Vous Présentiel</li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title">Rendez-Vous Présentiel</h2>
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
											<h3>Dr. <?php echo $psyInfo['Prenom'], ' ' . '' . $psyInfo['Nom'] ?></h3>

											<div class="patient-details">
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
											<li class="active">
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
											<li>
												<a href="doctor-profile-settings.php?userID=<?= $psyInfo['userID'] ?>">
													<i class="fas fa-user-cog"></i>
													<span>Paramétre Profile</span>
												</a>
											</li>
											<li>
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
							<div class="appointments">
								<div class="container">
									<form class="d-flex" method="POST">
										<input class="form-control me-2" type="search" name="recherche" placeholder="Recherche rendez-vous " aria-label="Search" autocomplete="off">
										<button class="btn btn-info">Rechercher</button>
									</form>
									<br>
									<!-- Appointment List -->
									<?php
									//pour Refuser un rendez-vous par mail
									if (isset($_GET['refuse']) and !empty($_GET['refuse'])) {
										$refuseEnvoi = (int) $_GET['refuse'];

										$patientMail = $bdd->query("SELECT * FROM  rendez_vous WHERE IDRdv='$refuseEnvoi' ");
										while ($Patient = $patientMail->fetch()) {


											$mail->setFrom('dhiainfo1@gmail.com', 'Dhia');
											$mail->addAddress($Patient['EmailP']);
											$mail->Subject = 'Concernant Votre Rendez-Vous';
											$mail->Body = "Cher " . $Patient['NomP'] . ' ' . $Patient['PrenomP'] . ",<br/>
	 				Nous sommes désolés, mais nous ne pouvons pas honorer votre rendez-vous prévu pour le <b>" . $Patient['Date'] . "</b> 
				   à <b>" . $Patient['Time'] . "</b>. Nous allons changer la date de votre rendez-vous et vous enverrons un nouveau mail avec la nouvelle date dès que possible. 
				   Nous nous excusons pour tout inconvénient que cela pourrait causer. Merci de votre compréhension.";
											$mail->send();
											echo ("<script>alert('Mail bien envoyer')</script>");
										}
									}

									if (isset($_POST['recherche']) && !empty(trim($_POST['recherche']))) {
										$recherche = htmlspecialchars($_POST['recherche']);
										$keywords = preg_split('/\s+/', $recherche); // Utilisez preg_split pour diviser la chaîne de recherche en utilisant les espaces comme délimiteurs.
										$query = 'SELECT * FROM compte_utilisateurs u JOIN rendez_vous r ON u.userID = r.IDPatient WHERE r.IDPsy = ? AND Statut="Nouveau" AND Type="Presentiel"';
										foreach ($keywords as $key => $value) {
											$query .= ' AND (NomP LIKE "%' . $value . '%" OR PrenomP LIKE "%' . $value . '%")';
										}
										$query .= ' ORDER BY IDRdv desc';
										$recupPatient = $bdd->prepare($query);
										$recupPatient->execute(array($getid));
									} else {
										$recupPatient = $bdd->prepare('SELECT * FROM compte_utilisateurs u JOIN rendez_vous r ON u.userID = r.IDPatient WHERE r.IDPsy = ? AND Statut="Nouveau" AND Type="Presentiel" ORDER BY IDRdv desc');
										$recupPatient->execute(array($getid));
									}


									if ($recupPatient->rowCount() > 0) {
										while ($Recrdv = $recupPatient->fetch()) {
									?>

											<div class="appointment-list">
												<div class="profile-info-widget">
													<a href="patient-profile.html" class="booking-doc-img">
														<img src="../Espace Patients/img/<?php echo $Recrdv['PhotoProfile'] ?>" alt="User Image">
													</a>
													<div class="profile-det-info">
														<h3><a href="#"><?php echo $Recrdv['PrenomP'] . ' ' . $Recrdv['NomP']; ?></a></h3>
														<div class="patient-details">
															<h5><i class="far fa-clock"></i> <?php echo $Recrdv['Date'] . ' ' . $Recrdv['Time']; ?></h5>
															<h5><i class="fas fa-envelope"></i> <?php echo $Recrdv['EmailP']; ?></h5>
															<h5 class="mb-0"><i class="fas fa-phone"></i> <?php echo $Recrdv['NumeroP']; ?></h5><br>
														</div>
													</div>
													<div class="appointment-action" style="padding-left: 60px;">
														<?php if ($Recrdv['Confirmer'] == 0) { ?>
															<a href="confirmerPatientP.php?userID=<?php echo $psyInfo['userID']; ?>&confirmer=<?= $Recrdv['IDRdv'] ?>" class="btn btn-sm bg-success-light">
																<i class="fas fa-check"></i> Confirmer
															</a>
														<?php } ?>
														<a href="modifierDateP.php?userID=<?php echo $psyInfo['userID']; ?>&modifier=<?= $Recrdv['IDRdv'] ?>" class="btn btn-sm bg-warning-light">
															<i class="fas fa-calendar-check"></i> Modifier la Date
														</a>
														<a href="rendez-vousP.php?userID=<?php echo $psyInfo['userID']; ?>&refuse=<?= $Recrdv['IDRdv'] ?>" class="btn btn-sm bg-primary-light">
															<i class="far fa-calendar-times"></i> Refuser La Confirmation
														</a>
														<a href="#" class="btn btn-sm bg-danger-light" onclick="afficherConfirmation(<?php echo $Recrdv['IDRdv'] ?>)">
															<i class="fas fa-times"></i> Supprimer
														</a>

														<script>
															function afficherConfirmation(idRDV) {
																var confirmation = confirm("Êtes-vous sûr de vouloir supprimer ?");
																if (confirmation) {
																	supprimer(idRDV);
																}
															}

															function supprimer(idRDV) {
																// Vous pouvez rediriger vers la page de suppression :
																window.location.href = "supprimerRDVP.php?userID=<?php echo $psyInfo['userID']; ?>&supprimer=" + idRDV;


																// Après la suppression réussie, afficher une alerte
																alert("Suppression effectuée avec succès !");
															}
														</script>
													</div>
												</div>
											</div>
									<?php }
									} else {
										echo ' <div class="alert alert-danger" style="width:100%; text-align:center;" role="alert">
						Aucun patient
					</div>';
									}
									?>
									<!-- /Appointment List -->
								</div>
							</div>
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

			<!-- Custom JS -->
			<script src="assets/js/script.js"></script>

	</body>

	</html>
<?php } ?>