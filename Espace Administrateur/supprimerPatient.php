<?php
session_start();
//Connexion a la Base De Données
$bdd= new PDO('mysql:host=localhost; dbname=psy.tn;','root','');

//Sécurité... 
if(!$_SESSION['auth']){
    header('Location: login.php');

}
if(isset($_GET['AdminID']) AND $_GET['AdminID']>0){
    $getid= intval($_GET['AdminID']); //intval pour sécuriser l'id 
    //Récupérer les données de client par l id qui entrer
    $recupAdmin= $bdd->prepare('SELECT * FROM administrateurs WHERE AdminID= ?');
    $recupAdmin->execute(array($getid));
    $AdminInfo= $recupAdmin->fetch();


    if(isset( $_GET['supprimer']) AND !empty($_GET['supprimer'])){
        // En recuperer l'id qui envoyer a modifier
        $getId=$_GET['supprimer'];
        $req=$bdd->prepare('DELETE FROM compte_utilisateurs WHERE userID= ?');
        $req->execute(array($getId));
        header('Location: utilisateurs.php?AdminID='. $AdminInfo['AdminID']);
    
    
    }
  

}