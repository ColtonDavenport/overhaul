<?php
$userID = $_SESSION['userID'];
$query_cartID = "SELECT cartID FROM users WHERE userID = $userID LIMIT 1";
if($r = mysqli_query($dbc, $query_cartID)) {
	//echo "$r['cartID']";
	if($row = mysqli_fetch_array($r)) {
		// found a row -- check if cartId is null
		if($row['cartID'] == null) {
			// no cartID - must create a new order
			$insert_order = "INSERT INTO orders (userID) VALUES ($userID)";
			echo "INSERTING: ";
			if(mysqli_query($dbc, $insert_order)) {
				// insert Successful created  a new order
				echo "<h3> order succesfully inserted </h3>";
				$cartID = $dbc->insert_id;
				// now update the user's cartID
				$update_cartID = "UPDATE users SET cartID = $cartID WHERE userID = $userID";
				echo "Updating";
				if(mysqli_query($dbc, $update_cartID)) {
					echo "<h3> UPDATE: userID: $userID cartID: $cartID </h3>";
				} else {
						echo "<br>Error: " . $update_cartID . "<br>" . $dbc->error;
				}
			} else {
				echo "<br>Error: " . $insert_order . "<br>" . $dbc->error;
			}
		}else {
			$cartID = $row['cartID'];
		}
	}
		// now have valid $cartID
		$_SESSION['cartID'] = $cartID;
} else {
	
	echo "<br>Error: " . $query_cartID . "<br>" . $dbc->error;
}
?>