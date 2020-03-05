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
$retval = $activity->getActivities($activities);

if(!$retval || empty($activities))
{
    $errors = "Oops. No Activities available at the moment.";
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{

  $retval = $activity->bookActivities($_POST, $errors);

  if ($retval == true)
  {
    $msg = 'Booking successful. Redirecting to Booked activities page in 3 seconds';
    header("refresh:3;url=booked_activities.php");
  }
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
    <?php $page='book_activities'; require("include/navbar.php"); ?>
    </header> 

    <section id="main">

        <?php 
        if(!empty($errors))
        {
            foreach ($errors as $name=>$error)
            {
                echo "<p>".$error."<br /></p>";
            }
        }
        elseif(!empty($msg))
        {
            echo "<p>".$msg."<br /></p>";
            exit();
        }
        else
        {
            echo "<h1>Book any activity now! Pay later.</h1>";
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

            <p>
              <label>Number of tickets</label><br>
              <input type="number" name="numberoftickets" value=1 max=10 autofocus required>
            </p>

            <p>
            <label>Activity</label><br>
            <select name="activity" size="2" required>
            <?php foreach ($activities as $act) { ?>
                <option value=<?php echo "\"".htmlspecialchars(trim($act['activityID']))."\"";?> >
                <?php echo htmlspecialchars(trim($act['activity_name'])); ?>
                </option>
            <?php } ?>
            </select>
            </p>

            <p>
              <label>Pick a date</label><br>
              <input type="date" name="date" value = "<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d');?>"
              max = "<?php echo date('Y-m-d', strtotime("+60 days"));?>" required>
            </p>

            <p>
              <button class='btn second'>Confirm Booking</button>
            </p>

        </form>

    </section>
</body>
</html>