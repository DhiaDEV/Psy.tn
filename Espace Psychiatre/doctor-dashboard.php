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

?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>Psychiatre</title>
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
		<!-- Button appel-->
		<link rel="stylesheet" href="assets/css/Appel.css">
		<style>

		</style>

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
									<li class="active"><a href="doctor-dashboard.php?userID=<?= $psyInfo['userID'] ?>">Accueil</a></li>
									<li><a href="appointments.php?userID=<?= $psyInfo['userID'] ?>">Rendez-Vous En Ligne</a></li>
									<li><a href="rendez-vousP.php?userID=<?= $psyInfo['userID'] ?>">Rendez-Vous Présentiel</a></li>
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
						</ul>
					</div>
					<ul class="nav header-navbar-rht">
						<li class="nav-item contact-item">
						</li>

						<!-- User Menu -->
						<li class="nav-item dropdown has-arrow logged-item">
							<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
								<span class="user-img">
									<img class="rounded-circle" src="images/profileImage/<?php echo $psyInfo['PhotoProfile'] ?>" width="31" >
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
									<li class="breadcrumb-item active" aria-current="page">Accueil</li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title">Accueil</h2>
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
											<li class="active">
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
											<li>
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

							<div class="row">
								<div class="col-md-12">
									<div class="card dash-card">
										<div class="card-body">
											<div class="row">
												<div class="col-md-12 col-lg-4">
													<div class="dash-widget dct-border-rht">
														<?php
														// Récupérer le nombre total de rendez-vous pour le psychiatre spécifique								
														$stmt = $bdd->prepare("SELECT COUNT(*) as total FROM rendez_vous WHERE IDPsy = ?");
														$stmt->execute(array($getid));
														$result = $stmt->fetch(PDO::FETCH_ASSOC);
														$total = $result['total'];

														// Récupérer le nombre total de rendez-vous dans la base de données								
														$stmt = $bdd->prepare("SELECT COUNT(*) as total_tasks FROM rendez_vous");
														$stmt->execute();
														$result = $stmt->fetch(PDO::FETCH_ASSOC);
														$total_tasks = $result['total_tasks'];

														if ($total > 0) {
															// Calculate the percentage only if $total is not zero
															$percentage = ($total_tasks / $total) * 100;
														} else {
															// Set the percentage to zero if $total is zero
															$percentage = 0;
														}



														// Générer le code HTML et CSS pour le graphique circulaire
														echo '<div class="circle-bar circle-bar1">';
														echo '<div class="circle-graph1" data-percent="' . $percentage . '">';
														echo '<img src="assets/img/icon-01.png" class="img-fluid" alt="patient">';
														echo '</div>';
														echo '</div>';
														?>
														<?php
														$stmt = $bdd->prepare("SELECT COUNT(*) as total_patients  FROM compte_utilisateurs u JOIN rendez_vous r ON u.userID = r.IDPatient WHERE IDPsy= ?");
														$stmt->execute(array($getid));
														$row = $stmt->fetch(PDO::FETCH_ASSOC);
														$total_patients = $row['total_patients'];
														?>
														<div class="dash-widget-info">
															<h6>Nombre total de patients</h6>
															<h3><?php echo $total_patients; ?></h3>
															<p class="text-muted">Jusqu'à aujourd'hui</p>
														</div>
													</div>
												</div>

												<div class="col-md-12 col-lg-4">
													<div class="dash-widget dct-border-rht">
														<div class="circle-bar circle-bar2">
															<?php
															// Récupère la date actuelle au format AAAA-MM-JJ
															$date = date('Y-m-d');

															// Requête pour obtenir le nombre de patients avec rendez-vous pour la date actuelle
															$stmt = $bdd->prepare("SELECT COUNT(*) AS num_patients FROM compte_utilisateurs u JOIN rendez_vous r ON u.userID = r.IDPatient WHERE IDPsy= :IDPsy  AND Date = :date AND Confirmer = 1");
															$stmt->bindParam(':IDPsy', $getid);
															$stmt->bindParam(':date', $date);
															$stmt->execute();
															$result = $stmt->fetch(PDO::FETCH_ASSOC);
															$num_patients = $result['num_patients'];

															// Requête pour obtenir le nombre maximum de rendez-vous possible pour la date actuelle
															$stmt = $bdd->prepare("SELECT COUNT(*) AS max_appointments FROM compte_utilisateurs u JOIN rendez_vous r ON u.userID = r.IDPsy WHERE IDPsy= :IDPsy AND Date = :date");
															$stmt->bindParam(':IDPsy', $getid);
															$stmt->bindParam(':date', $date);
															$stmt->execute();
															$result = $stmt->fetch(PDO::FETCH_ASSOC);
															$max_appointments = $result['max_appointments'];

															if ($max_appointments > 0) {
																// Calculate the percentage only if $max_appointments is not zero
																$percentage = ($num_patients / $max_appointments) * 100;
															} else {
																// Set the percentage to zero if $max_appointments is zero
																$percentage = 0;
															}

															// Calculer le pourcentage en fonction des données récupérées
															?>
															<div class="circle-graph2" data-percent="<?php echo $percentage; ?>">
																<img src="assets/img/icon-02.png" class="img-fluid" alt="Patient">
															</div>
														</div>
														<div class="dash-widget-info">
															<h6>Aujourd'hui patient</h6>
															<h3><?php echo $num_patients; ?></h3>
															<p class="text-muted"><?php echo date('d, M Y'); ?></p>
														</div>
													</div>
												</div>


												<div class="col-md-12 col-lg-4">
													<div class="dash-widget">
														<div class="circle-bar circle-bar3">
															<?php
															// Requête pour obtenir le nombre total de rendez-vous
															$stmt = $bdd->prepare("SELECT COUNT(*) AS total_RDV FROM rendez_vous");
															$stmt->execute();
															$result = $stmt->fetch(PDO::FETCH_ASSOC);
															$total_rdv = $result['total_RDV'];

															// Requête pour obtenir le nombre de rendez-vous confirmés pour le psychologue actuel
															$stmt = $bdd->prepare("SELECT COUNT(*) AS num_RDV FROM compte_utilisateurs u JOIN rendez_vous r ON u.userID = r.IDPatient WHERE IDPsy = :IDPsy AND Confirmer = 1");
															$stmt->bindParam(':IDPsy', $getid);
															$stmt->execute();
															$result = $stmt->fetch(PDO::FETCH_ASSOC);
															$num_rdv = $result['num_RDV'];

															if ($total_rdv > 0) {
																// Calculate the percentage only if $total_rdv is not zero
																$percentage = ($num_rdv / $total_rdv) * 100;
															} else {
																// Set the percentage to zero if $total_rdv is zero
																$percentage = 0;
															}



															//Génération du code HTML et CSS du graphe circulaire
															echo '<div class="circle-graph3" data-percent="' . $percentage . '">';
															echo '<img src="assets/img/icon-03.png" class="img-fluid" alt="Patient">';
															echo '</div>';
															?>
														</div>
														<div class="dash-widget-info">
															<h6>Rendez-Vous</h6>
															<h3><?php echo $num_rdv; ?></h3>
															<p class="text-muted"><?php echo date('d, M Y'); ?></p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<h4 class="mb-4">Rendez-Vous Patients</h4>
									<div class="appointment-tab">

										<!-- Appointment Tab -->
										<ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded">
											<li class="nav-item">
												<a class="nav-link" href="#today-appointments" data-toggle="tab">Aujourd'hui</a>
											</li>
										</ul>
										<!-- /Appointment Tab -->

										<div class="tab-content">

											<!-- Today Appointment Tab -->
											<?php
											$date = date('Y-m-d');
											$stmt = $bdd->prepare("SELECT * FROM compte_utilisateurs u JOIN rendez_vous r ON u.userID = r.IDPatient WHERE IDPsy= :IDPsy AND Date = :Date AND Confirmer= 1 ORDER BY Time");
											$stmt->bindParam(':IDPsy', $getid);
											$stmt->bindParam(':Date', $date);
											$stmt->execute();
											?>

											<div class="tab-pane" id="today-appointments">
												<div class="card card-table mb-0">
													<div class="card-body">
														<div class="table-responsive">
															<table class="table table-hover table-center mb-0">
																<thead>
																	<tr>
																		<th>Nom Patient</th>
																		<th>Email Patient</th>
																		<th>Date RDV</th>
																		<th>Payé</th>
																		<th>Appelle</th>
																		<th></th>
																	</tr>
																</thead>
																<tbody>

																	<?php foreach ($stmt as $patient) { ?>
																		<tr>
																			<td>
																				<h2 class="table-avatar">
																					<a href="#" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="../Espace Patients/img/<?php echo $patient['PhotoProfile'] ?>" alt="User Image"></a>
																					<a href="#"><?php echo $patient['PrenomP'] . ' ', '' . $patient['NomP'] ?></a>
																				</h2>
																			</td>
																			<td><?php echo $patient['Email'] ?></td>
																			<td><?php echo $patient['Date'] ?><span class="d-block text-info"><?php echo $patient['Time'] ?></span></td>
																			<?php if ($patient['paye'] == "") { ?>
																				<td><button style="border-radius: 20px; font-size:13px;" class="btn btn-danger">Non Payé</button></td>
																			<?php } else { ?>
																				<td><button style="border-radius: 20px; font-size:13px;" class="btn btn-success">Payé</button></td>
																			<?php } ?>
																			<td> <a href="./ChatLive_WebRTC/home.php" class="btn btn-success Appel">Appeler</a> </td>

																		</tr>
																	<?php } ?>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
											<!-- /Today Appointment Tab -->

										</div>
									</div>
								</div>
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

		<!-- Circle Progress JS -->
		<script src="assets/js/circle-progress.min.js"></script>

		<!-- Custom JS -->
		<script src="assets/js/script.js"></script>

	</body>

	</html>

<?php } ?>