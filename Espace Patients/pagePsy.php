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
    //Récupérer les données de Patient par l id qui entrer
    $recupPatient = $bdd->prepare('SELECT * FROM compte_utilisateurs WHERE userID= ?');
    $recupPatient->execute(array($getid));
    $patientInfo = $recupPatient->fetch();

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Psy.tn</title>
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
        <link rel="stylesheet" href="css/pagePsy.css">
        <link rel="stylesheet" href="css/trouverPsy.css">
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


        <!-- Démarrage de la Navbar -->
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
                    <h1 class="display-4 text-white animated zoomIn">Trouver un psychologue</h1>
                    <a href="profile.php?userID=<?= $patientInfo['userID'] ?>" class="h5 text-white">Home</a>
                    <i class="far fa-circle text-white px-2"></i>
                    <a href="psychologue.php?userID=<?= $patientInfo['userID'] ?>" class="h5 text-white">Trouver un psychologue</a>
                </div>
            </div>
        </div>
        </div>
        <!-- Démarrage de la Navbar -->

        <!-- Les information Globale de Psychiatres -->
        <?php
        if (isset($_GET['psyID'])) {
            $getidP = intval($_GET['psyID']);

            $recupPsy = $bdd->prepare('SELECT * FROM compte_utilisateurs u JOIN psychiatres p ON u.userID = p.PsyID WHERE p.Confirmer = 1 AND userID= ? ');
            $recupPsy->execute(array($getidP));
            $PsyInfo = $recupPsy->fetch();
        ?>
            <section style="width:100%;background-color: #1e2327;">
                <div class="d-flex align-items-center border-bottom pt-5 pb-4 px-5">
                    <img class="img-fluid rounded" src="../Espace Psychiatre/images/profileImage/<?php echo $PsyInfo['PhotoProfile'] ?>" style="width: 60px; height: 60px;">
                    <div class="ps-4">
                        <h4 class=" mb-1" style="color: #66CDAA;"><?php echo $PsyInfo['Prenom'], ' ' . '' . $PsyInfo['Nom'] ?><i class="bi bi-check-circle-fill" style="margin-left: 10px;"></i></h4>
                        <small class="text-uppercase"><?php echo $PsyInfo['Specialite'] ?></small>  
                        <br>
                        <div class="loca"><i class="bi bi-geo-alt-fill"></i><a class="locaLien" href=""><?php echo $PsyInfo['Gouvernorat'], '-' . '' . $PsyInfo['Lieu'] ?></a></div>
                    </div>
                    <div class="ms-auto rendez" style>
                        <a href="prendreRDV.php?userID=<?php echo $patientInfo['userID']; ?>&PsyID=<?php echo $PsyInfo['userID'] ?>" class="btn prendre btn-block"><i class="bi bi-calendar2-check" style="padding-right: 6px;"></i>Prendre Rendez-Vous</a>

                    </div>
                </div>
            </section>
            <!-- Les information Globale de Psychiatres -->

            <!-- Les information de contact de Psychiatres -->
            <section class="info">
                <div>
                    <h1>Informations de contact</h1>
                </div>

                <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalToggleLabel">Afficher le Numéro</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <section style="width:100%;background-color: #1e2327;">
                                    <div class="d-flex align-items-center border-bottom pt-5 pb-4 px-5">
                                        <img class="img-fluid rounded" src="../Espace Psychiatre/images/profileImage/<?php echo $PsyInfo['PhotoProfile'] ?>" style="width: 60px; height: 60px;">
                                        <div class="ps-4">
                                            <h4 class=" mb-1" style="color: #66CDAA;"><?php echo $PsyInfo['Prenom'], ' ' . '' . $PsyInfo['Nom'] ?><i class="bi bi-check-circle-fill" style="margin-left: 10px;"></i></h4>
                                            <small class="text-uppercase"><?php echo $PsyInfo['Specialite'] ?></small>
                                            <br>
                                            <div class="loca"><i class="bi bi-geo-alt-fill"></i><a class="locaLien" href=""><?php echo $PsyInfo['Gouvernorat'], '-' . '' . $PsyInfo['Lieu'] ?></a></div>
                                        </div>
                                    </div>
                                </section>
                                <h1 style="font-size: 15px; text-align:center;">Veuillez trouver ci dessous le numéro que vous cherchez</h1>
                                <br>
                                <div class="numbtnn">
                                    <i class="bi bi-telephone-fill"></i>
                                    <button class="numbtn" style="background-color:#66CDAA;"><?php echo $PsyInfo['NumeroT'] ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="contact-info">
                    <i class="bi bi-telephone-fill"></i>
                    <a class="btn  afficherNum" data-bs-toggle="modal" href="#exampleModalToggle" role="button">Afficher le Numéro</a>
                </div>
                <?php

                // Définir l'adresse à afficher sur la carte
                $address = $PsyInfo['Adresse'];

                // Encoder l'adresse à utiliser dans l'URL
                $encoded_address = urlencode($address);

                // la clé API pour Google Maps
                $api_key = "Api Google Maps ";

                // Construire l'URL de la page Google Maps
                $maps_url = "https://www.google.com/maps/search/?api=1&query={$encoded_address}&key={$api_key}";

                // Afficher le lien vers la page Google Maps
                echo "<div class='localisation'>";
                echo "<i class='bi bi-geo-alt-fill'></i>";
                echo "<p style='padding-top: 10px;'><a href='{$maps_url}' target='_blank'>{$address}</a></p>";
                echo "</div>";

                ?>
            </section>
            <!-- Les information de contact de Psychiatres -->

            <!-- Les information de Psychiatres -->
            <div class="info presentation">
                <h1>Présentation</h1>
                <h5>Spécialités</h5>
                <button class="btn btn-outline-dark"><?php echo $PsyInfo['Specialite'] ?></button>
                <h5>Description</h5>
                <p class="description"><?php echo $PsyInfo['Description'] ?></p>

            </div>
            <!-- Les information de Psychiatres -->

            <br>
            <!-- Afficher Localisation -->
            <h3 style="text-align:center;">Notre Localisation</h3>
            <div style=" display: flex; justify-content: center;">
                <iframe src="https://www.google.com/maps/embed/v1/view?key=AIzaSyA6Y2BX-uF7ttWvfucY47nRsIUuz_LpJJE&center=<?php echo $PsyInfo['latitude'] ?>,<?php echo $PsyInfo['longitude'] ?>&zoom=15" width="1000" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
            <!-- Afficher Localisation -->

        <?php } ?>


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