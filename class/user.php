<?php

$url = array('/quser636/include/start.php','/quser636/include/db.php', '/quser636/class/user.php','/quser636/include/navbar.php','/quser636/class/activity.php');
if(in_array($_SERVER['REQUEST_URI'],$url))
{
    header('HTTP/1.0 403 Forbidden');
    exit();
}

Class User
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    public function isLoggedin()
    {
        if(isset($_SESSION['loggedin']) && isset($_SESSION['name']) && isset($_SESSION['id']) )
        {
            return true;
        }
    }

    public function authLogin($postdata, &$errors)
    {
        // Now we check if the data was submitted, isset() function will check if the data exists.
        if (!isset($postdata['password']) || empty($postdata['password']) ) {
            // Could not get username or it is empty
            $errors['username'] = 'Username Invalid! ';
            return false;
        }
        // Make sure the submitted values are not empty.
        if (!isset($postdata['password']) || empty($postdata['password']) ) {
            // Could not get password or it is empty
            $errors['password'] = 'Password Invalid';
            return false;
        }

        // We need to check if the account with that username exists.
        if ($stmt = $this->db->prepare('SELECT customerID, password_hash FROM customers WHERE username = ?')) {
            // Bind parameters
            $stmt->bind_param('s', $postdata['username']);
            $stmt->execute();
            $stmt->store_result();
            // Store the result so we can check if the account exists in the database.
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $password);
                $stmt->fetch();
                // Account exists, now we verify the password.
                if (sha1($postdata['password']) == $password) {
                    // Verification success! User has loggedin!
                    // Create sessions so we know the user is logged in
                    session_regenerate_id();
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['name'] = $postdata['username'];
                    $_SESSION['id'] = $id;
                    return true;
                } else {
                    $errors['password'] = 'Incorrect password!';
                    return false;
                }
            } else {
                $errors['username'] = 'Incorrect username!';
                return false;
            }
            
            $stmt->close();
        } 
        else {
            // Something is wrong with the sql statement, check to make sure accounts table exists with all fields.
            $errors['general'] = 'Database Internal Error! Try again later';
        }
        $this->db->close();
        header("refresh:1;url=login.php");
    }

    public function sanitizeFields($input) {
        return htmlspecialchars(trim($input));
    }

    public function checkDateRange($input)
    {
        if($input < date('Y-m-d', strtotime("-100 years")) || $input > date('Y-m-d', strtotime("-18 years")))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    public function validateFields($postdata, &$errors)
    {        
        // Now we check if the data was submitted, isset() function will check if the data exists.
        if (!isset($postdata['firstname'], $postdata['lastname'], $postdata['address1'], $postdata['address2'], $postdata['pincode'], 
        $postdata['dob'], $postdata['username'], $postdata['password'])) 
        {
            // Could not get the data that should have been sent.
            $errors['general'] = 'One or more fields not set. Please complete the registration form!';
            return false;
        }
        // Make sure the submitted registration values are not empty.
        if (empty($postdata['firstname']) || empty($postdata['lastname']) || empty($postdata['address1']) || empty($postdata['address2']) 
        || empty($postdata['pincode']) || empty($postdata['dob']) || empty($postdata['username']) || empty($postdata['password'])) 
        {
            // One or more values are empty.
            $errors['general'] = 'One or more fields empty. Please complete the registration form';
            return false;
        }

        if(strlen($postdata['username'])< 8)
        {
            $errors['username'] = 'Username should have minimum 8 characters';
            return false;
        }

        if(!$this->checkDateRange($postdata['dob']))
        {
            $errors['dob'] = 'Invalid DOB. Age should be between 18 & 100';
            return false;
        }


        return true;
    }

    public function doRegistration($postdata,&$errors)
    {
        $retval = $this->validateFields($postdata, $errors);
        if(!$retval)
        {
            return $retval;
        }

        foreach ($postdata as $key=>$value)
        {
            if($key != 'password')
            {
            $postdata[$key] = $this->sanitizeFields($value);
            }
        }

        // We need to check if the account with that username exists.
        if ($stmt = $this->db->prepare('SELECT customerID, password_hash FROM customers WHERE username = ?')) {
            // Bind parameters
            $stmt->bind_param('s', $postdata['username']);
            $stmt->execute();
            $stmt->store_result();
            // Store the result so we can check if the account exists in the database.
            if ($stmt->num_rows > 0) 
            {
                // Username already exists
                $errors['username'] = 'Username exists, please choose another!';
                return false;
            } 
            else 
            {
                // Insert new account
                if ($stmt = $this->db->prepare('INSERT INTO customers (customer_forename, customer_surname, customer_address1,
                customer_address2, customer_postcode, date_of_birth, username, password_hash)
                VALUES (?,?,?,?,?,?,?,?)')) {
                    // Hash the password using sha1
                    $password = sha1($postdata['password']);

                    $rc=$stmt->bind_param('ssssssss', $postdata['firstname'], $postdata['lastname'], $postdata['address1'], $postdata['address2'], 
                    $postdata['pincode'], $postdata['dob'], $postdata['username'], $password);
                    if (false === $rc)
                    {
                        $errors['general'] = 'Insert failed. Try again';
                        return false;
                    }
                    $rc = $stmt->execute();
                    if ( false===$rc ) {
                        $errors['general'] = 'Insert failed. Try again';
                        return false;
                    }
                    if ($stmt->affected_rows>0)  
                    {
                        $stmt->close();
                        return true;                            
                    }
                    else
                    {
                        $errors['general'] = 'Insert failed. Try again';
                        return false;
                    }
                } 
                else {
                // Something is wrong with the sql statement, check to make sure accounts table exists with all fields.
                $errors['general'] = 'Insert failed. Try again';
                return false;
                }
            }
            $stmt->close();
        } 
        else {
            // Something is wrong with the sql statement, check to make sure accounts table exists with all fields.
            $errors['general'] = 'Insert failed. Try again';
            return false;
        }
        $this->db->close();
        header("Location:register.php");    
    }

    public function returnUID($username)
    {
        if ($stmt = $this->db->prepare('SELECT customerID FROM customers WHERE username = ?')) 
        {
            // Bind parameters (s = string, i = int, b = blob, etc)
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            // Store the result so we can check if the account exists in the database.
            if ($stmt->num_rows > 0) 
            {
                $stmt->bindresult($userid);
                // Username exists and UID is returned
                while($stmt->fetch())
                {
                    $stmt->close();
                    return $userid;
                }
            } 
            else 
            {
                return false;
            }    
        }
    }
}

?>