<?php 

require_once 'mail.php' ;

if(isset($_POST['envoyer'])){
    if(!empty($_POST['nom'])AND !empty($_POST['email'])AND !empty($_POST['sujet'])AND !empty($_POST['message'])){
        $nom=htmlspecialchars($_POST['nom']);
        $email=htmlspecialchars($_POST['email']);
        $sujet=htmlspecialchars($_POST['sujet']);
        $message= nl2br(htmlspecialchars($_POST['message']));
        
        $mail->addAddress('email');
        $mail->Subject= $sujet;
        $mail->Body ='<b>Nom:</b> '. $nom. '<br><b>Email:</b> ' . $email.'<br><b>Message:</b> '. $message ;   
        $mail->send();
        echo("<script> alert('Mail bien envoyer') ;</script>");
      }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Psy.tn: Contact</title>
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
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="index.php" class="navbar-brand p-0">
                <img src="img/logoF.png" width="150px" height="auto" style="padding-top: 15px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.php" class="nav-item nav-link active">Accueil</a>
                    <a href="about.php" class="nav-item nav-link">Qui sommes nous? </a>
                    <a href="articles.php" class="nav-item nav-link">Articles</a>
                    <a href="contact.php" class="nav-item nav-link">Contact</a>
                    <a href="connexion.php" class="nav-item nav-link">Se Connecter </a>
                </div>
            </div>
    </div>
    </nav>

    <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
        <div class="row py-5">
            <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                <h1 class="display-4 text-white animated zoomIn">Contact</h1>
                <a href="index.php" class="h5 text-white">Home</a>
                <i class="far fa-circle text-white px-2"></i>
                <a href="contact.php" class="h5 text-white">Contact</a>
            </div>
        </div>
    </div>
    </div>
    <!-- Navbar End -->

    <!-- Contact Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
                <h5 class="fw-bold contact text-uppercase">Contactez-nous</h5>
                <h1 class="mb-0">Si vous avez des questions, n'hésitez pas à nous contacter</h1>
            </div>
            <div class="row g-5">
                <div class="col-lg-6 wow slideInUp" data-wow-delay="0.3s">
                    <form method="POST" >
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="nom" class="form-control border-0  px-4 bgform" placeholder="Votre Nom" style="height: 55px;">
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" class="form-control border-0  px-4 bgform" placeholder="Votre Email" style="height: 55px;">
                            </div>
                            <div class="col-12">
                                <input type="text" name="sujet" class="form-control border-0  px-4 bgform" placeholder="Sujet" style="height: 55px;">
                            </div>
                            <div class="col-12">
                                <textarea name="message" class="form-control border-0  px-4 bgform py-3" rows="4" placeholder="Message"></textarea>
                            </div>
                            <div class="col-12">
                                <button name="envoyer" class="btn btnCmntr w-100 py-3" type="submit">Envoyer Message</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 wow slideInUp" data-wow-delay="0.6s">
                    <iframe class="position-relative rounded w-100 h-100" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d102239.55615109218!2d10.1432776!3d36.794882949999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12fd337f5e7ef543%3A0xd671924e714a0275!2sTunis!5e0!3m2!1sfr!2stn!4v1677324785076!5m2!1sfr!2stn" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" frameborder="0" style="min-height: 350px; border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->

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
                                <a class="text-light mb-2" href="index.php"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Accueil</a>
                                <a class="text-light mb-2" href="about.php"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Qui somme nous?</a>
                                <a class="text-light mb-2" href="contact.php"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Contact</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Liens populaires</h3>
                            </div>
                            <div class="link-animated d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="index.php"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Accueil</a>
                                <a class="text-light mb-2" href="connexion.php"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Trouver un psychiatre</a>
                                <a class="text-light mb-2" href="connexion.php"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Poser Une Question</a>
                                <a class="text-light mb-2" href="connexion.php"><i class="bi bi-arrow-right  me-2" style="color: #66CDAA;"></i>Prendre Rendez-Vous</a>
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


    <!-- Back to Top -->
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