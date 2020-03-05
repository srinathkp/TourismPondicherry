<?php 
$url = array('/quser636/include/start.php','/quser636/include/db.php', '/quser636/class/user.php','/quser636/include/navbar.php','/quser636/class/activity.php');
if(in_array($_SERVER['REQUEST_URI'],$url))
{
    header('HTTP/1.0 403 Forbidden');
    exit();
}
?>
<nav>
<menu id="navbar">
<li><a <?php echo ($page == 'index') ? "class='active'" : ""; ?> href="index.php">Home</a></li>
<li><a <?php echo ($page == 'activities') ? "class='active'" : ""; ?> href="activities.php">Activities</a></li>
<li><a <?php echo ($page == 'book_activities') ? "class='active'" : ""; ?> href="book_activities.php">Book now</a></li>
<li><a <?php echo ($page == 'booked') ? "class='active'" : ""; ?> href="booked_activities.php">Booked activities</a></li>
<li><a <?php echo ($page == 'credits') ? "class='active'" : ""; ?> href="credits.php">Credits</a></li>
<li><a <?php echo ($page == 'search') ? "class='active'" : ""; ?> href="search_activities.php">Search</a></li>
<?php 
if ( $user->isLoggedin() ) 
{
    echo "<li><a href=\"logout.php\">Logout</a></li>";
}
else
{
    echo "<li><a href=\"login.php\">Login</a></li>";
}
?>
</menu>
</nav>