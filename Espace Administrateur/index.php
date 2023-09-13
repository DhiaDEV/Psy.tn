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

	<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:20 GMT -->

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<title>Accueil: Administrateur</title>

		<!-- Favicon -->
		<link rel="shortcut icon" type="image/x-icon" href="assets/img/logoF.png">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">

		<!-- Fontawesome CSS -->
		<link rel="stylesheet" href="assets/css/font-awesome.min.css">

		<!-- Feathericon CSS -->
		<link rel="stylesheet" href="assets/css/feathericon.min.css">

		<link rel="stylesheet" href="assets/plugins/morris/morris.css">

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

				<div class="top-nav-search">
					<form method="POST">
						<input type="text" name="recherche" class="form-control" placeholder="Recherche" autocomplete="off">
						<button class="btn" name="rechercher" type="submit"><i class="fa fa-search"></i></button>
					</form>
				</div>

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
							<li class="active">
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
								<h3 class="page-title">Bienvenue Administrateur!</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item active">Accueil</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Header -->

					<?php
					// Query to fetch the total number of psychiatrists
					$query = "SELECT COUNT(*) FROM compte_utilisateurs u JOIN psychiatres p ON u.userID= p.PsyID WHERE Role = 1 AND p.Confirmer= 1 ";
					$stmt = $bdd->prepare($query);
					$stmt->execute();
					$psychiatristsCount = $stmt->fetchColumn();

					// Query to fetch the number of available psychiatrists
					$query = "SELECT COUNT(*) FROM compte_utilisateurs  ";
					$stmt = $bdd->prepare($query);
					$stmt->execute();
					$psychiatreNonConfirme = $stmt->fetchColumn();

					// Calculate the progress percentage
					if ($psychiatreNonConfirme != 0) {
						$progressPercentage = ($psychiatristsCount / $psychiatreNonConfirme) * 100;
					} else {
						$progressPercentage = 0;
					}	?>
					<div class="row">
						<!-- Psychiatrists count and progress bar card -->
						<div class="col-xl-4 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon text-primary border-primary">
											<i class="fe fe-users"></i>
										</span>
										<div class="dash-count">
											<h3><?php echo $psychiatristsCount ?></h3>
										</div>
									</div>
									<div class="dash-widget-info">
										<h6 class="text-muted">Psychiatres</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $progressPercentage ?>%;" aria-valuenow="<?php echo $progressPercentage ?>" aria-valuemin="0" aria-valuemax="1000"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
						// Query to fetch the total number of patient
						$query = "SELECT COUNT(*) FROM compte_utilisateurs  WHERE Role = 0 ";
						$stmt = $bdd->prepare($query);
						$stmt->execute();
						$patientCount = $stmt->fetchColumn();

						// Query to fetch the number of available psychiatrists
						$query = "SELECT COUNT(*) FROM compte_utilisateurs  ";
						$stmt = $bdd->prepare($query);
						$stmt->execute();
						$patient = $stmt->fetchColumn();

						// Calculate the progress percentage
						if ($patient != 0) {
							$progressPercentage = ($patientCount / $patient) * 100;
						} else {
							$progressPercentage = 0;
						}					?>

						<div class="col-xl-4 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon text-success">
											<i class="fe fe-credit-card"></i>
										</span>
										<div class="dash-count">
											<h3><?php echo $patientCount ?></h3>
										</div>
									</div>
									<div class="dash-widget-info">

										<h6 class="text-muted">Patients</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $progressPercentage ?>%;" aria-valuenow="<?php echo $progressPercentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
						// Query to fetch the total number of patient
						$query = "SELECT COUNT(*) FROM compte_utilisateurs u JOIN rendez_vous r ON u.userID = r.IDPatient  ";
						$stmt = $bdd->prepare($query);
						$stmt->execute();
						$RdvCount = $stmt->fetchColumn();

						// Query to fetch the number of available psychiatrists
						$query = "SELECT COUNT(*) FROM compte_utilisateurs  ";
						$stmt = $bdd->prepare($query);
						$stmt->execute();
						$Rdv = $stmt->fetchColumn();

						// Calculate the progress percentage
						if ($Rdv != 0) {
							$progressPercentage = ($RdvCount / $Rdv) * 100;
						} else {
							$progressPercentage = 0;
						}	
												?>
						<div class="col-xl-4 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon text-danger border-danger">
											<i class="fe fe-money"></i>
										</span>
										<div class="dash-count">
											<h3><?php echo $RdvCount ?></h3>
										</div>
									</div>
									<div class="dash-widget-info">

										<h6 class="text-muted">Rendez-Vous</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $progressPercentage ?>%;" aria-valuenow="<?php echo $progressPercentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-6 d-flex">

							<!-- Recent Orders -->
							<div class="card card-table flex-fill">
								<div class="card-header">
									<h4 class="card-title">Listes Docteurs</h4>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<?php
										if (isset($_POST['recherche']) && !empty(trim($_POST['recherche']))) {
											$recherche = htmlspecialchars($_POST['recherche']);
											$keywords = preg_split('/\s+/', $recherche);
											$searchQuery = 'SELECT * FROM compte_utilisateurs u, psychiatres p WHERE u.userID = p.PsyID AND p.Confirmer = 1';
											$searchParams = array();
											foreach ($keywords as $key => $value) {
												$searchQuery .= ' AND (u.Nom LIKE ? OR u.Prenom LIKE ? OR p.Specialite LIKE ?)';
												$searchParams[] = '%' . $value . '%';
												$searchParams[] = '%' . $value . '%';
												$searchParams[] = '%' . $value . '%';
											}
											$searchQuery .= ' ORDER BY userID desc';
											$recupPsy = $bdd->prepare($searchQuery);
											$recupPsy->execute($searchParams);
										} else {
											$recupPsy = $bdd->prepare('SELECT * FROM compte_utilisateurs u, psychiatres p WHERE u.userID = p.PsyID AND p.Confirmer = 1 ORDER BY userID desc');
											$recupPsy->execute();
										}

										?>
										<table class="table table-hover table-center mb-0">
											<thead>
												<tr>
													<th>Nom Docteur</th>
													<th>Spécialité</th>
												</tr>
											</thead>
											<tbody>
												<?php if ($recupPsy->rowCount() > 0) {
													while ($infoPsy = $recupPsy->fetch()) {
												?>
														<tr>
															<td>
																<h2 class="table-avatar">
																	<a href="#" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="../Espace Psychiatre/images/profileImage/<?php echo $infoPsy['PhotoProfile'] ?>" alt="User Image"></a>
																	<a href="#">Dr. <?php echo $infoPsy['Prenom'] . ' ', '' . $infoPsy['Nom'] ?></a>
																</h2>
															</td>
															<td><?php echo $infoPsy['Specialite'] ?></td>
														</tr>
												<?php }
												} ?>

											</tbody>
										</table>
									</div>
								</div>
							</div>
							<!-- /Recent Orders -->

						</div>
						<div class="col-md-6 d-flex">

							<!-- Feed Activity -->
							<div class="card  card-table flex-fill">
								<div class="card-header">
									<h4 class="card-title">Listes Patients</h4>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<!-- /Page Header -->
										<?php
										if (isset($_POST['recherche']) && !empty(trim($_POST['recherche']))) {
											$recherche = htmlspecialchars($_POST['recherche']);
											$keywords = preg_split('/\s+/', $recherche);
											$searchQuery = 'SELECT * FROM compte_utilisateurs WHERE Role = 0';
											$searchParams = array();
											foreach ($keywords as $key => $value) {
												$searchQuery .= ' AND (Nom LIKE ? OR Prenom LIKE ? )';
												$searchParams[] = '%' . $value . '%';
												$searchParams[] = '%' . $value . '%';
											}
											$searchQuery .= ' ORDER BY userID desc';
											$recupPatient = $bdd->prepare($searchQuery);
											$recupPatient->execute($searchParams);
										} else {
											$recupPatient = $bdd->prepare('SELECT * FROM compte_utilisateurs  WHERE Role = 0 ORDER BY userID desc');
											$recupPatient->execute();
										}
										?>
										<table class="table table-hover table-center mb-0">
											<thead>
												<tr>
													<th>Nom Patient</th>
													<th>Numéro de Téléphone</th>
													<th>Email</th>
												</tr>
											</thead>
											<tbody>
												<?php if ($recupPatient->rowCount() > 0) {
													while ($infoPatient = $recupPatient->fetch()) {
												?>
														<tr>
															<td>
																<h2 class="table-avatar">
																	<a href="profile.html" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="../Espace Patients/img/<?php echo $infoPatient['PhotoProfile'] ?>" alt="User Image"></a>
																	<a href="profile.html"><?php echo $infoPatient['Prenom'] . ' ', '' . $infoPatient['Nom'] ?> </a>
																</h2>
															</td>
															<td><?php echo $infoPatient['NumeroT'] ?></td>
															<td><?php echo $infoPatient['Email'] ?></td>
														</tr>
												<?php }
												} ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<!-- /Feed Activity -->

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

		<script src="assets/plugins/raphael/raphael.min.js"></script>
		<script src="assets/plugins/morris/morris.min.js"></script>
		<script src="assets/js/chart.morris.js"></script>

		<!-- Custom JS -->
		<script src="assets/js/script.js"></script>

	</body>

	<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:34 GMT -->

	</html>
<?php } ?>