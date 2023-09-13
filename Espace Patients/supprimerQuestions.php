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


    // Pour supprimer un Question 
    if(isset($_GET['supprimer']) && !empty($_GET['supprimer'])){
        $id_quest = (int)$_GET['supprimer'];

        $supprimer=$bdd->prepare('DELETE FROM questions WHERE QuestionID =?');
        $supprimer->execute(array($id_quest));
        header('Location: MesQuestions.php?userID=' .$patientInfo['userID']);   


    }}