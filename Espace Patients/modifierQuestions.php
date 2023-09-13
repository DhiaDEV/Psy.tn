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

  if (isset($_GET['question']) && !empty($_GET['question'])) {
    $id_quest = (int)$_GET['question'];
    if (isset($_POST['valider'])) {

      $new_titre = $_POST['titre'];
      $new_desc = $_POST['desc'];

      $modifierRdv = $bdd->prepare('UPDATE questions SET titre=?, contenu=? ,date_creation = NOW() WHERE QuestionID=?');
      $modifierRdv->execute(array($new_titre, $new_desc, $id_quest));

      echo '<script>alert("Modification bien enregistrée !");</script>';
      echo '<script>window.location.href = "MesQuestions.php?userID=' . $patientInfo['userID'] . '";</script>';
          }




    $recupQuestion = $bdd->prepare('SELECT * FROM questions WHERE QuestionID=?');
    $recupQuestion->execute(array($id_quest));
    $question = $recupQuestion->fetch();
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Psy.tn: Modifier Questions</title>

  <!-- Favicon -->
  <link href="img/logoF.png" rel="icon">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
  <!-- MDB -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css" rel="stylesheet" />
  <style>
    .container {
      width: 50%;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
  </style>
</head>

<body>

  <div class="container">
    <form action="" method="POST">
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Titre</label>
        <input type="text" name="titre" value="<?php echo $question['titre']  ?>" class="form-control" id="exampleFormControlInput1">
      </div>
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Description</label>
        <textarea type="text" name="desc" class="form-control" id="exampleFormControlInput1" cols="30" rows="10"><?php echo $question['contenu'] ?></textarea>
      </div>
      <button class="btn btn-warning" type="submit" name="valider">Modifier</button>
      <a href="MesQuestions.php?userID=<?php echo $patientInfo['userID'] ?>" class="btn btn-danger" name="valider">Annuler</a>

    </form>
  </div>

  <!-- MDB -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"></script>
</body>

</html>