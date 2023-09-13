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

	//Pour confirmer un Psy
    if(isset($_GET['confirmer']) AND !empty($_GET['confirmer'])){
        $confirmer = (int) $_GET['confirmer'];
        $req = $bdd->prepare('UPDATE psychiatres SET Confirmer = 1 WHERE PsyID = ?');
        $req->execute(array($confirmer));
        header('Location: doctor-list.php?AdminID='. $AdminInfo['AdminID']);
    
    }}