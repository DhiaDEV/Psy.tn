<?php
session_start();
//Connexion a la Base De Données
$bdd = new PDO('mysql:host=localhost; dbname=psy.tn;', 'root', '');


//validation de formulaire
if (isset($_POST['connecter'])) {
  //vérifier si le Psy a completer  tous les champs 
  if (!empty($_POST['email']) and !empty($_POST['MDP'])) {
    //Les données de Psy
    $PsyEmail = htmlspecialchars($_POST['email']);
    $PsyMDP = htmlspecialchars($_POST['MDP']);
    //verifier si le Psy existe 
    $PsyExiste = $bdd->prepare('SELECT * FROM  psychiatres p , compte_utilisateurs u WHERE p.PsyID = u.userID   AND Email= ? AND Role= 1');
    $PsyExiste->execute(array($PsyEmail));

    if ($PsyExiste->rowCount() > 0) {
      //Récuperer les données de Psy
      $PsyInfos = $PsyExiste->fetch();
      //Vérifier si le mot de passe est correcte
      if (password_verify($PsyMDP, $PsyInfos['MotDePasse'])) {

        // Authentifier Psy sur le site et récupérer ses données dans des variables globales sessions
        $_SESSION['auth'] = true;
        $_SESSION['userID'] = $PsyInfos['userID'];
        $_SESSION['nom'] = $PsyInfos['Nom'];
        $_SESSION['prenom'] = $PsyInfos['Prenom'];
        $_SESSION['email'] = $PsyInfos['Email'];
        //Rediriger l'utilisateur sur la page d'accueil
        if ($PsyInfos['Confirmer'] == 1) {
          header("Location:doctor-dashboard.php?userID=" . $_SESSION['userID']);
        }
      } else {
        $errorMsg = "<div class='alert alert-danger coloor' role='alert' style=' font-weight: 700;'>
            Le mot de passe que vous avez saisi est incorrect.
            </div> ";
      }
    } else {
      $errorMsg = "<div class='alert alert-danger coloor' role='alert' style=' font-weight: 700;'>
          Aucun utilisateur n'a été trouvé avec cette adresse email.
          </div> ";
    }
  } else {
    $errorMsg = "<div class='alert alert-danger coloor' role='alert' style=' font-weight: 700;'>
      Veuillez complèter tous les champs
      </div> ";
  }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/connexionPsy.css">
  <link href="assets/img/logoF.png" rel="icon">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
  <!-- MDB -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css" rel="stylesheet" />
  <title>Connexion</title>
  <style>
    .logo_login {
      background: url('images/layout_img/login_image.jpg');
      padding: 50px 0;
      height: 100%;
      background-position: center center;
      position: relative;
    }

    .logo_login::after {
      content: "";
      display: block;
      width: 100%;
      height: 100%;
      position: absolute;
      background: rgba(21, 40, 60, .8);
      top: 0px;
      left: 0;
    }

    .logo_login div {
      position: relative;
      z-index: 1;
    }

    .center {
      margin: auto;
      width: 50%;
      padding: 10px;
      padding-top: 100px;
    }
  </style>
</head>

<body>
  <form action="" method="POST">

    <section class="h-100 bg-dark">
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col">
            <div class="card card-registration my-4">
              <div class="row g-0">
                <div class="col-xl-6 d-none d-xl-block">
                  <div class="logo_login">
                    <div class="center">
                      <img width="300" src="images/logo/logoF.png" alt="#" />
                    </div>
                  </div>
                </div>

                <div class="col-xl-6">
                  <div class="card-body p-md-5 text-black">
                    <h3 class="mb-5 text-uppercase" style="padding-top: 40px;">Formulaire d'inscription psychiatre</h3>

                    <div class="form-outline mb-4 " style="margin-top:200px;">
                      <input type="email" id="form3Example8" name="email" class="form-control form-control-lg" />
                      <label class="form-label" for="form3Example8">Adresse Email</label>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="password" name="MDP" id="form3Example9" class="form-control form-control-lg" />
                      <label class="form-label" for="form3Example9">Mot De Passe</label>
                    </div>

                    <div class="d-flex justify-content-end pt-3">
                      <button type="reset" class="btn btn-light btn-lg">Annuler</button>
                      <button type="submit" name="connecter" class="btn  btn-lg ms-2" style="background-color: #66CDAA; color:black;">Connecter</button>
                    </div>
                    <p>J'ai n'est pas Un Compte <a href="sinscrire.php">S'inscrire</a></p>
                    <?php if (isset($errorMsg)) {
                      echo '<p>' . $errorMsg . '</p>';
                    } ?>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </form>


  <!-- MDB -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"></script>
</body>

</html>