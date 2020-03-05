<?php 

require_once('include/start.php');

// If the user is not logged in redirect to the login page...
if ( $user->isLoggedin() ) 
{
	header('Location: index.php');
	exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{

    $errors = array();

    $retval = $user->authLogin($_POST, $errors);

    if ($retval == true)
    {
        header("Location:index.php");
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
    <section id="main">

    <?php 
    
    if(!empty($errors))
    {
        foreach ($errors as $name=>$error)
        {
            echo "<p>".$error."</p>";
        }
        echo "<p> Invalid credentials. Enter again.</p>";
    }
    else
    {
        echo "<h1>Sign in to explore!</h1>";
    }
    ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <p>
              <label>Username</label><br>
              <input type="text" name="username" placeholder="johndoe" autofocus required>
            </p>
            <p>
                <label>Password</label><br>
              <input type="password" name="password" required>
            </p>
            <p>
              <button class='btn second'>Login</button>
            </p>
          </form>
          <p>
          New user? <strong><a href='register.php'>Register now</a></strong>
          </p>
    </section>
</body>
</html>
