<?php
require_once "config.php";

session_start();
//Connexion a la Base De Données
$bdd = new PDO('mysql:host=localhost; dbname=psy.tn;', 'root', '');

//Sécurité... 
if (!$_SESSION['auth']) {
    header('Location: \Psy.tn/index.php');
}
if (isset($_GET['userID']) and $_GET['userID'] > 0) {
    $getid = intval($_GET['userID']); //intval pour sécuriser l'id 
    //Récupérer les données de client par l id qui entrer
    $recupPatient = $bdd->prepare('SELECT * FROM compte_utilisateurs WHERE userID= ?');
    $recupPatient->execute(array($getid));
    $patientInfo = $recupPatient->fetch();

    if (isset($_GET['IDPsy']) and $_GET['IDPsy'] > 0) {
        $getidP = intval($_GET['IDPsy']);

        if (isset($_GET['IDRdv']) and $_GET['IDRdv'] > 0) {
            $getidR = intval($_GET['IDRdv']);

?>
        <!doctype html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Page de tarification</title>
            <link rel="stylesheet" href="bootstrap-4.0.0-dist/css/bootstrap.min.css">
            <style type="text/css">
                .container {
                    margin-top: 100px;

                }

                .container {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }

                .card {
                    width: 1000px;
                    height: 500px;

                }

                .card-body {
                    padding-top: 220px;

                }

                .card:hover {
                    -webkit-transform: scale(1.05);
                    -moz-transform: scale(1.05);
                    -ms-transform: scale(1.05);
                    -o-transform: scale(1.05);
                    transform: scale(1.05);
                    -webkit-transition: all .3s ease-in-out;
                    -moz-transition: all .3s ease-in-out;
                    -ms-transition: all .3s ease-in-out;
                    -o-transition: all .3s ease-in-out;
                    transition: all .3s ease-in-out;
                }

                .list-group-item {
                    border: 0px;
                    padding: 5px;
                }

                .price {
                    font-size: 72px;
                }

                .currency {
                    position: relative;
                    font-size: 25px;
                    top: -31px;
                }

                .card-title h2 {
                    font-size: 24px;
                }
            </style>
        </head>

        <body>
            <div class="container ">
                <?php
                foreach ($consultation as $consultationID => $attributes) {
                    echo '<div class="row">';
                    echo '
                <div class="col-md-4">
                    <div class="card">
                      
                        <div class="card-body text-center">
                            <div class="card-title">
                                <h2>' . $attributes['title'] . '</h2>
                            </div>

                            ';
                    echo '
                            <br>
                            <form action="stripeIPN.php?id=' . $consultationID . '&userID=' . $getid . '&IDPsy=' . $getidP . '&IDRdv=' . $getidR . '" method="POST">
                            <script
                              src="https://checkout.stripe.com/checkout.js" class="stripe-button"

                                data-key="' . $stripeDetails['publishableKey'] . '"
                                data-amount="' . $attributes['price'] . '"
                                data-name="' . $attributes['title'] . '"
                                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                                data-locale="auto">
                              </script>
                            </form>
                        </div>
                    </div>
                </div>
            ';
                }
                ?>
            </div>
        </body>
        </html>
<?php }
}}
?>