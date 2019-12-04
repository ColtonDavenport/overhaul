<?php
session_start();


/***
 ***   add_book_to_cart
 ***/
function add_book_to_cart($dbc, $price) {
	$bookID = $_GET['newBookID'];
	$cartID = $_SESSION['cartID'];
	$query_book_orders = "SELECT quantity FROM book_orders WHERE orderID = $cartID AND bookID = $bookID";
	if( $x = mysqli_query($dbc, $query_book_orders)) {
		if($xow = mysqli_fetch_array($x)){
			// there already existed a record in book_orders for this bookID & orderID combo
			// increment the quantity
			// set the price again incase it's changed
			$newQuantity = $xow['quantity'] + 1;
			$update_book_orders = "UPDATE book_orders SET quantity = $newQuantity, price = $price WHERE orderID = $cartID AND bookID = $bookID";
			if(!mysqli_query($dbc, $update_book_orders)) {
				echo "<br>Error: " . $update_book_orders . "<br>" . $dbc->error;
			}
		} else {
			// no record existed in the order for this book.
			// create that record
			// start its quantity at 1
			// price is found when determing $isARealBook
			$insert_book_orders = "INSERT INTO book_orders (orderID, bookID, quantity, price) VALUES ($cartID, $bookID, 1, $price)";
			if(mysqli_query($dbc, $insert_book_orders)) {
			} else {
				echo "<br>Error: " . $insert_book_orders . "<br>" . $dbc->error;
			}
		}
	} else {
		echo "<br>Error: " . $query_book_orders . "<br>" . $dbc->error;
	}
};





$bookID = $_GET['newBookID'];
$query_check_book = "SELECT bookID, price FROM books WHERE bookID = $bookID LIMIT 1";
if($r = mysqli_query($dbc, $query_check_book)){
	if($row = mysqli_fetch_array($r)){
		$price = $row['price'];
		$isARealBook = true;
	} else {
		$isARealBook = false;
	}
} else {
	$isARealBook = false;
	echo "<br>Error: " . $query_check_book . "<br>" . $dbc->error;
}

	

if($isARealBook) {
	// Check if Signed in
	if(isset($_SESSION['userID'])) {
		// user is logged in

		// check if there is a guestCart
		if(isset($_SESSION['guestCart'])){
			include('Merge_Cart.php');
			merge_guest_cart($dbc);
		}
		add_book_to_cart($dbc, $price);
		// user is logged in. check if they have a cart id;
			// time to add book to cart
			
		// First check if the book already exists within the cart
		
	} else {
		$bookID = $_GET['newBookID']; // get the book id
		$_SESSION['guestCart'][$bookID] += 1; //increment a position in the array -- creates that array if it doesn't already exist
	} 
}

?>