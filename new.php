<?php
session_start();

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}
require_once "pdo.php";
$failure = false;
if ( isset($_SESSION['failure']) ) {
    $failure = $_SESSION['failure'];

    unset($_SESSION['failure']);
}//IMPORTANT

if ( isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['user']) ) 
{
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 || strlen($_POST['user']) < 1 ) 
    {
        $_SESSION['failure'] = "All fields are required";
        header("Location: new.php");
        return;
    } 
    else 
    {
        $pass = htmlentities($_POST['pass']);
        $email = htmlentities($_POST['email']);
        $user=htmlentities($_POST['user']);

        if ((strpos($email, '@') === false)) 
        {
            $_SESSION['failure'] = "Email must have an at-sign (@)";
            header("Location: new.php");
            return;
        }
    $_SESSION['naccount'] = $email;

    $stmt = $pdo->prepare("
        INSERT INTO user (UserName, Password, Email) 
        VALUES (:UserName, :Password, :Email)
    ");

    $stmt->execute([
        ':UserName' => $user, 
        ':Password' => $pass, 
        ':Email' => $email
    ]);

    $_SESSION['status'] = 'The account has been created successfully.';
    $_SESSION['color'] = 'green';

    header('Location: view.php');
	return;

    }
}
?>
<!DOCTYPE html>
<html>
<head>

<title>Create an Account-Dipanshu Barnwal</title>
</head>
<body>
<div class="container">
<h1>Create a new Account</h1>
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
<label for="user">User Name</label>
<input type="text" name="user" id="user"><br/>
<input type="submit" value="Create">
<input type="submit" name="cancel" value="Cancel">
</form>

</div>
</body>
