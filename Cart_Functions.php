<?php
function print_book_in_cart($dbc,$row) {
	echo "<div class='book' style='padding-bottom: 60px; padding-top: 60px;'>";
	if (isset($row['image'])) {
		echo "<img src='images/".$row['image']."' width='200px' alt='Image not found'>";
	}else {
		echo "<img src='images/mississippi_logo.png' width='200px' alt='No image, alt not found'>";
	}
	echo "<p class='bookTitle'>".$row['title']."</p>";
	
	// Separate query for authors
	$authorQuery = "select authorFirstName, authorLastName from books left join book_authors
	using (bookID) left join authors using (authorID) where bookID = ".$row['bookID'];
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
	$total = $row['quantity'] * $row['price'];
	echo "<p class='bookPrice'>$".$row['price']."</p>";
	echo "<p>Quantity: <input type='number' id='quantity".$row['bookID']."'
		value='".$row['quantity']."' min='0' onfocusout='changeQuantity(".$row['bookID'].")'></p>";
	echo "<p class='subtotal'>Subtotal: $".number_format($total, 2, '.', ',')."</p>";
	echo "<button id='".$row['bookID']."' onclick='confirmRemove(".$row['bookID'].")'>Remove</button></div>";
	return $total;
}
?>
<script>
function confirmRemove(buttonID) {
	if (buttonID == 'all') {
		if (confirm("Are you sure you want to empty your cart?")) {
			window.location.href = "User_Cart.php?remove=" + buttonID;
		}
	} else {
		if (confirm("Are you sure you want remove this item?")) {
			window.location.href = "User_Cart.php?remove=" + buttonID;
		}
		else {
			location.reload();
		}
	}
}

function changeQuantity(bookID) {
	var quantity = document.getElementById("quantity" + bookID).value;
	if (quantity <= 0) { // If no quantity remove from cart
		confirmRemove(bookID);
	} else { // Adjust quantity
		window.location.href = "User_Cart.php?bookID=" + bookID + "&quantity=" + quantity;
	}
}

</script>