<?php

require_once('include/start.php');
require_once('class/activity.php');
// If the user is not logged in redirect to the login page...
if ( !$user->isLoggedin() ) 
{
	header('Location: login.php');
	exit();
}

$errors = array();

$activity = new Activity($con);
$activities = array();
$retval = $activity->getBookedActivities($activities);

if(!$retval || empty($activities))
{   
    $errors['general'] = "Oops. No Activities available at the moment.";
}


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
      <?php $page='booked'; require("include/navbar.php"); ?>
    </header>  
    <section id="main">
    
    <?php     
        if(!empty($errors))
        {
            foreach ($errors as $name=>$error)
            {
                echo "<p>".$error."<br /></p>";
            }
            exit();
        }
        elseif(!empty($message))
        {
          echo "<p>".$message."<br /></p>";
          exit();
        }
        else
        {
            echo "<h1>Booked activties</h1>";
        }

    ?>

        <table>
            <tr>
            <th>S No</th>
            <th>Activity name</th>
            <th>Date</th>
            <th>No. of tickets</th>
            </tr>

            <?php 
            $count = 1;
            foreach ($activities as $act)
            {
            ?>   

            <tr>
                <td> <?php echo $count; ?> </td>
                <td> <?php echo htmlspecialchars(trim($activity->getActivityName($act['activityID']))); ?> </td>
                <td><?php echo date("d-m-Y",strtotime($act['date_of_activity'])); ?> </td>
                <td><?php echo htmlspecialchars(trim($act['number_of_tickets'])); ?> </td>    
            </tr>

            <?php
            $count = $count+1;
            } 
            ?>

        </table>

    </section>
</body>
</html>
