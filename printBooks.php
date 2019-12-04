<?php
session_start();
include('mysqli_connect.php');
// Initialize base query
$query = "SELECT distinct title, bookID, price, image, shortDesc, longDesc, edition, releaseDate, pubID FROM books";
	
// If category selected edit query
/*
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['category'])) {
	$query = $query." inner join book_categories using (bookID)
		inner join categories using (catID) where ";
	foreach($_POST['category'] as $selected){
		$query = $query."catName = '".$selected."' or ";
	}
	$query = substr($query, 0, -4);
}
/**/
if(isset($_GET['newBookID'])) {
	include('View_Functions.php');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['category'])) {
	$query = $query." inner join book_categories using (bookID)
		inner join categories using (catID) where ";
	foreach($_POST['category'] as $selected){
		$query = $query."catName = '".$selected."' or ";
	}
	$query = substr($query, 0, -4);
}

// Display products using query
$r = mysqli_query($dbc, $query);
if (!mysqli_query($dbc, $query)) {
	echo "ERROR: ".mysqli_error($dbc);
}

while ($row=mysqli_fetch_array($r, MYSQLI_ASSOC)) { 
// get the authors
$authorQuery = "SELECT CONCAT(authorFirstName,' ', authorLastName) AS name FROM authors INNER JOIN book_authors USING (authorID) INNER JOIN books USING (bookID) WHERE bookID = {$row['bookID']}";
if ($a = mysqli_query($dbc, $authorQuery)) {
	if($aow = mysqli_fetch_array($a)) {
		$authorNames = $aow['name'];
		while($aow = mysqli_fetch_array($a)){
			$authorNames .= ", {$aow['name']}";
		}
	}
} else {
	echo "ERROR: ".mysqli_error($dbc);
}

// get the publisher
$publisherQuery = "SELECT pubName FROM publishers WHERE pubID = {$row['pubID']}";
$pubName = "N/A"; 
if ($p = mysqli_query($dbc, $publisherQuery)) {
	if($pow = mysqli_fetch_array($p)) {
		$pubName = $pow['pubName'];
	}	else {
		echo "ERROR: No publsiher Found";
	}
} else {
	echo "ERROR: ".mysqli_error($dbc);
}

$categoryQuery = "SELECT catName FROM categories INNER JOIN book_categories USING (catID) INNER JOIN books USING (bookID) WHERE bookID = {$row['bookID']} LIMIT 3";
if ($c = mysqli_query($dbc, $categoryQuery)) {
	if($cow = mysqli_fetch_array($c)) {
		$categories = $cow['catName'];
		while($cow = mysqli_fetch_array($c)){
			$categories .= ", {$cow['catName']}";
		}
	}
} else {
	echo "ERROR: ".mysqli_error($dbc);
}

?>
<div class="book" id="<?php echo "{$row["bookID"]}";?>">
				<table>
					<tr>
						<td class="book-cover">
							<img src = "images/<?php echo "{$row["image"]}"?>">
						</td>
						<td class="book-info">
							<h4><?php echo "{$row["title"]}"?></h4>
							<?php
								if(isset($authorNames)) {
									echo "<p><span class='book-expand'> - </span>$authorNames</p>";
								}
							?>
							<br class="book-expand">
							<p class="book-publisher book-expand"> Published: <?php echo "$pubName";?></p>
							<br class="book-expand">
							<p> <?php echo "{$row["edition"]}"?> </p>
							<?php
								if(isset($categories)) {
									echo "<p><span class='book-expand'> - </span> $categories</p>";
								}
							?>
							<br class="book-expand">
							<h5>"<?php echo "{$row["shortDesc"]}"?>"</h5>
							<p class="book-expand"><?php echo "{$row["longDesc"]}"?></p>
							<br class="book-expand">
							<h5 class="book-price">$<?php echo "{$row["price"]}"?></h5>
							
							<div>
								<br class="book-expand"><br>
								<?php echo "<button type='button' id='{$row['bookID']}' style='z-index: 2;' onclick=\"location.href='index.php?newBookID={$row['bookID']}'\"  >ADD TO CART</button>";?>
								
							</div>
						</td>
							
					</tr>
				</table>
			</div>
<?php

} // end while loop
	
/*	
	
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
//og button//<button type="button" onclick="expandBook('b-1')">Add to cart</button> 
	echo "<button id='{$row['bookID']}' onclick=\"location.href='View_Products.php?newBookID={$row['bookID']}'\"  >ADD TO CART</button></div>";
}*/
?>

