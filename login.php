<?php
session_start();
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}
require_once "pdo.php";
// $salt = 'XyZzy12*_';
// $stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

$failure = false;  // If we have no POST data

if ( isset($_SESSION['failure']) ) {
    $failure = $_SESSION['failure'];

    unset($_SESSION['failure']);
}//IMPORTANT
// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) 
{
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) 
    {
        $_SESSION['failure'] = "Email and password are required";
        header("Location: login.php");
        return;
    } 
    else 
    {
        $pass = htmlentities($_POST['pass']);
        $email = htmlentities($_POST['email']);

        if ((strpos($email, '@') === false)) 
        {
            $_SESSION['failure'] = "Email must have an at-sign (@)";
            header("Location: login.php");
            return;
        }
        // else
        // {
        //     $check = hash('md5', $salt.$pass);//Encoded password.
        //     if ( $check == $stored_hash ) 
        //     {

        //         $_SESSION['paccount'] = $email;
        //         $_SESSION['status']='Successfully logged in.';
        //         $_SESSION['color']='green';
        //         header("Location: view.php");
        //         return;
        //     } 
        //     else 
        //     {
        //         $_SESSION['failure'] = "Incorrect password";

        //         header("Location: login.php");
        //         return;
        //     }
        // }
        $stmt = $pdo->prepare("
        SELECT * FROM user WHERE Email=:Email
        ");

        $stmt->execute([
            ':Email' => $email
        ]);
        $row = $stmt->fetch(PDO::FETCH_OBJ); 
        if(empty($row))
        {
            $_SESSION['failure'] = "User doesn't exist";
            header("Location: login.php");
            return; 
        }
        else if($row->Password==$pass)
        {
            $_SESSION['paccount'] = $email;
            $_SESSION['status'] = 'Successfully logged in.';
            $_SESSION['color'] = 'green';

            header('Location: view.php');
	        return;
        }
        else
        {
            $_SESSION['failure'] = "Incorrect password";
            header("Location: login.php");
            return;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "pdo.php"; ?>
<title>Login Page-Dipanshu Barnwal</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php

if ( $failure !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
?>
<form method="POST">
<label for="nam">Email</label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>

</div>
</body>
