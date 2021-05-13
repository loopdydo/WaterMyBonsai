<?php

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect them to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$name = $freq = "";
$name_err = $freq_err = "";


 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if tree name is empty
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a name for your bonsai.";
    } else{
        $name = trim($_POST["name"]);
    }
    
    // Check if freq is empty
    if(empty(trim($_POST["freq"]))){
        $freq_err = "Please enter how often your bonsai needas watered.";
    } else{
        $freq = trim($_POST["freq"]);
    }
    
//check input errors
    if(empty($name_err) && empty($freq_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO trees (username, name, water_freq) VALUES (:username, :name, :freq)";
         
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
            $stmt->bindParam(":freq", $param_freq, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = $_SESSION["username"];
            $param_name = $name;
            $param_freq = $freq;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to index page
                header("location: index.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }
    
    // Close connection
    unset($pdo);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Bonsai</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Add a Bonsai</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Name your bonsai</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                <span class="help-block"><?php echo $name_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($freq_err)) ? 'has-error' : ''; ?>">
                <label>Water Frequency (in days)</label>
                <input type="int" name="freq" class="form-control">
                <span class="help-block"><?php echo $freq_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Add">
            </div>
        </form>
    </div>
</body>
</html>