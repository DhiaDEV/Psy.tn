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


	// Vérification de l'envoi du formulaire
	if (isset($_POST['valider'])) {
		// Vérification des champs requis
		if (!empty($_POST['titre'])  && !empty($_POST['desc'])) {
			// Traitement des données envoyées
			$titre = htmlspecialchars($_POST['titre']);
			$desc = htmlspecialchars($_POST['desc']);
			$photoType = $_FILES["photo"]["type"];
			$photoName = $_FILES["photo"]["name"];
			$photo = $_FILES["photo"]["tmp_name"];
			move_uploaded_file($photo, "images/Articles/" . $photoName);
			$position = "images/Articles/" . $photoName;

			// Insertion des données dans la base de données
			$insertArticles = $bdd->prepare('INSERT INTO articles(idPSY, titre, PhotoA, DescriptionA) VALUES (?, ?, ?, ?)');
			$insertArticles->execute(array($getid, $titre, $photoName, $desc));

			// Message d'alerte JavaScript pour indiquer l'insertion réussie des données et redirection
			echo "<script>alert('L\\'article a été enregistré avec succès!');
				window.location='ajouterArticle.php?userID=" . $psyInfo['userID'] . "';</script>";


			exit(); // Arrêt du script après redirection
		} else {
			// Affichage d'un message d'erreur si des champs requis sont manquants
			$errorMsg = "<div class='alert alert-danger coloor' role='alert' style=' font-weight: 700;'>
			Veuillez complèter tous les champs
			</div> ";
		}
	}
?>

	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>Ajouter Des Articles </title>
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
							<li class="has-submenu">
								<a href="#">Liens <i class="fas fa-chevron-down"></i></a>
								<ul class="submenu">
									<li><a href="doctor-dashboard.php?userID=<?= $psyInfo['userID'] ?>">Accueil</a></li>
									<li><a href="appointments.php?userID=<?= $psyInfo['userID'] ?>">Rendez-Vous En Ligne</a></li>
									<li><a href="rendez-vousP.php?userID=<?= $psyInfo['userID'] ?>">Rendez-Vous Présentiel</a></li>
									<li><a href="my-patients.php?userID=<?= $psyInfo['userID'] ?>">Mes Patients</a></li>
									<li><a href="questions.php?userID=<?= $psyInfo['userID'] ?>">Questions</a></li>
									<li><a href="doctor-profile-settings.php?userID=<?= $psyInfo['userID'] ?>">Paramétre Profile</a></li>
								</ul>
							</li>
							<li class="has-submenu active">
								<a href="#">Mes Articles <i class="fas fa-chevron-down"></i></a>
								<ul class="submenu">
									<li class="active"><a href="ajouterArticle.php">Ajouter Des Articles</a></li>
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
									<img class="rounded-circle" src="images/profileImage/<?php echo $psyInfo['PhotoProfile'] ?>" width="31">
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
									<li class="breadcrumb-item active" aria-current="page">Ajouter Des Articles</li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title">Ajouter Des Articles</h2>
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
							<div class="col-lg-12">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title">Ajouter Des Articles </h4>
									</div>
									<div class="card-body">
										<form method="POST" enctype="multipart/form-data">
											<div class="form-group row">
												<label class="col-form-label col-md-2">Titre</label>
												<div class="col-md-10">
													<input type="text" name="titre" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-form-label col-md-2">Photo</label>
												<div class="col-md-10">
													<input class="form-control" name="photo" type="file">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-form-label col-md-2">Description</label>
												<div class="col-md-10">
													<textarea name="desc" rows="5" cols="5" class="form-control" placeholder="Entrez du texte ici"></textarea>
												</div>
											</div>
											<button class="btn btn-primary" type="submit" name="valider">Ajouter</button>
										</form>
									</div>
								</div>
							</div>
							<div class="container"><?php if (isset($errorMsg)) {
														echo '<p>' . $errorMsg . '</p>';
													} ?></div>
						</div>
					</div>
				</div>
				<!-- /Page Content -->

				<!-- Tableau Pour gérer articles  -->
				<h1 style="text-align: center; color:#242038;">Gérer vos Articles</h1>
				<br>
				<div class="container">
					<table class="table">
						<thead>
							<tr>
								<th scope="col">Titre</th>
								<th scope="col">Photo</th>
								<th scope="col">Description</th>
								<th scope="col">Action</th>

							</tr>
						</thead>
						<tbody>
							<?php
							$recupArticles = $bdd->prepare('SELECT * FROM articles a JOIN compte_utilisateurs u ON a.idPSY = u.userID WHERE a.idPSY = ? order by ArticleID desc');
							$recupArticles->execute(array($getid));

							while ($infoArticle = $recupArticles->fetch()) {
							?>

								<tr>
									<td><?php echo $infoArticle['titre'] ?></td>
									<td><img src="./images/Articles/<?php echo $infoArticle['PhotoA'] ?>" width="200px" height="150px" alt="User Image"></td>
									<td><?php echo $infoArticle['DescriptionA'] ?></td>
									<td>
										<a href="#" class="btn btn-danger" style="font-size: 11px;" onclick="afficherConfirmation(<?php echo $infoArticle['ArticleID'] ?>)">
											Supprimer Article
										</a>
									</td>
								</tr>

								<script>
									function afficherConfirmation(articleID) {
										var confirmation = confirm("Êtes-vous sûr de vouloir supprimer ?");
										if (confirmation) {
											supprimer(articleID);
										}
									}

									function supprimer(articleID) {
										// Vous pouvez rediriger vers la page de suppression :
										window.location.href = "supprimerArticles.php?userID=<?php echo $psyInfo['userID'] ?>&supprimer=" + articleID;

										// Après la suppression réussie, afficher une alerte
										alert("Suppression effectuée avec succès !");
									}
								</script>

							<?php } ?>
						</tbody>
					</table>
				</div>
				<!-- Tableau Pour gérer articles  -->
				<br>
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
											<img src="assets/img/logoF.png" width="100%" height="auto" alt="logo">
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