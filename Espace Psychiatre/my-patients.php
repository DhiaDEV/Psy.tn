<?php
session_start();
//Connexion a la Base De Données
$bdd = new PDO('mysql:host=localhost; dbname=psy.tn;', 'root', '');

//Sécurité... 
if (!$_SESSION['auth']) {
	header('Location: connexionPsy.php');
}
require_once 'mail.php';

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
		<title>Mes patients</title>
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
									<li><a href="rendez-vousP.php?userID=<?= $psyInfo['userID'] ?>">Rendez-Vous Présentiel</a></li>
									<li class="active"><a href="my-patients.php?userID=<?= $psyInfo['userID'] ?>">Mes Patients</a></li>
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
									<li class="breadcrumb-item active" aria-current="page">Mes patients</li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title">Mes patients</h2>
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
											<li class="active">
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
							<div class="row row-grid">
								<div class="container">
									<form class="d-flex" method="POST">
										<input class="form-control me-2" type="search" name="recherche" placeholder="Recherche Patient ou N°_Dossier " aria-label="Search" autocomplete="off">
										<button class="btn btn-info">Rechercher</button>
									</form>
									<br>
									<?php
									if (isset($_POST['recherche']) && !empty(trim($_POST['recherche']))) {
										$recherche = htmlspecialchars($_POST['recherche']);
										$keywords = preg_split('/\s+/', $recherche); // Utilisez preg_split pour diviser la chaîne de recherche en utilisant les espaces comme délimiteurs.
										$query = 'SELECT u.*, r.*, p.*
										FROM compte_utilisateurs u
										JOIN patients p ON u.userID = p.idPatient
										JOIN (
											SELECT MAX(IDRdv) AS IDRdv, IDPatient
											FROM rendez_vous
											GROUP BY IDPatient
										) AS last_rdv ON last_rdv.IDPatient = u.userID
										JOIN rendez_vous r ON r.IDRdv = last_rdv.IDRdv
										WHERE r.Confirmer = 1  AND r.IDPsy = ?';

										foreach ($keywords as $key => $value) {
											$query .= ' AND (NomP LIKE "%' . $value . '%" OR PrenomP LIKE "%' . $value . '%" OR N_Dossier LIKE "%' . $value . '%")';
										}
										$query .= ' ORDER BY last_rdv.IDRdv DESC';
										$recupPatient = $bdd->prepare($query);
										$recupPatient->execute(array($getid));
									} else {
										$recupPatient = $bdd->prepare('SELECT u.*, r.*, p.*
										FROM compte_utilisateurs u
										JOIN patients p ON u.userID = p.idPatient
										JOIN (
											SELECT MAX(IDRdv) AS IDRdv, IDPatient
											FROM rendez_vous
											GROUP BY IDPatient
										) AS last_rdv ON last_rdv.IDPatient = u.userID
										JOIN rendez_vous r ON r.IDRdv = last_rdv.IDRdv
										WHERE r.Confirmer = 1 AND r.IDPsy = ?
										ORDER BY last_rdv.IDRdv DESC');
										$recupPatient->execute(array($getid));
									}
									//pour la nouveau  rendez-vous par mail
									if (isset($_GET['nouv']) && !empty($_GET['nouv'])) {
										$nouvEnvoi = (int)$_GET['nouv'];

										$patientMail = $bdd->query("SELECT * FROM rendez_vous r JOIN psychiatres p ON r.IDPsy = p.PsyID WHERE IDRdv='$nouvEnvoi'");
										if ($patientMail->rowCount() > 0) {
											$Patient = $patientMail->fetch();

											if ($Patient['Type'] == 'En_ligne') {
												$mail->setFrom('dhiainfo1@gmail.com', 'Dhia');
												$mail->addAddress($Patient['EmailP']);
												$mail->Subject = 'Concernant Votre Rendez-Vous';
												$mail->Body = "Bonjour " . $Patient['NomP'] . ' ' . $Patient['PrenomP'] . ",<br/>
											Nous sommes heureux de vous informer que nous avons réservé pour vous un nouveau rendez-vous pour le <b>" . $Patient['Date'] . "</b> à <b>" . $Patient['Time'] . "</b>. Nous espérons que cette date vous conviendra mieux. Si vous avez des questions ou des préoccupations,
											n'hésitez pas à nous contacter. Nous avons hâte de vous voir bientôt.
											Avant votre nouveau rendez-vous, veuillez noter que le paiement doit être effectué au moins 15 minutes avant la séance. Après avoir payé, veuillez attendre l'appel du docteur pour vous connecter à la consultation. 
											Nous vous remercions de votre coopération et de votre compréhension.";
												$mail->send();
												echo ("<script>alert('Mail bien envoyer')</script>");
											} elseif ($Patient['Type'] == 'Presentiel') {
												$mail->setFrom('dhiainfo1@gmail.com', 'Dhia');
												$mail->addAddress($Patient['EmailP']);
												$mail->Subject = 'Concernant Votre Rendez-Vous Présentiel';
												$mail->Body = "Cher " . $Patient['NomP'] . ' ' . $Patient['PrenomP'] . ",<br/>
										Nous sommes ravis de vous confirmer votre nouveau rendez-vous en personne. Voici les détails de votre rendez-vous.
										<br>
										Date : [<b>" . $Patient['Date'] . "</b>]
										<br>
										Heure : [<b>" . $Patient['Time'] . "</b>]
										<br>
										Lieu : [<b>" . $Patient['Adresse'] . "</b>]";

												$mail->send();
												echo ("<script>alert('Mail bien envoyer')</script>");
											}
										} else {
											echo ("<script>alert('Aucun résultat trouvé pour le rendez-vous ID $nouvEnvoi')</script>");
										}
									} else {
										echo ("<script>alert('Paramètre 'nouv' manquant ou vide')</script>");
									}

									//pour Confirmer un rendez-vous par mail
									if (isset($_GET['mail']) and !empty($_GET['mail'])) {
										$mailEnvoi = (int) $_GET['mail'];

										//$patientMail = $bdd->query("SELECT * FROM rendez_vous r JOIN psychiatres p ON r.IDPsy = p.PsyID WHERE IDRdv='$mailEnvoi' ");
										$patientMail = $bdd->query("SELECT * FROM  rendez_vous WHERE IDRdv='$mailEnvoi' ");

										if ($patientMail->rowCount() > 0) {
											$Patient = $patientMail->fetch();

											if ($Patient['Type'] == 'En_ligne') {

												$mail->setFrom('dhiainfo1@gmail.com', 'Dhia');
												$mail->addAddress($Patient['EmailP']);
												$mail->Subject = 'Concernant Votre Rendez-Vous';
												$mail->Body = "Bonjour " . $Patient['NomP'] . ' ' . $Patient['PrenomP'] . ",<br/>
											j'espére que vous allez bien. Je vous confirme que j'ai bien accepté votre rendez-vous pour le <b>" . $Patient['Date'] . "</b> 
											à <b>" . $Patient['Time'] . "</b>.<br/>
											Avant votre rendez-vous, veuillez noter que le paiement doit être effectué au moins 15 minutes avant la séance. Après avoir payé,
											veuillez attendre l'appel du docteur pour vous connecter à la consultation. Nous vous remercions de votre coopération et de votre compréhension.";
												$mail->send();
												echo ("<script>alert('Mail bien envoyer')</script>");
											} elseif ($Patient['Type'] == 'Presentiel') {
												$mail->setFrom('dhiainfo1@gmail.com', 'Dhia');
												$mail->addAddress($Patient['EmailP']);
												$mail->Subject = 'Concernant Votre Rendez-Vous Présentiel';
												$mail->Body = "Cher " . $Patient['NomP'] . ' ' . $Patient['PrenomP'] . ",<br/>
										J'espère que vous allez bien. Je tenais à vous envoyer ce courriel pour confirmer notre rendez-vous pour une consultation en personne.
										<br>
										Date : [<b>" . $Patient['Date'] . "</b>]
										<br>
										Heure : [<b>" . $Patient['Time'] . "</b>]
										<br>
										Lieu : [<b>" . $Patient['Adresse'] . "</b>]";

												$mail->send();
												echo ("<script>alert('Mail bien envoyer')</script>");
											}
										} else {
											echo ("<script>alert('Aucun résultat trouvé pour le rendez-vous ID $nouvEnvoi')</script>");
										}
									} else {
										echo ("<script>alert('Paramètre 'nouv' manquant ou vide')</script>");
									}

									?>
								</div>
							</div>

							<?php
							if ($recupPatient->rowCount() > 0) {
								while ($Recrdv = $recupPatient->fetch()) {
							?>

									<div class="card mb-3">
										<div class="card-body">
											<img src="../Espace Patients/img/<?php echo $Recrdv['PhotoProfile']; ?>" width="10%" height="50" alt="Photo de profile" class="img-thumbnail">

											<h5 class="card-title"><?php echo $Recrdv['NomP'] . ' ' . $Recrdv['PrenomP']; ?></h5>
											<?php if ($Recrdv['N_Dossier'] == 0) { ?>
												<a href="ajoutNumDossier.php?userID=<?php echo $psyInfo['userID'] ?>&IDPatient=<?php echo $Recrdv['IDPatient'] ?>" class="btn btn-info float-right" style="font-size: 11px;">Ajouter un numéro de dossier</a>
											<?php } ?>
											<br><br>
											<table class="table">
												<thead>
													<tr>
														<th scope="col">Nom</th>
														<th scope="col">Valeur</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Date de rendez-vous</td>
														<td><?php echo $Recrdv['Date'] . ' ' . $Recrdv['Time']; ?></td>
													</tr>
													<tr>
														<td>Adresse e-mail</td>
														<td><?php echo $Recrdv['EmailP']; ?></td>
													</tr>
													<tr>
														<td>Téléphone</td>
														<td><?php echo $Recrdv['NumeroP']; ?></td>
													</tr>

													<tr>
														<td>Statut</td>
														<td><?php echo $Recrdv['Statut']; ?></td>
													</tr>
													<tr>
														<td>Type De Rendez-Vous</td>
														<td><?php echo $Recrdv['Type']; ?></td>
													</tr>
													<?php
													if ($Recrdv['N_Dossier'] > 0) {
													?>
														<tr>
															<td>N° De Dossier</td>
															<td><?php echo $Recrdv['N_Dossier']; ?></td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
											<a class="btn btn-light" href="my-patients.php?userID=<?php echo $psyInfo['userID']; ?>&mail=<?= $Recrdv['IDRdv']; ?>" style="margin-left: 10px; margin-top: 10px;">Mail De Confirmation</a>
											<a class="btn btn-success" href="my-patients.php?userID=<?php echo $psyInfo['userID'] ?>&nouv=<?= $Recrdv['IDRdv'] ?>" style="margin-left: 10px ; margin-top:10px;">Envoyer la nouvelle date</a>
											<?php
											// Vérifier si l'IDPatient existe dans la table "dossier_médicale"
											$patientId = $Recrdv['IDPatient'];
											$requete = $bdd->prepare('SELECT COUNT(*) AS count FROM dossier_medical WHERE IDPatient = :id_patient');
											$requete->bindParam(':id_patient', $patientId);
											$requete->execute();
											$resultat = $requete->fetch();

											if ($resultat['count'] > 0) {
												$recupID_DM = $bdd->query('SELECT idDM FROM dossier_medical ');
												$res = $recupID_DM->fetch();
												// Si l'IDPatient existe déjà, modifier le lien pour pointer vers "mettreAjour.php"
												echo '<a class="btn btn-warning" href="mettreAjourDossier.php?userID=' . $psyInfo['userID'] . '&IDDossier=' . $res['idDM'] . '" style="margin-left: 10px ; margin-top:10px;">Mettre à jour le dossier médical</a>';
											} else {
												// Si l'IDPatient n'existe pas, utiliser le lien existant pour "dossier_medicale.php"
												echo '<a class="btn btn-warning" href="dossier_medicale.php?userID=' . $psyInfo['userID'] . '&IDPatient=' . $Recrdv['IDPatient'] . '" style="margin-left: 10px ; margin-top:10px;">Dossier Médical</a>';
											}
											?>
										</div>
									</div>
							<?php
								}
							} else {
								echo ' <div class="alert alert-danger" style="width:100%; text-align:center;" role="alert">
									Aucun patient
	  							</div>';
							} ?>
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
									<li><a href="my-patients.php?userID=<?php echo $psyInfo['userID'] ?>"><i class="fas fa-angle-double-right"></i> Mon Patients</a></li>
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