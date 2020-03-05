<?php

$url = array('/quser636/include/start.php','/quser636/include/db.php', '/quser636/class/user.php','/quser636/include/navbar.php','/quser636/class/activity.php');
if(in_array($_SERVER['REQUEST_URI'],$url))
{
    header('HTTP/1.0 403 Forbidden');
    exit();
}

Class Activity
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    public function getActivityName($activityid)
    {
        if ($stmt = $this->db->prepare('SELECT activity_name FROM activities WHERE activityID = ?')) 
        {
            // Bind parameters
            $stmt->bind_param('i', $activityid);
            $stmt->execute();
            $stmt->store_result();
            // Store the result so we can check if the entry exists in the database.
            if ($stmt->num_rows > 0) 
            {
                $stmt->bind_result($activityname);
                // Activity exists and return Act name
                while($stmt->fetch())
                {
                    $stmt->close();
                    return $activityname;
                }
            } 
            else 
            {
                return false;
            }    
        }
    }

    public function getActivities(&$activities)
    {
        if ($stmt = $this->db->query('SELECT activityID,activity_name,description,price FROM activities')) 
        {
            // No of rows returned so we can check if the entry exists in the database.
            if ($stmt->num_rows > 0) 
            {
                $activities = $stmt->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return true;
            } 
            else 
            {
                return false;
            }    
        }
    }

    public function validateFields($postdata, &$errors)
    {
        // Now we check if the data was submitted, isset() function will check if the data exists.
        if (!isset($postdata['numberoftickets'], $postdata['date'], $postdata['activity'])) 
        {
            // Could not get the data that should have been sent.
            $errors['general'] = 'One or more fields not set. Please complete the form!';
            return false;
        }
        // Make sure the submitted values are not empty.
        if (empty($postdata['numberoftickets']) || empty($postdata['date']) || empty($postdata['activity'])) 
        {
            // One or more values are empty.
            $errors['general'] = 'One or more fields empty. Please complete the form';
            return false;
        }
        return true;
    }

    public function bookActivities($postdata,&$errors)
    {
        if(!isset($_SESSION['loggedin'],$_SESSION['id']))
        {
            return false;
        }
        $retval = $this->validateFields($postdata, $errors);
        if(!$retval)
        {
            return $retval;
        }

        // We need to check if the activities with selected ID exists.
        if ($stmt = $this->db->prepare('SELECT activityID,activity_name FROM activities WHERE activityID=?')) {

            $rc = $stmt->bind_param('i',$postdata['activity']);
            if (false === $rc)
            {
                $errors['general'] = 'Activity Check failed. Try again';
                return false;
            }
            $rc = $stmt->execute();
            if (false === $rc)
            {
                $errors['general'] = 'Activity Check failed. Try again';
                return false;
            }
            $stmt->store_result();
            // Store the result so we can check if the entry exists in the database.
            if ($stmt->num_rows == 0 ) 
            {
                // Activity NA
                $errors['activity'] = 'Selected activity not available, please try again!';
                return false;
            } 
            elseif ( $stmt->num_rows > 1 )
            {
                // db error ID is unique and can't exceed the count
                $errors['activity'] = 'DB error. Contact admin for support';
                return false;
            }
            else 
            {
                // Insert rows
                if ($stmt = $this->db->prepare('INSERT INTO booked_activities (activityID, customerID, date_of_activity, number_of_tickets)
                VALUES (?,?,?,?)')) {

                    $rc = $stmt->bind_param('iisi',$postdata['activity'],$_SESSION['id'],$postdata['date'],$postdata['numberoftickets']);
                    if (false === $rc)
                    {
                        $errors['general'] = 'Insert failed. Try again.';
                        return false;
                    }
                    $rc = $stmt->execute();
                    if ( false===$rc ) {
                        $errors['general'] = 'Insert failed. Try again';
                        return false;
                    }
                    if ($stmt->affected_rows > 0)  
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
        header("Location:book_activities.php");    

    }
    
    public function getBookedActivities(&$activities)
    {
        if ($stmt = $this->db->prepare('SELECT activityID,date_of_activity,number_of_tickets FROM booked_activities where customerID = ? order by date_of_activity asc')) 
        {
            // Bind parameters (s = string, i = int, b = blob, etc)
            $stmt->bind_param('i', $_SESSION['id']);
            $stmt->execute();
            //$stmt->store_result();
            $result = $stmt->get_result();
            //$stmt->store_result();
            // Store the result so we can check if the entry exists in the database.
            if ($result->num_rows > 0) 
            {
                $activities = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return true;
            } 
             else 
            {
                $errors['general'] = 'Error!';
                 return false;
            }    
        }
    }

    public function searchActivities($getdata, &$activities)
    {
        if($getdata['condition'] ==='AND' && !empty($getdata['search2']))
        {
            if ($stmt = $this->db->prepare('SELECT activity_name, description from activities where(description like ? and description like ?)') )
            {
                //bind param
                $search1 = "%{$getdata['search1']}%";
                $search2 = "%{$getdata['search2']}%";
                $stmt->bind_param('ss', $search1, $search2);
                $stmt->execute();
                
                $result = $stmt->get_result();

                //Store and check if entry exists
                if($result->num_rows > 0)
                {
                    $activities = $result->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();
                    return true;
                }
                else
                {
                    $errors['general'] = 'Error!';
                    return false;
                }
            }
        }
        elseif($getdata['condition'] ==='OR' && !empty($getdata['search2']))
        {
            if ($stmt = $this->db->prepare('SELECT activity_name, description from activities where(description like ? or description like ?)'))
            {
                //bind param
                $search1 = "%{$getdata['search1']}%";
                $search2 = "%{$getdata['search2']}%";
                $stmt->bind_param('ss', $search1, $search2);
                $stmt->execute();
                
                $result = $stmt->get_result();

                //Store and check if entry exists
                if($result->num_rows > 0)
                {
                    $activities = $result->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();
                    return true;
                }
                else
                {
                    $errors['general'] = 'Error!';
                    return false;
                }

            }
        }
        else
        {
            if ($stmt = $this->db->prepare('SELECT activity_name, description from activities where(description like ?)'))
            {
                //bind param
                $search1 = "%{$getdata['search1']}%";
                $stmt->bind_param('s', $search1);
                $stmt->execute();
                
                $result = $stmt->get_result();

                //Store and check if entry exists
                if($result->num_rows > 0)
                {
                    $activities = $result->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();
                    return true;
                }
                else
                {
                    $errors['general'] = 'Error!';
                    return false;
                }

            }
        }
    }

}