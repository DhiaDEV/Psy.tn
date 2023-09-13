<?php
session_start();
//Connexion a la Base De Données
$bdd = new PDO('mysql:host=localhost; dbname=psy.tn;', 'root', '');

//validation de formulaire
if (isset($_POST['sinscrire'])) {
  //vérifier si le Psy a completer  tous les champs 
  if (
    !empty($_POST['pseudo']) and !empty($_POST['prenom']) and !empty($_POST['nom']) and !empty($_POST['numT']) and !empty($_POST['email'])
    and !empty($_POST['CIN']) and !empty($_POST['latitude'] and !empty($_POST['longitude'])) and !empty($_POST['gouvernorat'])
    and !empty($_POST['lieu']) and !empty($_POST['adresse']) and !empty($_POST['description']) and !empty($_POST['MDP'])
  ) {
    //Les données de Psy
    $psypseudo = htmlspecialchars($_POST['pseudo']);
    $psyprenom = htmlspecialchars($_POST['prenom']);
    $psynom = htmlspecialchars($_POST['nom']);
    $psynumero = htmlspecialchars($_POST['numT']);
    $psyemail = htmlspecialchars($_POST['email']);
    $psycin = htmlspecialchars($_POST['CIN']);
    $psylatitude = htmlspecialchars($_POST['latitude']);
    $psylongitude = htmlspecialchars($_POST['longitude']);
    $psygouvernorat = htmlspecialchars($_POST['gouvernorat']);
    $psylieu = htmlspecialchars($_POST['lieu']);
    $specialite = htmlspecialchars($_POST['specialite']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $description = nl2br($_POST['description']);


    $psymdp = password_hash($_POST['MDP'], PASSWORD_DEFAULT);


    $photoType = $_FILES["photo"]["type"];
    $photoName = $_FILES["photo"]["name"];
    $photo = $_FILES["photo"]["tmp_name"];
    move_uploaded_file($photo, "images/profileImage/" . $photoName);
    $position = "images/profileImage/" . $photoName;

    //Carte Pro
    $carteType = $_FILES["carte"]["type"];
    $carteName = $_FILES["carte"]["name"];
    $carte = $_FILES["carte"]["tmp_name"];
    move_uploaded_file($carte, "images/carteProfessionnel/" . $carteName);
    $position_carte = "images/carteProfessionnel/" . $carteName;


    // verifier si le Psy existe deja dans le site
    $Psyexiste = $bdd->prepare('SELECT Email , username FROM compte_utilisateurs WHERE Email=? OR username=?');
    $Psyexiste->execute(array($psyemail, $psypseudo));

    if ($Psyexiste->rowCount() == 0) {
      // insérer le Psy dans le BDD
      $insertPsy = $bdd->prepare('INSERT INTO compte_utilisateurs(username,Nom, Prenom ,Email ,NumeroT ,PhotoProfile,MotDePasse,Role) VALUES(?,?,?,?,?,?,?,1)');
      $insertPsy->execute(array($psypseudo, $psynom, $psyprenom, $psyemail, $psynumero, $photoName, $psymdp));

      $user_id = $bdd->lastInsertId();
      $insertPsyS = $bdd->prepare('INSERT INTO psychiatres(PsyID,Cin,Gouvernorat,Lieu,Specialite,Adresse,Description,CartePro,latitude,longitude) VALUES (?,?,?,?,?,?,?,?,?,?)');
      $insertPsyS->execute(array($user_id, $psycin, $psygouvernorat, $psylieu, $specialite, $adresse, $description, $carteName, $psylatitude, $psylongitude));

      //Récuperer les informations de Psy
      $getInfosPsy = $bdd->prepare('SELECT userID ,Nom , Prenom ,Email FROM compte_utilisateurs WHERE Nom= ?  AND Prenom=? AND Email=?');
      $getInfosPsy->execute(array($psynom, $psyprenom, $psyemail));

      $PsyInfo = $getInfosPsy->fetch();
      // Authentifier le Psy sur le site et récupérer ses données dans des variables globales sessions
      $_SESSION['auth'] = true;
      $_SESSION['userID'] = $PsyInfo['userID'];
      $_SESSION['nom'] = $PsyInfo['Nom'];
      $_SESSION['prenom'] = $PsyInfo['Prenom'];
      $_SESSION['email'] = $PsyInfo['Email'];
      //Rediriger Psy sur la page votre compte
      echo "<script>alert('compte créé avec succès');
      window.location='connexionPsy.php';</script>";
      } else {
      $errorMsg = "<div class='alert alert-danger coloor' role='alert' style=' font-weight: 700;'>
              Psychiatre déja existe 
          </div>  ";
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
  <title>Connexion Psy</title>
  <style>
    .logo_login {
      background: url('images/layout_img/login_image.jpg');
      padding: 50px 0;
      height: 100%;
      background-position: center center;
      background-size: cover;
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
      padding-top: 300px;
    }
  </style>
</head>

<body>
  <form action="" method="POST" enctype="multipart/form-data">
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
                    <h3 class="mb-5 text-uppercase">Formulaire d'inscription au psychiatre</h3>

                    <div class="row">

                      <div class="form-outline mb-4">
                        <input type="text" id="form3Example9" name="pseudo" class="form-control form-control-lg" />
                        <label class="form-label" for="form3Example9">Pseudo</label>
                      </div>

                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <input type="text" id="form3Example1m" name="prenom" class="form-control form-control-lg" />
                          <label class="form-label" for="form3Example1m">Prénom</label>
                        </div>
                      </div>
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <input type="text" id="form3Example1n" name="nom" class="form-control form-control-lg" />
                          <label class="form-label" for="form3Example1n">Nom</label>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <input type="number" id="form3Example1m1" name="numT" class="form-control form-control-lg" />
                          <label class="form-label" for="form3Example1m1">Numéro De Téléphone</label>
                        </div>
                      </div>
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <input type="text" id="form3Example1n1" name="email" class="form-control form-control-lg" />
                          <label class="form-label" for="form3Example1n1">Email</label>
                        </div>
                      </div>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="text" id="form3Example8" name="CIN" class="form-control form-control-lg" />
                      <label class="form-label" for="form3Example8">Carte D'identité</label>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-4">
                        <div class="form-outline mb-4">
                          <input type="text" id="latitude" name="latitude" class="form-control form-control-lg" />
                          <label class="form-label" for="form3Example8">Latitude</label>
                        </div>
                      </div>

                      <div class="col-md-6 mb-4">
                        <div class="form-outline mb-4">
                          <input type="text" id="longitude" name="longitude" class="form-control form-control-lg" />
                          <label class="form-label" for="form3Example8">Longitude</label>
                        </div>

                      </div>
                      <button type="button" class="btn  btn-lg ms-2" style="margin-bottom: 20px; background-color: #66CDAA; color:black; " onclick="getLocation()">Obtenir l'emplacement</button>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="text" id="form3Example9" name="gouvernorat" class="form-control form-control-lg" />
                      <label class="form-label" for="form3Example9">Gouvernorat</label>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="text" id="form3Example90" name="lieu" class="form-control form-control-lg" />
                      <label class="form-label" for="form3Example90">Lieu</label>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="text" id="form3Example90" name="adresse" class="form-control form-control-lg" />
                      <label class="form-label" for="form3Example90">Adresse</label>
                    </div>

                    <div class="form-outline mb-4">
                      <textarea class="form-control form-control-lg" id="form3Example91" name="description" rows="4"></textarea>
                      <label class="form-label" for="form3Example91">Description</label>
                    </div>

                    <select name="specialite" class="form-select" aria-label="Default select example">
                      <option selected>Choisir Votre Spécialité</option>
                      <option value="psychiatre">Psychiatre</option>
                      <option value="pédopsychiatre">Pédopsychiatre</option>
                      <option value="sexologues">Sexologues</option>
                    </select>

                    <br>
                    <label class="form-label" for="form3Example99">Votre Photo</label>
                    <div class="form-outline mb-4">
                      <input type="file" id="form3Example99" name="photo" class="form-control form-control-lg" />
                    </div>

                    <label class="form-label" for="form3Example99">Carte Professionnel</label>
                    <div class="form-outline mb-4">
                      <input type="file" id="form3Example99" name="carte" class="form-control form-control-lg" />
                    </div>

                    <div class="form-outline mb-4">
                      <input type="password" id="form3Example97" name="MDP" class="form-control form-control-lg" />
                      <label class="form-label" for="form3Example97">Mot De Passe</label>
                    </div>



                    <div class="d-flex justify-content-end pt-3">
                      <button type="reset" class="btn btn-light btn-lg">Annuler</button>
                      <button type="submit" class="btn  btn-lg ms-2" name="sinscrire" style="background-color: #66CDAA; color:black;">S'inscrire</button>

                      <br>
                    </div>
                    <p>J'ai Un Compte <a href="connexionPsy.php">Se Connecter</a></p>
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


  <script>
    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    }

    function showPosition(position) {
      document.getElementById("latitude").value = position.coords.latitude;
      document.getElementById("longitude").value = position.coords.longitude;
    }
  </script>

  <!-- MDB -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"></script>
</body>

</html>