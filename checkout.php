<?php
	$userID = $_SESSION['userID'];
	$query="select cartID from users where userID = $userID limit 1";
	$cartIDs = mysqli_query($dbc, $query);
	if (!mysqli_query($dbc, $query)) {
		echo "ERROR: ".mysqli_error($dbc);
		echo "<br>Query is: ".$query."<br>";
	}
	if ($cartIDt=mysqli_fetch_array($cartIDs, MYSQLI_ASSOC)) {
		$cartID = $cartIDt['cartID'];
	}
	
	// Get data for user
	$query="select fName, lName, address, postal from users where userID = $userID limit 1";
	$users = mysqli_query($dbc, $query);
	if (!mysqli_query($dbc, $query)) {
		echo "ERROR: ".mysqli_error($dbc);
		echo "<br>Query is: ".$query."<br>";
	}
	if ($user=mysqli_fetch_array($users, MYSQLI_ASSOC)) {
		$fName = $user['fName'];
		$lName = $user['lName'];
		$address = $user['address'];
		$postal = $user['postal'];
	}
	$date = date("Y/M/d");
	$filename = "orders/order$cartID.txt";
	$content = "$fName, $lName\n$date\n$address\n$postal";
	
	// Get data for books
	$subTotal = 0;
	$query="select b.title, o.quantity, o.price from users u inner join book_orders o on (cartID = orderID)
	inner join books b using (bookID)
	where userID = $userID";
	$books = mysqli_query($dbc, $query);
	if (!mysqli_query($dbc, $query)) {
		echo "ERROR: ".mysqli_error($dbc);
		echo "<br>Query is: ".$query."<br>";
	}
	while ($book=mysqli_fetch_array($books, MYSQLI_ASSOC)) {
		$title = $book['title'];
		$quantity = $book['quantity'];
		$price = number_format($book['price'], 2, '.', ',');
		$total = number_format(($quantity * $price), 2, '.', ',');
		$subTotal += $total;
		$content = $content."\n$title, Quantity: $quantity, Price: $$price, Subtotal: $$total";
	}
	// Write data to file
	$content = $content."\nTotal: $".number_format($subTotal, 2, '.', ',');;
	$handle = file_put_contents($filename,$content, FILE_APPEND);
	
	// Set users cart to be past order
	$query = "update orders set orderDate = sysdate() where userID = $userID and orderDate is null";
	mysqli_query($dbc, $query);
	if (!mysqli_query($dbc, $query)) {
		echo "ERROR: ".mysqli_error($dbc);
		echo "<br>Query is: ".$query."<br>";
	}
	
	// Give user a new cart
	$create_cart = "INSERT INTO orders (userID) VALUES ($userID)";
	if(mysqli_query($dbc, $create_cart)) {
		// Insert successful created a new cart
		$cartID = $dbc->insert_id;
		// Now update the user's cartID
		$update_cartID = "UPDATE users SET cartID = $cartID WHERE userID = $userID";
		if(mysqli_query($dbc, $update_cartID)) {
		} else {
				echo "<br>Error: " . $update_cartID . "<br>" . $dbc->error;
		}
	} else {
		echo "<br>Error: " . $insert_order . "<br>" . $dbc->error;
	}
	
	$data = file($filename);
	foreach ($data as $d) {
		echo $d."<br>";
	}

?>