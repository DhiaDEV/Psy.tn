<?php
session_start();
//Connexion a la Base De Données
$bdd = new PDO('mysql:host=localhost; dbname=psy.tn;', 'root', '');

//Sécurité... 
if (!$_SESSION['auth']) {
	header('Location: login.php');
}
if (isset($_GET['AdminID']) and $_GET['AdminID'] > 0) {
	$getid = intval($_GET['AdminID']); //intval pour sécuriser l'id 
	//Récupérer les données de client par l id qui entrer
	$recupAdmin = $bdd->prepare('SELECT * FROM administrateurs WHERE AdminID= ?');
	$recupAdmin->execute(array($getid));
	$AdminInfo = $recupAdmin->fetch();

?>
	<!DOCTYPE html>
	<html lang="en">

	<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/patient-list.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:51 GMT -->

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<title>Listes Patients</title>

		<!-- Favicon -->
		<link rel="shortcut icon" type="image/x-icon" href="assets/img/logoF.png">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">

		<!-- Fontawesome CSS -->
		<link rel="stylesheet" href="assets/css/font-awesome.min.css">

		<!-- Feathericon CSS -->
		<link rel="stylesheet" href="assets/css/feathericon.min.css">

		<!-- Datatables CSS -->
		<link rel="stylesheet" href="assets/plugins/datatables/datatables.min.css">

		<!-- Main CSS -->
		<link rel="stylesheet" href="assets/css/style.css">

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
									<h6><?php echo $AdminInfo['Prenom'] . ' ', '' . $AdminInfo['Nom'] ?></h6>
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
							<li class="active">
								<a href="questions.php?AdminID=<?= $AdminInfo['AdminID'] ?>"><i class="fe fe-question"></i> <span>Questions</span></a>
							</li>
							<li class="menu-title">
								<span>Pages</span>
							</li>
							<li>
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
							<div class="col-sm-12">
								<h3 class="page-title">Questions</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.php?AdminID=<?= $AdminInfo['AdminID'] ?>">Accueil</a></li>
									<li class="breadcrumb-item active">Questions</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Header -->

					<table class="table align-middle mb-0 bg-white">
						<thead class="bg-light">
							<tr>
								<th>Nom</th>
								<th>Titre</th>
								<th>Contenu</th>
								<th>Date Creation</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$recupQuestions = $bdd->prepare('SELECT * FROM questions ORDER BY QuestionID desc');
							$recupQuestions->execute();

							while ($infoQuest = $recupQuestions->fetch()) {

							?>
								<tr>

									<td><?php echo $infoQuest['nom_utilisateur'] ?></td>
									<td><?php echo $infoQuest['titre'] ?></td>
									<td><?php echo nl2br($infoQuest['contenu']) ?></td>
									<td><?php echo $infoQuest['date_creation'] ?></td>
									<td>
										<a class="btn btn-danger btn-sm btn-rounded" href="#"onclick="afficherConfirmation(<?php echo $infoQuest['QuestionID']  ?>)">Supprimer</a>
										<script>
                                        function afficherConfirmation(idQ) {
                                            var confirmation = confirm("Êtes-vous sûr de vouloir supprimer ?");
                                            if (confirmation) {
                                                supprimer(idQ);
                                            }
                                        }

                                        function supprimer(idQ) {
                                            // Vous pouvez rediriger vers la page de suppression :
                                            window.location.href = "supprimerQuestion.php?AdminID=<?= $AdminInfo['AdminID']; ?>&supprimer=" + idQ;

                                            // Après la suppression réussie, afficher une alerte
                                            alert("Suppression effectuée avec succès !");
                                        }
                                    </script>
									</td>
								</tr>
							<?php }?>
						</tbody>
					</table>
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

		<!-- Datatables JS -->
		<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="assets/plugins/datatables/datatables.min.js"></script>

		<!-- Custom JS -->
		<script src="assets/js/script.js"></script>

	</body>

	</html>
<?php } ?>