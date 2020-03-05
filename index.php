<?php

require_once('include/start.php');

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1" >
    <title>Pondicherry Tourism</title>
</head>
<body>
    <header>
      <?php $page='index'; require("include/navbar.php"); ?>
    </header>  
    <section id="main">
        <h1>Welcome to Pondicherry tourism!</h1>
        <em>
        Pondicherry (/pɒndɪˈtʃɛri/), officially known as Puducherry (/pʊdʊˈtʃɛri/), is the capital and the most populous city of the Indian Union Territory of Puducherry, 
        with a population of 657,209 and an area of 492 km2. The city is located in the Puducherry district on the southeast coast of India, 
        and is surrounded by the state of Tamil Nadu with which it shares most of its culture and language.  The city has many colonial buildings, churches, temples and statues which, 
        combined with the town planning and French style avenues in the old part of town, still preserve much of the colonial ambiance.The Sri Aurobindo Ashram, located on rue de la Marine, is one of the most important ashrams in India, founded by the renowned Freedom Fighter and spiritual philosopher Sri Aurobindo. Auroville (City of Dawn) is an "experimental" township located 8 km north-west of Pondicherry.
        There are a number of old and large churches in Pondicherry, most of which were built in the 18th and 19th centuries. A number of heritage buildings and monuments are present around the Promenade Beach, such as the Children's Park and Dupleix Statue, Gandhi statue, Nehru Statue, Le Café, French War Memorial, 19th Century Light House, Bharathi Park, Governors Palace, Romain Rolland Library, Legislative Assembly, 
        Pondicherry Museum and the French Institute of Pondicherry at Saint Louis Street.
        </em>

        <?php 
        if ( !$user->isLoggedin() ) 
        { ?>
            <p>Login/Register now to book activities.</p>
            <p>
            <a href='login.php' class='btn first'>Login now </a>
            <a href='register.php'>New user? Register now!</a>
            </p>
        <?php
        }
        else{
            echo('<p><strong>Welcome '.$_SESSION['name'].', Explore Pondicherry this vacation! Book any activity now, Pay later!</strong></p>');
        }
        ?>    

    </section>
</body>
</html>