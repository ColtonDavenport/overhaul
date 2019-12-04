<?php
function merge_guest_cart($dbc) {
	// get the cartID
	$cartID = $_SESSION['cartID'];
	// take every element in the cart and add them to the user's cart

	foreach($_SESSION['guestCart'] as $guestBookID => $guestQuantity){		
		// find the price of the new book
		
		$query_price = "SELECT price FROM books WHERE bookID = $guestBookID LIMIT 1";
		if($t = mysqli_query($dbc, $query_price)) {
			
			if($tow = mysqli_fetch_array($t)){
				// if a row is returned that book exists
				$guestPrice = $tow['price'];
						
				// for every book in guestCart either update or insert in cartID
				$query_guest_book = "SELECT * FROM book_orders WHERE orderID = $cartID and bookID = $guestBookID";						
				// look for this orderid bookid combo in book_orders
				if($r = mysqli_query($dbc, $query_guest_book)){							
					//echo "<br>".$query_guest_book;
					
					if($row = mysqli_fetch_array($r)) {
						
						// if a row is fetched then that bookID already exists in book_orders for the user's cart
						// must update the quantity in the order
						// this implementation replaces user cart quantity with guest cart quantity as that is the most recent
						$update_guest = "UPDATE book_orders SET quantity = $guestQuantity, price = $guestPrice WHERE orderID = $cartID AND bookID = $guestBookID";
						if($y = mysqli_query($dbc, $update_guest)){
						} else { // update failed
							echo "<br>Error: " . $update_guest . "<br>" . $dbc->error;
						}				
					} else {
						// no record exists, will INSERT a new record
						$insert_guest = "INSERT INTO book_orders (orderID, bookID, quantity,price) VALUES ($cartID, $guestBookID, $guestQuantity, $guestPrice)";
						if($z = mysqli_query($dbc, $insert_guest)){
						} else {
							echo "<br>Error: " . $insert_guest . "<br>" . $dbc->error;
						}
					}
				} else {
					echo "<br>Error: " . $query_guest_book . "<br>" . $dbc->error;
				}
			} else {  // no rows were returned in the price query
				echo "<br>Error: " . $guestBookID . " isn't a valid bookID";
			}
		} else { // else for initial price query
			echo "<br>Error: " . $query_price . "<br>" . $dbc->error;
		}
	} // end foreach thru $_SESSION['guestCart']
	// finally, unset guestcart
	unset($_SESSION['guestCart']);
};
?>