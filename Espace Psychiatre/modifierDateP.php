<?php
session_start();
//Connexion a la Base De Données
$bdd= new PDO('mysql:host=localhost; dbname=psy.tn;','root','');

//Sécurité... 
if(!$_SESSION['auth']){
    header('Location: connexionPsy.php');

}
if(isset($_GET['userID']) AND $_GET['userID']>0){
    $getid= intval($_GET['userID']); //intval pour sécuriser l'id 
    //Récupérer les données de client par l id qui entrer
    $recupPsy= $bdd->prepare('SELECT * FROM compte_utilisateurs u , psychiatres p WHERE u.userID = p.PsyID AND userID= ?');
    $recupPsy->execute(array($getid));
    $psyInfo= $recupPsy->fetch();

     // Pour modifier date  d'un rendez-vous 
 if(isset($_GET['modifier']) && !empty($_GET['modifier'])){
    $rdv_id = (int)$_GET['modifier'];
    if(isset($_POST['valider'])) {
        // Get the new date and time values from the form
        $new_date = $_POST['date'];
        $new_time = $_POST['time'];

        // Update the rendez-vous date and time in the database
        $modifierRdv=$bdd->prepare('UPDATE rendez_vous SET Date=?, Time=? WHERE IDRdv=?');
        $modifierRdv->execute(array($new_date, $new_time, $rdv_id));

        // Redirect to the rendez-vous list page
        echo '<script>alert("Modification bien enregistrée !");</script>';
        echo '<script>window.location.href = "rendez-vousP.php?userID=' . $psyInfo['userID'] . '";</script>'; 
    }

    $recupDateTime=$bdd->prepare('SELECT * FROM rendez_vous WHERE IDRdv=?');
    $recupDateTime->execute(array($rdv_id));
    $rdv = $recupDateTime->fetch();

       
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Date</title>
    <!-- Font Awesome -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
  rel="stylesheet"
/>
<!-- Google Fonts -->
<link
  href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
  rel="stylesheet"
/>
<!-- MDB -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css"
  rel="stylesheet"
/>
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

<div  class="container">
    <form action="" method="POST">
<div class="mb-3">
  <label for="exampleFormControlInput1" class="form-label">Date</label>
  <input type="date" name="date" value="<?php echo $rdv['Date'] ?>"  class="form-control" id="exampleFormControlInput1">
</div>
<div class="mb-3">
  <label for="exampleFormControlInput1" class="form-label">Time</label>
  <input type="time" name="time" value="<?php echo $rdv['Time'] ?>" class="form-control" id="exampleFormControlInput1">
</div>
<button class="btn btn-warning" type="submit" name="valider">Modifier</button>
</form>
</div>

<!-- MDB -->
<script
  type="text/javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"
></script>
</body>
</html>