<?php
	require_once "stripe-php-master/init.php";
	require_once "consultation.php";


	$stripeDetails = array(
		"secretKey" => "your secret key",
		"publishableKey" => "your publish key"
	);

	// Set your secret key: remember to change this to your live secret key in production
	// See your keys here: https://dashboard.stripe.com/account/apikeys
	\Stripe\Stripe::setApiKey($stripeDetails['secretKey']);
?>
