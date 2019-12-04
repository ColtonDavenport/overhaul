<DOCTYPE! html>

<html>

<head>
<title>Mississippi</title>
<link href="includes/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="includes/scripts.js"></script>
</head>

<body>

	<?php include("mysqli_connect.php");
	include("header.php");?>
	
	<div id="center" style="position:relative; left:15%;">
		<?php
		session_start();
		include('mysqli_connect.php');
		include('Cart_Functions.php');
		$total = 0;
		if(isset($_SESSION['userID'])) {
			// call set_cartID to make certain you have a valid $_SESSION['cartID']
			include('set_cartID.php');
			if(isset($_SESSION['guestCart'])) {
				include('Merge_Cart.php');
				merge_guest_cart($dbc);
			}
		}
		// Update Quantity of an item
		if (isset($_GET['quantity'])) {
			$bookID = $_GET["bookID"];
			$quantity = $_GET["quantity"];
			if (isset($_SESSION['userID'])) { // If signed in update database
				$userID = $_SESSION['userID'];
				$query = "update book_orders set quantity = ".$quantity." where orderID = 
					(select orderID from orders where userID = $userID and orderDate is null) and bookID = ".$bookID;
				
				$rows = mysqli_query($dbc, $query);
				if (!mysqli_query($dbc, $query)) {
					echo "ERROR: ".mysqli_error($dbc);
					echo "<br>Query is: ".$query;
				}
			} else { // If not signed in update session
				$_SESSION['guestCart'][$bookID] = $quantity;
			}
		} // End update

		// Remove items from database
		if (isset($_GET['remove'])) {
			if (isset($_SESSION['userID'])) { // If logged in edit database
				$query = "delete from book_orders where orderID =
					(select orderID from orders where userID = $userID and orderDate is null)";
				
				if (($_GET['remove']) != "all") { // If specific book edit query
					$bookID = $_GET["remove"];
					$query = substr($query, 0, -1);
					$query = $query." and bookID = $bookID)";
				}
				$rows = mysqli_query($dbc, $query);
				if (!mysqli_query($dbc, $query)) {
					echo "ERROR: ".mysqli_error($dbc);
					echo "<br>Query is: ".$query;
				}
			} else { // If not signed in remove sessions
				if (($_GET['remove']) == "all") { // If non-specific book remove all
					unset($_SESSION['guestCart']);
				} else { // Remove specific session
					$bookID = $_GET["remove"];
					unset($_SESSION['guestCart'][$bookID]);
				}
			}
		} // End Removal

		// Require sign in
		if(isset($_SESSION['userID'])) {
			
			if(!isset($_SESSION['cartID'])){
				echo "cartID is not set";
				include('setCartID.php');
			}
			if(isset($_SESSION['guestCart'])){
				include('Merge_Cart.php');
				merge_guest_cart($dbc);
			}

			echo "Shopping Cart<br>";
			// Initialize query
			$userID = $_SESSION['userID'];
			$query = "select b.image, b.title, bo.price, bookID, bo.quantity from orders o
			inner join users u on (orderID = cartID) inner join book_orders bo using (orderID)
			inner join books b using (bookID) where u.userID = $userID";

			// Display products using query
			$rows = mysqli_query($dbc, $query);
			if (!mysqli_query($dbc, $query)) {
				echo "ERROR: ".mysql_error($dbc);
			}

			while ($row=mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
				$total = $total + print_book_in_cart($dbc,$row);
			}
		} else {
			if(isset($_SESSION['guestCart'])){
				echo "<h3>Guest Cart</h3>";
				foreach($_SESSION['guestCart'] as $guestBookID => $guestQuantitiy){
					$query_guest = "SELECT bookID, title, image, price FROM books WHERE bookID = $guestBookID";
					if($r = mysqli_query($dbc, $query_guest)){
						if($row = mysqli_fetch_array($r)) {
							$row['quantity'] = $guestQuantitiy;
							$total = $total + print_book_in_cart($dbc, $row);
						} else {
							echo "<brError: No book found with id $guestBookID";
						}
					} else {
						echo "<br>Error: " . $query_guest . "<br>" . $dbc->error;
					}
				}
			} else {
				echo "No Cart to Display";
			}
		}

		?>
<br><br><br><br><br><br><br><input type="Button" id="removeAll" value="Remove All" onclick="confirmRemove('all')"><br>
<?php
echo "<h3>Total: $".number_format($total, 2, '.', ',');
    if(isset($_SESSION['userID'])){
?>
<form action="charge.php" method="post">
  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key=pk_test_HKSbrOiakC6tdohgKA32rfCe000XgdyOoR
          data-description="<?php echo 'Payment Form'; ?>"
          data-amount="<?php echo $total*100; ?>"
          data-locale="auto"></script>
	  <input type="hidden" name="totalamt" value="<?php echo $total*100; ?>" />
</form>
<?php
    }else{
        echo "<br>";
        echo "Please Sign In to Check-Out";
    }
?>
	</div>
	
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
