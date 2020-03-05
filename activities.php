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
      <?php $page='activities'; require("include/navbar.php"); ?>
    </header>  
    <section id="main">
        <h1>Available Activities</h1>
        
        <?php
            $activity = new Activity($con);
            $activities = array();
            $retval = $activity->getActivities($activities);

            if(!$retval || empty($activities))
            {
                echo "<em> Oops. No Activities found at the moment </em>";
            }
            else
            {
                echo "<ol>";
                foreach ($activities as $act)
                {
                    echo "<li><h3>".htmlspecialchars(trim($act['activity_name']))."</h3>";
                    echo "<ul><li><em>".htmlspecialchars(trim($act['description']))."</em></li>";
                    echo "<li><strong>Price:".htmlspecialchars(trim($act['price']))." Pounds</strong></li>";
                    echo "</ul></li>";
                }
                echo "</ol>";
            }
        ?>

    </section>
</body>
</html>