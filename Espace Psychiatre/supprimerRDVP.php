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

 // Pour supprimer un rendez-vous 
 if(isset($_GET['supprimer']) && !empty($_GET['supprimer'])){
    $rdv_id = (int)$_GET['supprimer'];
    $req = $bdd->prepare('DELETE FROM rendez_vous WHERE IDRdv = ?');
    $req->execute(array($rdv_id));
    header('Location: rendez-vousP.php?userID=' .$psyInfo['userID']);   
}

}

?>