<?php
/** 
	ERROR CHECKING
*/
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
/**
	END ERROR CHECKING
*/
session_start();
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Mississippi</title>
    <link type='text/css' rel='stylesheet' href='includes/style.css'/>
  </head>
  <body>
	<?php include('header.php');?>
    <div id="container">
    <h3 style="text-align:center">ADD PRODUCTS</h3>
<?php

$query = "select isAdmin from users where username = '$username'";

$r = @mysqli_query ($dbc, $query);
$row = mysqli_fetch_array($r);
$admin = $row['isAdmin'];

if(isset($_SESSION['username']) && $admin) {

include('mysqli_connect.php');

include('addBookFunctions.php');
// currently publishers is the only mandatory field that can't be checked in html	
$formComplete = isset($_POST['publishers']);

sendToPost($dbc);

echo "<br>";

?>
<form action="admin.php" method="POST" enctype="multipart/form-data">

<div class="inputContainer">
<h3>Book details</h3>

<label>Title: <input type="text" placeholder="Title" name="title" maxlength="50" value="<?php sticky('title')?>" required></label><br>

<label>Price: <input type="number" placeholder="Price" name="price" max="999.99" min="0" step="any" value="<?php sticky('price')?>" required></label><br>

<label>Short Description: <textarea rows="4" cols="50" placeholder="A short blurb to summarize the book" name="shortDesc" maxlength="200" required><?php sticky('shortDesc')?></textarea></label><br>

<label>Long Description: <textarea rows="4" cols="50" placeholder="A long description to give greater detail about the book" name="longDesc" maxlength="1500" required><?php sticky('longDesc')?></textarea></label><br>

<label>Edition: <input type="text" placeholder="1st paperback, eg" name="edition" maxlength="20" value="<?php sticky('edition')?>"></label><br>

<label>Publication Date: <input type="date" name = "releaseDate" value="<?php sticky('releaseDate')?>" required></label><br>
</div>




<div class="inputContainer">
<h3> Publishers </h3>
<?php
displaySelection($dbc,"publishers",array("pubId", "pubName"),"radio");
?>
<div id="newPublisher"></div>
<button id="addPublisher" type="button" >Add New Publisher</button>
<button id="clearPublisher" type="button" >Clear New Publisher</button>
</div>

<br>
<div class="inputContainer">
<h3> Categories </h3>
<?php
displaySelection($dbc,"categories",array("catId", "catName"),"checkbox");
?>
<div id="newCategories"></div>
<button id="addCategory" type="button" >Add New Category</button>
<button id="clearCategories" type="button">Clear New Categories</button>
</div>

<div class="inputContainer">
<h3> Authors </h3>
<?php
searchAuthors($dbc);
?>
<table id="newAuthors">
</table>
<button id="addAuthor" type="button" >Add an Author</button>
<button id="clearAuthors" type="button">Clear New Authors</button>
</div>

<br>

<input type="file"name="user_file" required />

<br>

<input type="submit" value="Add Book">
</form>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type='text/javascript' src ="includes/addBook.js"></script>
  </div>
<?php
} else { // end major admin check if
	echo "Must Log In as Admin to add products <a href='login.php'>HERE</a>";
}
?>
<footer>
Mississippi©
<?php
    if(isset($_SESSION['userID'])){
        echo "<br>";
        echo "You have accepted the changes to the privacy policy. To view these changes or to update your stance on them click
        <a href='terms.php'>HERE</a>";
    }
    ?>
</footer>

  </body>
</html>
