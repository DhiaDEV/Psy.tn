<?php 
    session_start();

    require 'classes/DB.php';
    require 'classes/User.php';

    $userObj = new \MyApp\User ;

    define('BASE_URL' , 'http://localhost:7882/Psy.tn/Espace%20Patients/ChatLive_WebRTC/' );

?>