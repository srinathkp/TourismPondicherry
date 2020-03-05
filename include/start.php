<?php
$url = array('/quser636/include/start.php','/quser636/include/db.php', '/quser636/class/user.php','/quser636/include/navbar.php','/quser636/class/activity.php');
if(in_array($_SERVER['REQUEST_URI'],$url))
{
    header('HTTP/1.0 403 Forbidden');
    exit();
}

session_start();

// connect to db
require_once('db.php');

// Add user class
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/quser636/class/user.php');

$user = new User($con);

?>