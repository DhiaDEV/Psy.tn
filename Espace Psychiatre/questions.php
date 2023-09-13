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
	ob_start(); // start output buffering

?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>Questions</title>
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
									<li><a href="my-patients.php?userID=<?= $psyInfo['userID'] ?>">Mes Patients</a></li>
									<li><a href="doctor-profile-settings.php?userID=<?= $psyInfo['userID'] ?>">Paramétre Profile</a></li>
									<li class="active"><a href="questions.php?userID=<?= $psyInfo['userID'] ?>">Questions</a></li>
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
									<li class="breadcrumb-item active" aria-current="page">Questions</li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title">Questions</h2>
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
											<li>
												<a href="my-patients.php?userID=<?= $psyInfo['userID'] ?>">
													<i class="fas fa-user-injured"></i>
													<span>Mes patients</span>
												</a>
											</li>

											<li class="active">
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
							<div class="doc-review review-listing">
								<!-- Review Listing -->
								<ul class="comments-list">

									<?php
									$recuptQ = $bdd->prepare('SELECT q.*, u.*, COUNT(r.ReponseID) AS nbReponses
										FROM questions q
										JOIN compte_utilisateurs u ON q.utilisateur_id = u.userID
										LEFT JOIN reponses r ON q.QuestionID = r.question_id
										GROUP BY q.QuestionID
										ORDER BY q.date_creation DESC');
									$recuptQ->execute();


									while ($row = $recuptQ->fetch()) {
										// Récupération des réponses associées à la question
										$recuptR = $bdd->prepare('SELECT r.*, u.*
																	  FROM reponses r
																	  JOIN compte_utilisateurs u ON r.utilisateur_id = u.userID
																	  WHERE r.question_id = :question_id
																	  ORDER BY r.date_creation');
										$recuptR->execute(array('question_id' => $row['QuestionID']));

										// Affichage de la question
									?>
										<li>
											<div class="comment">
												<?php if ($row['nom_utilisateur'] == 'Anonyme') { ?>
													<img class="avatar rounded-circle" alt="User Image" src="../Espace Patients/img/avatar.avif">
												<?php } else { ?>
													<img class="avatar rounded-circle" alt="User Image" src="../Espace Patients/img/<?php echo $row['PhotoProfile'] ?>">
												<?php } ?>
												<div class="comment-body">
													<div class="meta-data">
														<?php if ($row['nom_utilisateur'] != 'Anonyme') { ?>

															<span class="comment-author"><?php echo $row['Prenom'] . ' ', '' . $row['Nom'] ?></span>
														<?php } else { ?>
															<span class="comment-author"><?php echo $row['nom_utilisateur']  ?></span>

														<?php } ?>

														<span class="comment-date"><?php echo $row['date_creation'] ?> </span>
													</div>
													<p class="recommended"> <?php echo $row['titre'] ?> </p>
													<p class="comment-content">
														<?php echo $row['contenu'] ?>
													</p>
													<?php
													if (isset($_POST['envoyer'])) {
														if (!empty($_POST['reponse'])) {
															$repons = htmlspecialchars($_POST['reponse']);
															$question_id = $_POST['question_id'];
															$insertRep = $bdd->prepare('INSERT INTO reponses(contenu , utilisateur_id , question_id) VALUES (?,?,?)');
															$insertRep->execute(array($repons, $getid, $question_id));
															header('Location:questions.php?userID=' . $psyInfo['userID']); // send header
															exit();
														}
													}

													?>

												</div>
											</div>

											<!-- Affichage les réponses -->
											<ul class="comments-reply response-list-hidden">

												<?php while ($rowR = $recuptR->fetch()) { ?>
													<li>
														<div class="comment ">
															<?php
															if ($rowR['Role'] == 1) {
																// Si la réponse est de l'utilisateur, afficher sa propre photo de profil
															?>
																<img class="avatar rounded-circle" alt="User Image" src="./images/profileImage/<?php echo $rowR['PhotoProfile'] ?>">
															<?php
															} elseif ($row['nom_utilisateur'] != 'Anonyme') {
																// Sinon, afficher la photo de profil du psychiatre
															?>
																<img src="../Espace Patients/img/<?php echo $rowR['PhotoProfile'] ?>" width="40px" height="40px" class="d-block ui-w-40 rounded-circle" alt="">
															<?php
															} elseif ($row['nom_utilisateur'] == 'Anonyme') { ?>

																<img src="../Espace Patients/img/avatar.avif" width="40px" height="40px" class="d-block ui-w-40 rounded-circle" alt="">
															<?php
															} else {
																// Sinon, afficher la photo de profil de l'utilisateur de la réponse
															?>
																<img src="./Espace Patients/img/<?php echo $rowR['PhotoProfile'] ?>" width="40px" height="40px" class="d-block ui-w-40 rounded-circle" alt="">
															<?php
															}
															?>
															<div class="comment-body">
																<div class="meta-data">
																	<?php if ($rowR['Role'] == 1) { ?>
																		<span class="comment-author"><?php echo $rowR['Prenom'] . ' ', '' . $rowR['Nom'] ?></span>
																	<?php } elseif ($rowR['Role'] == 0 and $row['nom_utilisateur'] == 'Anonyme') { ?>
																		<span class="comment-author"><?php echo $row['nom_utilisateur'] ?></span>
																	<?php } else { ?>
																		<span class="comment-author"><?php echo $rowR['Prenom'] . ' ', '' . $rowR['Nom'] ?></span>
																	<?php } ?>
																	<span class="comment-date"><?php echo $rowR['date_creation'] ?> </span>
																</div>
																<p class="comment-content">
																	<?php echo $rowR['contenu'] ?>
																</p>
																<?php
																if (isset($_POST['envoyer'])) {
																	if (!empty($_POST['reponse'])) {
																		$repons = htmlspecialchars($_POST['reponse']);
																		$question_id = $_POST['question_id'];
																		$insertRep = $bdd->prepare('INSERT INTO reponses(contenu , utilisateur_id , question_id) VALUES (?,?,?)');
																		$insertRep->execute(array($repons, $getid, $question_id));
																		header('Location:questions.php?userID=' . $psyInfo['userID']); // send header
																		exit();
																	}
																}

																?>

															</div>
														</div>

													</li>

												<?php } ?>
											</ul>

											<div class="show-response-btn " style="margin-left:800px;">
												<button class="btn btn-dark" style="font-size: 10px; border-radius:5px;" onclick="showResponses(this)"><i class="fas fa-eye"></i> Afficher les réponses</button>
											</div>
											<div class="comment-reply" style="margin-left:10px">
												<form action="" method="POST">
													<input type="hidden" name="question_id" value="<?php echo $row['QuestionID'] ?>">
													<input type="text" name="reponse">
													<button class="comment-btn" name="envoyer" style="border: 0; background-color:white; color:#0de0fe;"><i class="fas fa-reply"></i> Répondre</button>
												</form>
											</div>
										</li>
									<?php }
									ob_end_flush(); // flush the output buffer
									?>
									<!-- /Comment List -->
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
		<style>
			.response-list-hidden {
				display: none;
			}
		</style>
		<script>
			function hideResponses(button) {
				var responseList = button.parentNode.previousElementSibling;
				responseList.classList.add("response-list-hidden");
				button.textContent = "Afficher les réponses";
				$(button).html('<i class="fas fa-eye"></i> ' + button.textContent);
				button.onclick = function() {
					showResponses(button);
				};
			}

			function showResponses(button) {
				var responseList = button.parentNode.previousElementSibling;
				responseList.classList.remove("response-list-hidden");
				button.textContent = "Masquer les réponses";
				$(button).html('<i class="fas fa-eye-slash"></i> ' + button.textContent);
				button.onclick = function() {
					hideResponses(button);
				};
			}

			function toggleResponses(button) {
				var responseList = button.parentNode.previousElementSibling;
				if (responseList.classList.contains("response-list-hidden")) {
					showResponses(button);
				} else {
					hideResponses(button);
				}
			}
		</script>

	</body>

	</html>
<?php } ?>