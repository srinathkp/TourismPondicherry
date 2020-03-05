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

  $retval = $user->doRegistration($_POST, $errors);

  if ($retval == true)
  {
    $message = 'Registration successful. Redirecting to Login Page in 3 seconds';
    header("refresh:3;url=login.php");
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
            echo "<h1>Alright, Register to get started!</h1>";
        }

        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <p>
              <label>First name</label><br>
              <input type="text" name="firstname" placeholder="John" autofocus required>
            </p>
            <p>
              <label>Sur name</label><br>
              <input type="text" name="lastname" placeholder="Doe" required>
            </p>
            <p>
              <label>Date of Birth</label><br>
              <input type="date" name="dob"  min="<?php echo date('Y-m-d', strtotime("-100 years"));?>" max="<?php echo date('Y-m-d', strtotime("-18 years"));?>" required>
            </p>
            <p>
              <label>Address Line 1</label><br>
              <input type="text" name="address1" placeholder="221B Baker street" required>
            </p>
            <p>
              <label>Address Line 2</label><br>
              <input type="text" name="address2" placeholder="London" required>
            </p>
            <p>
              <label>Zip Code</label><br>
              <input type="text" placeholder="NW1 6XE" title="3 characters minimum" name="pincode" required>
            </p>
            <p>
              <label>Username (Based on availability)</label><br>
              <input type="text" name="username" placeholder="johndoe" required>
            </p>
            <p>
                <label>Password</label><br>
              <input type="password" name="password" required>
            </p>
            <p>
              <button class='btn first'>Register</button>
              <button class='btn first' type="reset">Reset form</button>
            </p>
          </form>
          <p>
          Not a new user? <strong><a href='login.php'>Sign in</a></strong> to access your account.
          </p>
    </section>
</body>
</html>