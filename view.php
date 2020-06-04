<?php
session_start(); 
if ( ! isset($_SESSION['paccount']) && ! isset($_SESSION['naccount'])) {
  die('Not logged in');
}
$status = false;
require_once "pdo.php";
if ( isset($_SESSION['status']) ) {
	$status = $_SESSION['status'];
	$status_color = $_SESSION['color'];

	unset($_SESSION['status']);
	unset($_SESSION['color']);
}

// try 
// {
//     $pdo = new PDO('mysql:host=localhost;port=3306;dbname=songify', 'dipb', 'mun');
//     // set the PDO error mode to exception
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// }
// catch(PDOException $ex)
// {
//     echo "Connection failed: " . $ex->getMessage();
//     die();
// }

// $autos=[];
// $all_autos = $pdo->query("SELECT * FROM autos");
// while ( $row = $all_autos->fetch(PDO::FETCH_OBJ) ) 
// {
//     $autos[] = $row;
// }
if(isset($_SESSION['paccount']))
{
    $email=$_SESSION['paccount'];
    // $name=$pdo->prepare(" SELECT UserName FROM user WHERE Email=$email ");
    $stmt = $pdo->prepare("
	    SELECT * FROM user
	    WHERE Email = :Email
	");
	$stmt->execute([
	    ':Email' => $email
	]);
	$row = $stmt->fetch(PDO::FETCH_OBJ);
}
else
{
    $email=$_SESSION['naccount'];
    $stmt = $pdo->prepare("
	    SELECT * FROM user
	    WHERE Email = :Email
	");

	$stmt->execute([
	    ':Email' => $email
	]);

	$row = $stmt->fetch(PDO::FETCH_OBJ);
}
?>




<!DOCTYPE html>
<html>
<head>
<title>Home Page-Dipanshu Barnwal</title>
<link rel="stylesheet" href="style1.css">
    <meta charset="utf-8">
</head>
<body>
<h1>Songify:Discover Your Music</h1>
<p>
<?php 
if ( $status !== false ) 
{
    echo('<p style="color: '.$status_color.';"
    class="col-sm-10 col-sm-offset-2">'.
    htmlentities($status).
    "</p>\n"
    );
}
?>
</p>
<h1>Welcome <?php echo htmlentities($row->UserName); ?></h1>

<a href="add.php">Add Songs</a> |
<a href="contact.php">Contact</a> |
<a href="logout.php">Logout</a>
        <hr>
        <div id="sea">
            <input type="text" name="search" placeholder="song name">
            <button>search</button>
        </div>
        <div id="fav">
        <button>Favourite</button>
        </div>
        <form method="POST"> 
            <!-- action="song.html"> -->
        <div class="box">
        <div class="lan extra">
            <h4>Language</h4>
            <hr>
            <input type="radio" name="language" value="English">English<br>
            <input type="radio" name="language" value="Hindi">Hindi<br>
        </div>
        <div class="mood extra">
            <h4>Mood</h4>
            <hr>
            <input type="radio" name="mood" value="mood1">Mood1<br>
            <input type="radio" name="mood" value="mood2">Mood2<br>
        </div>
        <div class="album extra">
            <h4>Album</h4>
            <hr>
            <input type="radio" name="alb" value="album1">Album1<br>
            <input type="radio" name="alb" value="album2">Album2<br>
        </div>
        <div class="genre extra">
            <h4>Genre</h4>
            <hr>
            <input type="radio" name="gen" value="genre1">Genre1<br>
            <input type="radio" name="gen" value="genre2">Genre2<br>
        </div>
        </div>
    </form>

<h2>Songs</h2>
<!-- Inserting tables -->

<?php if(empty($autos)): 
    echo "None"; 

 else: ?>
<ul>
<?php foreach($autos as $auto) : ?>
    <li>
    <?php echo $auto->make; ?> <?php echo $auto->year; ?> <?php echo $auto->mileage; ?> 
    </li>
<?php endforeach; ?>
</ul>
<?php endif;?>

</div>

</body>
</html>

<!-- User already exists. -->