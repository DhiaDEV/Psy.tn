<?php
//Connexion a la Base De Données
$bdd = new PDO('mysql:host=localhost; dbname=psy.tn;', 'root', '');

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Psy.tn: Articles</title>
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
    </head>

    <body>
        <!-- Démarrage du Spinner -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner"></div>
        </div>
        <!-- Démarrage du Spinner -->

        


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
                        <a href="index.php" class="nav-item nav-link active">Accueil</a>
                        <a href="about.php" class="nav-item nav-link">Qui sommes nous? </a>
                        <a href="Articles.php" class="nav-item nav-link">Articles</a>
                        <a href="contact.php" class="nav-item nav-link">Contact</a>
                        <a href="connexion.php" class="nav-item nav-link">Se Connecter </a>
                    </div>
                </div>
            </nav>

            <div class="container-fluid py-5 bgArticle" style="margin-bottom: 90px;">
                <div class="row py-5">
                    <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                        <h1 class="display-4 text-white animated zoomIn">Articles</h1>
                        <a href="index.php" class="h5 text-white">Accueil</a>
                        <i class="far fa-circle text-white px-2"></i>
                        <a href="Articles.php" class="h5 text-white">Articles</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Démarrage de la Navbar -->

        <!-- Articles -->
        <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-12">
                        <div class="row g-5">
                            <?php
                            // limite de page et numéro de page actuel
                            $page_limit = 10;
                            $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

                            // calculer le décalage pour la requête de la base de données
                            $offset = ($current_page - 1) * $page_limit;

                            // récupérer le nombre total d'articles depuis la base de données
                            $total_articles = $bdd->query('SELECT COUNT(*) FROM articles')->fetchColumn();

                            // calculer le nombre total de pages
                            $total_pages = ceil($total_articles / $page_limit);

                            // récupérer les articles pour la page actuelle
                            $recupArticles = $bdd->prepare('SELECT * FROM articles a JOIN compte_utilisateurs u ON a.idPSY = u.userID ORDER BY a.articleID DESC LIMIT :limit OFFSET :offset');
                            $recupArticles->bindParam(':limit', $page_limit, PDO::PARAM_INT);
                            $recupArticles->bindParam(':offset', $offset, PDO::PARAM_INT);
                            $recupArticles->execute();
                            // Si aucunne article publié
                            if ($total_articles == 0) {
                                echo "<div class='alert alert-danger coloor' role='alert' style='font-weight: 700;'>
                                Aucun article n'a été publié pour le moment.                            
                              </div>";
                            }

                            // afficher les articles pour la page actuelle
                            while ($article = $recupArticles->fetch()) { ?>
                                <div class="col-md-6 wow slideInUp" data-wow-delay="0.1s">
                                    <div class="blog-item bg-light rounded overflow-hidden">
                                        <div class="blog-img position-relative overflow-hidden">
                                            <img class="img-fluid" style="height: 400px; width:100%;" src="./Espace Psychiatre/images/Articles/<?php echo $article["PhotoA"]; ?>" alt="">
                                        </div>
                                        <div class="p-4">
                                            <div class="d-flex mb-3">
                                                <small class="me-3"><i class="far fa-user me-2" style="color: #66CDAA;"></i><?php echo $article["Prenom"] . ' ' . $article["Nom"]; ?></small>
                                                <small><i class="far fa-calendar-alt me-2" style="color: #66CDAA;"></i><?php echo $article["Time"]; ?></small>
                                            </div>
                                            <h4 class="mb-3"><?php echo $article["titre"]; ?></h4>
                                            <p><?php echo $article["DescriptionA"]; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <?php
                            // afficher les liens de pagination
                            echo '<nav aria-label="Page navigation">';
                            echo '<ul class="pagination pagination-lg m-0">';

                            // afficher le lien de page précédente
                            if ($current_page > 1) {
                                echo '<li class="page-item">';
                                echo '<a class="page-link rounded-0" style="color: #66CDAA;" href="Articles.php?page=' . ($current_page - 1) . '" aria-label="Previous">';
                                echo '<span aria-hidden="true"><i class="bi bi-arrow-left"></i></span>';
                                echo '</a>';
                                echo '</li>';
                            } else {
                                echo '<li class="page-item disabled">';
                                echo '<a class="page-link rounded-0" style="color: #66CDAA;" href="#" aria-label="Previous">';
                                echo '<span aria-hidden="true"><i class="bi bi-arrow-left"></i></span>';
                                echo '</a>';
                                echo '</li>';
                            }


                            // afficher les liens de page
                            for ($i = 1; $i <= $total_pages; $i++) {
                                if ($i == $current_page) {
                                    echo '<li class="page-item active"><a class="page-link" style="color: black; background-color: #66CDAA; border:#66CDAA;" href="Articles.php?page=' . $i . '">' . $i . '</a></li>';
                                } else {
                                    echo '<li class="page-item"><a class="page-link" style="color: black;" href="Articles.php?page=' . $i . '">' . $i . '</a></li>';
                                }
                            }

                            // afficher le lien de page suivante
                            if ($current_page < $total_pages) {
                                echo '<li class="page-item">';
                                echo '<a class="page-link rounded-0" style="color: #66CDAA;" href="Articles.php?page=' . ($current_page + 1) . '" aria-label="Next">';
                                echo '<span aria-hidden="true"><i class="bi bi-arrow-right"></i></span>';
                                echo '</a>';
                                echo '</li>';
                            } else {
                                echo '<li class="page-item disabled">';
                                echo '<a class="page-link rounded-0" style="color: #66CDAA;" href="#" aria-label="Next">';
                                echo '<span aria-hidden="true"><i class="bi bi-arrow-right"></i></span>';
                                echo '</a>';
                                echo '</li>';
                            }

                            echo '</ul>';
                            echo '</nav>';

                            ?>
                        </div>
                    </div>
                </div>
                <!-- Articles -->
            </div>
        </div>

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-light mt-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="row gx-5">
                    <div class="col-lg-4 col-md-6 footer-about">
                        <div class="d-flex flex-column align-items-center justify-content-center text-center h-100  p-4" style="background-color: #66CDAA;">
                            <a href="index.php" class="navbar-brand">
                                <a href="index.php" class="navbar-brand p-0">
                                    <!-- <h1 style="padding-left: 40px; padding-top: 10px;">Psy.tn</h1> -->
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
