<?php
session_start();
//Connexion a la Base De Données
$bdd= new PDO('mysql:host=localhost; dbname=psy.tn;','root','');

//Sécurité... 
if(!$_SESSION['auth']){
    header('Location: index.php');

}
if(isset($_GET['userID']) AND $_GET['userID']>0){
    $getid= intval($_GET['userID']); //intval pour sécuriser l'id 
    //Récupérer les données de Patient par l id qui entrer
    $recupPatient= $bdd->prepare('SELECT * FROM compte_utilisateurs WHERE userID= ?');
    $recupPatient->execute(array($getid));
    $patientInfo= $recupPatient->fetch();

     // Pour supprimer un rendez-vous 
    if(isset($_GET['IDRdv']) && !empty($_GET['IDRdv'])){
    $rdv_id = (int)$_GET['IDRdv'];
    $req = $bdd->prepare('DELETE FROM rendez_vous WHERE IDRdv = ?');
    $req->execute(array($rdv_id));
    header('Location: MesRDV.php?userID=' .$patientInfo['userID']);   
}

}
