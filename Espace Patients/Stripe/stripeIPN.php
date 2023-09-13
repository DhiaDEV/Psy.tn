<?php
require_once "config.php";
require_once "tarification.php";

// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost; dbname=psy.tn;', 'root', '');

// Vérification de l'authentification de l'utilisateur
if (!$_SESSION['auth']) {
    header('Location: index.php');
}

// Désactiver la vérification des certificats SSL
\Stripe\Stripe::setVerifySslCerts(false);

// Token est créé en utilisant Checkout ou Elements!
// Obtenez l'ID de jeton de paiement soumis par le formulaire
$consultationID = $_GET['id'];

// Vérifiez si le jeton Stripe et la consultation existent
if (!isset($_POST['stripeToken']) || !isset($consultation[$consultationID])) {
    header("Location: tarification.php");
    exit();
}

// Obtenir le jeton de paiement et l'adresse e-mail associée
$token = $_POST['stripeToken'];
$email = $_POST["stripeEmail"];
$currentDate = date("Y-m-d H:i:s");

// Charge la carte de l'utilisateur
$charge = \Stripe\Charge::create(array(
    "amount" => $consultation[$consultationID]["price"],
    "currency" => "USD",
    "description" => $consultation[$consultationID]["title"],
    "source" => $token,
));

// Obtenir l'ID de rendez-vous à mettre à jour à partir de l'URL
$getidP = $_GET['IDPsy'];
$getidR = $_GET['IDRdv'];

// Get the appointment details from the database
$sql = "SELECT * FROM rendez_vous WHERE IDRdv = ?";
$stmt = $bdd->prepare($sql);
$stmt->execute(array($getidR));
$appointment = $stmt->fetch();

// Check if the appointment exists and the email address matches
if (!$appointment || $appointment['EmailP'] !== $email) {
    // Either the appointment doesn't exist or the email doesn't match
    echo "Erreur:Paiement échoué. Vérifier Votre Email.";
    exit();
}


$insertPay =$bdd->prepare("INSERT INTO paiements(IDPatient , IDPsychiatre , IDRendez_vous, Montant , Date) VALUES (?,?,?,?,?)");
$insertPay->execute(array($getid,$getidP,$getidR ,$charge->amount, $currentDate));

$sql = "UPDATE rendez_vous SET paye = 'succès', Statut='Ancien' WHERE IDRdv=?";
$stmt = $bdd->prepare($sql);
$result = $stmt->execute(array( $getidR));

if ($insertPay) {
    echo '<script>alert("Succès ! Vous avez été facturé de ' . ($consultation[$consultationID]["price"] / 100) . ' TND.");
    window.location="/Psy.tn/Espace%20Patients/ChatLive_WebRTC/home.php"; </script>';

} else {
    echo "Paiement échoué";
}
