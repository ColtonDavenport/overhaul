<?php
/** 
	ERROR CHECKING
*
error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
	END ERROR CHECKING
*/
?>


<html>
<head>
	<title>View Products</title>
	<link type='text/css' rel='stylesheet' href='View_Products_style.css'/>
</head>
<body>
<div id="container">
<h3>MISSISSIPPI</h3>
<?php
session_start();
include('mysqli_connect.php');
echo "<br>To View Cart. Click <a href='User_Cart.php'>HERE</a>.";
echo "<br>To Order History. Click <a href='order_history.php'>HERE</a>.";
if(isset($_SESSION['userID'])) {
	echo "<br>To Logout. Click <a href='logout.php'>HERE</a>.";
	// call set_cartID to make certain you have a valid $_SESSION['cartID']
	include('set_cartID.php');
	if(isset($_SESSION['guestCart'])) {
		include('Merge_Cart.php');
		merge_guest_cart($dbc);
	}
} else {
	echo "<br> to log in Click Here <a href='login.php'>HERE</a>.";
}

if(isset($_GET['newBookID'])) {
	include('View_Functions.php');
}


$query = "select distinct catName from categories";
$rows = mysqli_query($dbc, $query);
echo "<form action='View_Products.php' method='POST'>";
while ($row=mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
	$catName = $row['catName'];
	echo "<input type='checkbox' name='category[]' value='".$catName."' id='".$catName."'></input>";
	echo "<label for='".$catName."'>".$catName."</label><br>";
}
echo "<input type='submit' value='Search'></form><br>";

// Initialize base query
$query = "select distinct image, title, price, bookID from books";
	
// If category selected edit query
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['category'])) {
	$query = $query." inner join book_categories using (bookID)
		inner join categories using (catID) where ";
	foreach($_POST['category'] as $selected){
		$query = $query."catName = '".$selected."' or ";
	}
	$query = substr($query, 0, -4);
}

// Display products using query
$rows = mysqli_query($dbc, $query);
if (!mysqli_query($dbc, $query)) {
	echo "ERROR: ".mysql_error($dbc);
}

while ($row=mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
	echo "<div class='bookContainer'>";
	if (isset($row['image'])) {
		echo "<img src='images/".$row['image']."' width='200px' alt='Image not found'>";
	}else {
		echo "<img src='images/mississippi_logo.png' width='200px' alt='No image, alt not found'>";
	}
	echo "<p class='bookTitle'>".$row['title']."</p>";
	
	// Separate query for authors
	$authorQuery = "select authorFirstName, authorLastName from books left join book_authors using (bookID)
	left join authors using (authorID) where bookID = ".$row['bookID'];
	$authors = mysqli_query($dbc, $authorQuery);
	if (!mysqli_query($dbc, $authorQuery)) {
		echo "ERROR: ".mysql_error($dbc);
	}
	$rowCount = 0;
	while ($author=mysqli_fetch_array($authors, MYSQLI_ASSOC)) {
		echo "<p class='bookAuthor'>".$author['authorFirstName']." ".$author['authorLastName']."</p>";
		if (isset($author['authorFirstName'])) {
			$rowCount++;
		}
	}
	if ($rowCount == 0) {
		$publisherQuery = "select pubName from books inner join publishers using (pubID)
		where bookID = ".$row['bookID'];
		$publishers = mysqli_query($dbc, $publisherQuery);
		if (!mysqli_query($dbc, $publisherQuery)) {
			echo "ERROR: ".mysql_error($dbc);
		}
		while ($publisher=mysqli_fetch_array($publishers, MYSQLI_ASSOC)) {
			echo "<p class='bookAuthor'>".$publisher['pubName']."</p>";
		}
	}
	echo '<script>$( "#spinner" ).spinner();</script>';
	echo "<p class='bookPrice'>$".$row['price']."</p>";
	echo "<button id='{$row['bookID']}' onclick=\"location.href='View_Products.php?newBookID={$row['bookID']}'\"  >ADD TO CART</button></div>";
}
//check if terms are accepted

if(isset($_SESSION['userID'])){
	echo "<br>";
	echo "You have accepted the changes to the privacy policy. To view these changes or to update youyr stance on them click 
	<a href='terms.php'>HERE</a>";
} 

?>
</div>
</body>
</html>