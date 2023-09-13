<?php
session_start();
//Connexion a la Base De Données
$bdd = new PDO('mysql:host=localhost; dbname=psy.tn;', 'root', '');

//Sécurité... 
if (!$_SESSION['auth']) {
    header('Location: index.php');
}
if (isset($_GET['userID']) and $_GET['userID'] > 0) {
    $getid = intval($_GET['userID']); //intval pour sécuriser l'id 
    //Récupérer les données de Patient par l'id qui entrer
    $recupPatient = $bdd->prepare('SELECT * FROM compte_utilisateurs WHERE userID= ?');
    $recupPatient->execute(array($getid));
    $patientInfo = $recupPatient->fetch();

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Psy.tn : Trouver Psychiatres</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="Free HTML Templates" name="keywords">
        <meta content="Free HTML Templates" name="description">

        <!-- Favicon -->
        <link href="img/logoF.png" rel="icon">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
        <link href="lib/animate/animate.min.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
        <link rel="stylesheet" href="css/nv.css">
        <link rel="stylesheet" href="css/trouverPsy.css">
        
        <!-- CSS pour Afficher les Psychiatres -->
        <style>
            .center {
                margin: auto;
                width: 60%;
                padding: 10px;
            }

            .loca {
                margin-top: 7px;
                font-size: 15px;
                color: #66CDAA;
            }

            .loca .locaLien {
                color: #0a5fdf;
                padding-left: 4px;
            }

            .prendre {
                background-color: #66CDAA;
                color: black;
            }

            .prendre:hover {
                background-color: black;
                color: white;
            }
        </style>
    </head>

    <body>
        <!-- Démarrage du Spinner -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner"></div>
        </div>
        <!-- Démarrage du Spinner -->

        <!-- Démarrage de la Topbar -->
        <div class="container-fluid bgC px-5 d-none d-lg-block" style="background-color: #919292;">
            <div class="row gx-0">
                <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <a href="profile.php?userID=<?php echo $patientInfo['userID'] ?>"><img class="imageProfile" width="4%" style="border-radius: 10px;" src="img/<?php echo $patientInfo['PhotoProfile']; ?>"></a>

                    <div class="d-inline-flex align-items-center" style="height: 45px;">
                        <small class="me-3 text-light"></i> <?php echo $patientInfo['Prenom'], ' ' . '' . $patientInfo['Nom'] ?></small>
                    </div>
                </div>
                <div class="col-lg-4 text-center text-lg-end">
                    <div class="dropdown" style="width: 100%; margin-top:5px; ">
                        <button class="btn btn-secondary dropdown-toggle" style="background-color:#66CDAA;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Mon compte
                        </button>
                        <div class="dropdown-menu" style="width: 100%;" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="profile.php?userID=<?= $patientInfo['userID'] ?>">Profil</a>
                            <a class="dropdown-item" href="MesRDV.php?userID=<?= $patientInfo['userID'] ?>">Mes Rendez-vous</a>
                            <a href="questions.php?userID=<?= $patientInfo['userID'] ?>" class="dropdown-item">Poser Une Question</a>
                            <a href="MesQuestions.php?userID=<?= $patientInfo['userID'] ?>" class="dropdown-item"> Mes Question</a>
                            <a class="dropdown-item" href="modifierProfile.php?userID=<?= $patientInfo['userID'] ?>">Paramètres</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="deconnexion.php">Déconnexion</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Démarrage de la Topbar -->


        <!-- Démarrage de la Navbar  -->
        <div class="container-fluid position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
                <a href="profile.php?userID=<?= $patientInfo['userID'] ?>" class="navbar-brand p-0">
                    <img src="img/logoF.png" width="150px" height="auto" style="padding-top: 15px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="profile.php?userID=<?= $patientInfo['userID'] ?>" class="nav-item nav-link active">Accueil</a>
                        <a href="psychologue.php?userID=<?= $patientInfo['userID'] ?>" class="nav-item nav-link">Trouver un psychiatre</a>
                        <a href="Qui_sommes_nous.php?userID=<?= $patientInfo['userID'] ?>" class="nav-item nav-link">Qui sommes nous? </a>
                        <a href="Articles.php?userID=<?= $patientInfo['userID'] ?>" class="nav-item nav-link">Articles</a>
                        <a href="contact.php?userID=<?= $patientInfo['userID'] ?>" class="nav-item nav-link">Contact</a>
                    </div>
                </div>
        </div>
        </nav>

        <div class="container-fluid py-5 bgTrouver" style="margin-bottom: 90px;">
            <div class="row py-5">
                <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                    <h1 class="display-4 text-white animated zoomIn">Trouver un psychiatre et prendre rendez-vous</h1>
                    <a href="profile.php?userID=<?= $patientInfo['userID'] ?>" class="h5 text-white">Home</a>
                    <i class="far fa-circle text-white px-2"></i>
                    <a href="psychologue.php?userID=<?= $patientInfo['userID'] ?>" class="h5 text-white">Trouver un psychiatre</a>
                </div>
            </div>
        </div>
        </div>
        <!-- Démarrage de la Navbar  -->

        <!-- Trouver un psychiatres par filtration -->
        <div class="col-lg-12">
            <form id="search-form" method="POST" role="search" action="#">
                <div class="row">
                    <div class="col-lg-3 align-self-center">
                        <fieldset>
                            <select name="gouvernorat" class="form-select" aria-label="Area" id="">
                                <option selected>Gouvernorat</option>
                                <option value="tunis">Tunis</option>
                                <option value="ariana">Ariana</option>
                                <option value="ben arous">Ben Arous</option>
                                <option value="beja">Béja</option>
                                <option value="bizerte">Bizerte</option>
                                <option value="gabes">Gabès</option>
                                <option value="gafsa">Gafsa</option>
                                <option value="jendouba">Jendouba</option>
                                <option value="kairouan">Kairouan</option>
                                <option value="kasserine">Kasserine</option>
                                <option value="kebili">Kébili</option>
                                <option value="kef">Le Kef </option>
                                <option value="mahdia">Mahdia </option>
                                <option value="manouba">La Manouba </option>
                                <option value="mednine">Médenine</option>
                                <option value="monastir">Monastir</option>
                                <option value="nabeul">Nabeul</option>
                                <option value="sfax">Sfax</option>
                                <option value="sidi bouzid">Sidi Bouzid</option>
                                <option value="siliana">Siliana</option>
                                <option value="sousse">Sousse</option>
                                <option value="tataouine">Tataouine</option>
                                <option value="tozeur">Tozeur</option>
                                <option value="zaghouan">Zaghouan</option>
                            </select>
                        </fieldset>
                    </div>

                    <div class="col-lg-3 align-self-center">
                        <fieldset>
                            <input type="text" name="lieu" class="searchText" placeholder="ville" autocomplete="on">
                        </fieldset>
                    </div>
                    <div class="col-lg-3 align-self-center">
                        <fieldset>
                            <select name="specialite" class="form-select" aria-label="Default select example" id="">
                                <option selected>Spécialité</option>
                                <option value="psychiatre">Psychiatre</option>
                                <option value="pédopsychiatre">Pédopsychiatre</option>
                                <option value="sexologues">Sexologues</option>
                            </select>
                        </fieldset>
                    </div>

                    <div class="col-lg-3">
                        <button type="submit" name="rech" class="main-button"><i class="fa fa-search"></i> Recherche</button>
                    </div>
                </div>
            </form>
        </div>
        <br><br>
        <!--Trouver un psychiatres par filtration-->

        <?php
        if (isset($_POST['gouvernorat']) || isset($_POST['specialite']) || isset($_POST['lieu'])) {

            $gouvernorat = $_POST['gouvernorat'];
            $specialite = $_POST['specialite'];
            $lieu = $_POST['lieu'];

            // Vérifier si la combinaison de gouvernorat et de spécialité et lieu existe dans la base de données
            $stmt = $bdd->prepare("SELECT * FROM compte_utilisateurs u JOIN psychiatres p ON u.userID = p.PsyID WHERE u.Role= 1 AND p.Confirmer= 1 AND p.Gouvernorat=:Gouvernorat AND p.Specialite=:Specialite AND p.Lieu =:Lieu ");
            $stmt->bindParam(':Gouvernorat', $gouvernorat);
            $stmt->bindParam(':Specialite', $specialite);
            $stmt->bindParam(':Lieu', $lieu);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                // La combinaison existe, exécuter la requête SQL
                $sql = "SELECT * FROM compte_utilisateurs u JOIN psychiatres p ON u.userID = p.PsyID WHERE u.Role= 1 AND p.Confirmer= 1 AND p.Gouvernorat LIKE :Gouvernorat AND p.Specialite LIKE :Specialite AND p.Lieu LIKE :Lieu GROUP BY p.Gouvernorat, p.Specialite, p.Lieu ORDER BY p.Gouvernorat, p.Specialite, p.Lieu";
                $stmt = $bdd->prepare($sql);
                $stmt->bindValue(':Gouvernorat',  '%' . $gouvernorat . '%', PDO::PARAM_STR);
                $stmt->bindValue(':Specialite',  '%' . $specialite . '%', PDO::PARAM_STR);
                $stmt->bindValue(':Lieu', '%' . $lieu . '%', PDO::PARAM_STR);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($results as $row) {
        ?>

                    <section class="center" style="width:70%; ">
                        <div class="d-flex align-items-center border-bottom pt-5 pb-4 px-5">
                            <a href="pagePsy.php?userID=<?php echo $patientInfo['userID'];?>&psyID=<?php echo $row['userID'] ?>"><img class="img-fluid rounded" src="../Espace Psychiatre/images/profileImage/<?php echo $row['PhotoProfile'] ?>" style="width: 60px; height: 60px;"></a>
                            <div class="ps-4">
                                <h4 class=" mb-1" style="color: #66CDAA;"><?php echo $row['Prenom'], ' ' . '' . $row['Nom'] ?></h4>
                                <small class="text-uppercase"><?php echo $row['Specialite'] ?></small>
                                <br>
                                <div class="loca"><i class="bi bi-geo-alt-fill"></i><a class="locaLien" href=""><?php echo $row['Gouvernorat'], '-' . '' . $row['Lieu'] ?></a></div>
                            </div>
                            <div class="ms-auto rendez" style>
                                <a href="prendreRDV.php?userID=<?php echo $patientInfo['userID']; ?>&PsyID=<?php echo $row['userID'] ?>" class="btn prendre btn-block"><i class="bi bi-calendar2-check" style="padding-right: 6px;"></i>Prendre Rendez-Vous</a>
                            </div>
                        </div>

                        <div class="pt-4 pb-5 px-5">
                            <?php echo $row['Description'] ?>
                        </div>
                    </section>
                    <hr>

        <?php
                }
            } else {
                // La combinaison n'existe pas, afficher un message d'erreur
                echo '<div class="alert alert-danger" role="alert" style=" margin: auto; margin-top:15px; width:29%;">
            Aucun praticien trouvé avec les critères sélectionnés.  
        </div>';
            }
        }

        ?>

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-light mt-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="row gx-5">
                    <div class="col-lg-4 col-md-6 footer-about">
                        <div class="d-flex flex-column align-items-center justify-content-center text-center h-100  p-4" style="background-color: #66CDAA;">
                            <a href="index.php" class="navbar-brand">
                                <a href="index.php" class="navbar-brand p-0">
                                    <img src="img/logoF.png" width="200px" height="110px" style="padding-top: 15px;">
                                </a>
                            </a>
                            <p class="mt-3 mb-4">La santé mentale est une partie importante de notre bien-être global. Il est important de prendre soin de notre santé mentale tout comme nous prenons soin de notre santé physique.</p>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-6">
                        <div class="row gx-5">
                            <div class="col-lg-4 col-md-12 pt-5 mb-5">
                                <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                    <h3 class="text-light mb-0">Contactez-nous</h3>
                                </div>
                                <div class="d-flex mb-2">
                                    <i class="bi bi-geo-alt  me-2" style="color: #66CDAA;"></i>
                                    <p class="mb-0">adress</p>
                                </div>
                                <div class="d-flex mb-2">
                                    <i class="bi bi-envelope-open  me-2" style="color: #66CDAA;"></i>
                                    <p class="mb-0">email</p>
                                </div>
                                <div class="d-flex mb-2">
                                    <i class="bi bi-telephone  me-2" style="color: #66CDAA;"></i>
                                    <p class="mb-0">numero</p>
                                </div>
                                <div class="d-flex mt-4">
                                    <a class="btn sociaux btn-square me-2" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                                    <a class="btn sociaux btn-square me-2" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                                    <a class="btn sociaux btn-square me-2" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                                    <a class="btn sociaux btn-square" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                                <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                    <h3 class="text-light mb-0">Liens rapides</h3>
                                </div>
                                <div class="link-animated d-flex flex-column justify-content-start">
                                    <a class="text-light mb-2" href="profile.php?userID=<?= $patientInfo['userID'] ?>"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Accueil</a>
                                    <a class="text-light mb-2" href="psychologue.php?userID=<?= $patientInfo['userID'] ?>"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Trouver un psychiatre</a>
                                    <a class="text-light mb-2" href="Qui_sommes_nous.php?userID=<?= $patientInfo['userID'] ?>"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Qui somme nous?</a>
                                    <a class="text-light mb-2" href="Articles.php?userID=<?= $patientInfo['userID'] ?>"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Articles</a>
                                    <a class="text-light mb-2" href="contact.php?userID=<?= $patientInfo['userID'] ?>"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Contact</a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                                <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                    <h3 class="text-light mb-0">Liens populaires</h3>
                                </div>
                                <div class="link-animated d-flex flex-column justify-content-start">
                                    <a class="text-light mb-2" href="profile.php?userID=<?= $patientInfo['userID'] ?>"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Accueil</a>
                                    <a class="text-light mb-2" href="MesRDV.php?userID=<?= $patientInfo['userID'] ?>"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Mes Rendez-Vous</a>
                                    <a class="text-light mb-2" href="questions.php?userID=<?= $patientInfo['userID'] ?>"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Poser Une Question</a>
                                    <a class="text-light mb-2" href="MesQuestions.php?userID=<?= $patientInfo['userID'] ?>"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Mes Questions</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid text-white" style="background: #061429;">
            <div class="container text-center">
                <div class="row justify-content-end">
                    <div class="col-lg-8 col-md-6">
                        <div class="d-flex align-items-center justify-content-center" style="height: 75px;">
                            <p class="mb-0">&copy; <a class="text-white border-bottom" href="#">Psy.tn</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Retour en haut -->
        <a href="#" class="btn btn-lg btn-lg-square rounded back-to-top bibiBg"><i class="bi bi-arrow-up bibi"></i></a>


        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="lib/wow/wow.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/counterup/counterup.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>
    </body>

    </html>
<?php } ?>