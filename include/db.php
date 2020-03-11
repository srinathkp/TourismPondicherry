<?php

$url = array('/quser636/include/start.php','/quser636/include/db.php', '/quser636/class/user.php','/quser636/include/navbar.php','/quser636/class/activity.php');
if(in_array($_SERVER['REQUEST_URI'],$url))
{
    header('HTTP/1.0 403 Forbidden');
    exit();
}
// connection info.
$DATABASE_HOST = '';
$DATABASE_USER = '';
$DATABASE_PASS = '';
$DATABASE_NAME = '';

// connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
    die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}

?>
