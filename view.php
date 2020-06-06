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
if(isset($_POST['language']))
{
    $apply1=$_POST['language'];
    $songs=[];
    $all_songs = $pdo->query("SELECT songs.Song_id,songs.Name,songs.Release_Date,songs.Rating,
    artist.Name AS Artist,album.NAME AS Album, genre.Name AS Genre 
    FROM songs JOIN album JOIN genre JOIN artist JOIN language
    ON songs.Album_id=album.ALBUM_id AND songs.genre_id=genre.Genre_id 
    AND songs.Artist_id=artist.Artist_id AND language.Language_id=songs.Lang_id
    WHERE songs.Lang_id=$apply1");
}
else if(isset($_POST['genre']))
{
    $apply3=$_POST['genre'];
    $songs=[];
    $all_songs = $pdo->query("SELECT songs.Song_id,songs.Name,songs.Release_Date,songs.Rating,
    artist.Name AS Artist,album.NAME AS Album, genre.Name AS Genre 
    FROM songs JOIN album JOIN genre JOIN artist
    ON songs.Album_id=album.ALBUM_id AND songs.Genre_id=genre.Genre_id 
    AND songs.Artist_id=artist.Artist_id
    WHERE songs.Genre_id=$apply3");
}
else if(isset($_POST['mood']))
{
    $apply2=$_POST['mood'];
    $songs=[];
    $all_songs = $pdo->query("SELECT songs.Song_id,songs.Name,songs.Release_Date,songs.Rating,
    artist.Name AS Artist,album.NAME AS Album, genre.Name AS Genre 
    FROM songs JOIN album JOIN genre JOIN artist JOIN mood
    ON songs.Album_id=album.ALBUM_id AND songs.genre_id=genre.Genre_id 
    AND songs.Artist_id=artist.Artist_id
    WHERE songs.Mood_id=$apply2");
}
else
{
    $songs=[];
    $all_songs = $pdo->query("SELECT songs.Song_id,songs.Name,songs.Release_Date,songs.Rating,
    artist.Name AS Artist,album.NAME AS Album, genre.Name AS Genre 
    FROM songs JOIN album JOIN genre JOIN artist 
    ON songs.Album_id=album.ALBUM_id AND songs.genre_id=genre.Genre_id AND songs.Artist_id=artist.Artist_id");
}
while ( $row2 = $all_songs->fetch(PDO::FETCH_OBJ) ) 
{
    $songs[] = $row2;
}

$lang=[];
$all_lang = $pdo->query("SELECT * FROM language");

while ( $row3 = $all_lang->fetch(PDO::FETCH_OBJ) ) 
{
    $lang[] = $row3;
}
$mood=[];
$all_mood = $pdo->query("SELECT * FROM mood");

while ( $row4 = $all_mood->fetch(PDO::FETCH_OBJ) ) 
{
    $mood[] = $row4;
}
$genre=[];
$all_genre = $pdo->query("SELECT * FROM genre");

while ( $row5 = $all_genre->fetch(PDO::FETCH_OBJ) ) 
{
    $genre[] = $row5;
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

<a href="fav.php">Favourite Songs</a> |
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
        
            <!-- action="song.html"> -->
        <div class="box">
        <div class="lan extra">
            <h4>Language</h4>
            <hr>
            <form method="POST"> 
            <?php foreach($lang as $langa) : ?>
            <input type="radio" name="language" value=<?php echo $langa->Language_id;?>> <?php echo $langa->Name;?> 
            <?php endforeach; ?>
            <input type="submit" name="button" value="Apply"/></form>
        </div>
        <div class="mood extra">
            <h4>Mood</h4>
            <hr>
            <form method="POST"> 
            <?php foreach($mood as $mooda) : ?>
            <input type="radio" name="mood" value=<?php echo $mooda->Mood_id;?>> <?php echo $mooda->Name;?> 
            <?php endforeach; ?>
            <input type="submit" name="button" value="Apply"/></form>
        </div>
        <div class="genre extra">
            <h4>Genre</h4>
            <hr>
            <form method="POST">
            <?php foreach($genre as $genrea) : ?>
            <input type="radio" name="genre" value=<?php echo $genrea->Genre_id;?>> <?php echo $genrea->Name;?> 
            <?php endforeach; ?>
            <input type="submit" name="button" value="Apply"/></form>
        </div>
        </div>
    </form>

<h2>Songs</h2>
<!-- Inserting tables -->
<?php if(empty($songs)): 
    echo "None"; 

 else: ?>
<table style="width:70%">
    <tr>
    <th><?php echo "Name"; ?> </th>
    <th><?php echo "Artist"; ?> </th>
    <th><?php echo "Album"; ?> </th>
    <th><?php echo "Rating"; ?> </th>
    <th><?php echo "Release_Date"; ?> </th>
    <th><?php echo "Genre";?> </th>
    </tr>
<?php foreach($songs as $song) : ?>
    <tr>
    <td><?php echo $song->Name; ?> </td>
    <td><?php echo $song->Artist; ?> </td>
    <td><?php echo $song->Album; ?> </td>
    <td><?php echo $song->Rating; ?> </td>
    <td><?php echo $song->Release_Date; ?> </td>
    <td><?php echo $song->Genre;?> </td>
    <td><a href="fav.php?song_id=<?php echo $song->Song_id; ?>">Add to Fav.</a>
    </tr>
<?php endforeach; ?>
</table>
<?php endif;?>

</div>

</body>
</html>

<!-- User already exists. -->
