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
$retval = true;
$activity = new Activity($con);
$activities = array();

if(isset($_GET['search1']))
{
    $retval = $activity->searchActivities($_GET, $activities);

    if(!$retval || empty($activities))
    {
        $errors['general'] = "Oops. No Activities available at the moment.";
    }
    // unset($_GET['search1']);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js"></script>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1" >
    <title>Pondicherry Tourism</title>
</head>
<body>
    <header>
      <?php $page='search'; require("include/navbar.php"); ?>
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
        elseif(!empty($message))
        {
          echo "<p>".$message."<br /></p>";
          exit();
        }
        else
        {
            echo "<h1>Search for activities</h1>";
        }
    ?>

    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
    <p>
    <input type="text" name="search1" value="<?php if(isset($_GET['search1'])){echo htmlspecialchars($_GET['search1']);}?>" id="search1" placeholder="Search text goes here.." autofocus required>
    <select name="condition">
        <option value="AND">AND</option>
        <option value="OR">OR</option>
    </select>    
    <input type="text" name="search2" value="<?php if(isset($_GET['search2'])){echo htmlspecialchars($_GET['search2']);}?>" id="search2" placeholder="additional">
    <button class='btn first'>Search</button>
    </p>
    </form>
    
    <?php
        if($retval && !empty($activities))
        {
            echo "<ol id=\"search_target\">";
            foreach ($activities as $act)
            {
                echo "<li><h3>".htmlspecialchars(trim($act['activity_name']))."</h3>";
                echo "<ul><li><em>".htmlspecialchars(trim($act['description']))."</em></li>";
                echo "</ul></li>";
            }
            echo "</ol>";
        }            
    ?>                


    </section>

</body>
</html>
