<?php

require_once('include/start.php');
require_once('class/activity.php');
// If the user is not logged in redirect to the login page...
if ( !$user->isLoggedin() ) 
{
	header('Location: login.php');
	exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1" >
    <title>Pondicherry tourism</title>
</head>
<body>
    <header>
      <?php $page='credits'; require("include/navbar.php"); ?>
    </header>  
    <section id="main">
        <h1>Pondicherry Tourism site sincere thanks the following websites</h1>
        <ul>
        <li class="credits">
            <a href="https://stackoverflow.com/"> Stack Overflow community</a>
        </li>    
        <li class="credits">
            <a href="https://www.dummies.com/web-design-development/html/notable-changes-in-html5/"> Dummies.com html5 lessons</a>
        </li>   
        <li class="credits">
            <a href="https://www.w3schools.com/php/">W3 Schools php Lessons</a>
        </li>   
        <li class="credits">
            <a href="https://www.w3schools.com/css/">W3 Schools CSS Lessons</a>
        </li>   
        <li class="credits">
            <a href="https://developer.mozilla.org/en-US/docs/Web/HTML"> Mozilla Developer Network-MDN</a>
        </li>   
        <li class="credits">
            <a href="https://traveltriangle.com/blog/things-to-do-in-pondicherry/"> Travel Triangle - Things to do in Pondicherry</a>
        </li>   
        </ul>
    </section>
</body>
</html>