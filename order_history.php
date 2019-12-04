<DOCTYPE! html>

<html>

<head>
<title>Mississippi</title>
<link href="includes/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="includes/scripts.js"></script>
</head>

<body>

	<?php 
	session_start();
	include("mysqli_connect.php");
	include("header.php");?>
	
	<div id ="center" style="position:relative; left:20%;">
		<?php

		if(isset($_SESSION['userID'])) {
			// call set_cartID to make certain you have a valid $_SESSION['cartID']
			include('set_cartID.php');
			if(isset($_SESSION['guestCart'])) {
				include('Merge_Cart.php');
				merge_guest_cart($dbc);
			}
		}		
		$userID = $_SESSION['userID'];
		$cartID = $_SESSION['cartID'];
		$orderQuery = "Select o.orderID, o.orderdate, ot.totals from orders as o INNER JOIN order_totals as ot on o.orderID = ot.orderID where o.userID = '$userID' and o.orderID != '$cartID' order by o.orderdate desc";
		//$q = "Select o.orderID, o.orderdate, bo.bookID, bo.quantity, b.title, bo.price, b.image, ot.totals from orders as o inner join book_orders as bo on o.orderID = bo.orderID INNER JOIN books as b on b.bookID = bo.bookID INNER JOIN order_totals as ot on bo.orderID = ot.orderID WHERE o.userID = '$userID' and o.orderID != '$cartID' order by o.orderdate desc";

		//table outline
		
		//Find order id and total price info
		$r = mysqli_query ($dbc, $orderQuery);
		while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
			//title meta data
			echo '<table id = "table" border="1" width="100%"align="center" style="border-collapse: collapse;">
			<tr>
				<td align="left" width="12%"><b>Order ID</b></td>
				<td align="left" width="12%"><b>Order Date</b></td>
				<td align="left" width="12%"><b>Grand Total</b></td>

			</tr>';
			echo "<br>";
			$orderID = $row['orderID'];
			$bookOrderQuery = "Select bo.bookID, bo.quantity, b.title, b.image, b.price from book_orders as bo INNER JOIN books as b on b.bookID = bo.bookID where orderID ='$orderID'";
			
			$rowz = mysqli_query ($dbc, $bookOrderQuery);
			echo "<tr>";
			echo "<td align=\"left\">{$row['orderID']}</td>";
			echo "<td align=\"left\">{$row['orderdate']}</td>";
			echo "<td align=\"left\">\${$row['totals']}</td>";
			echo "</tr>";
			echo "<br>";
			//create book meta data
			echo '<table id = "table" border="1" width="100%" align="center" style="border-collapse: collapse;">
			<tr>
				<td align="left" width="12%"><b>Book Title</b></td>
				<td align="left" width="12%"><b>Book Image</b></td>
				<td align="left" width="12%"><b>Quantity Ordered</b></td>
				<td align="left" width="12%"><b>Total Item Price</b></td>

			</tr>';
			//find books within the orderID
			while($rows = mysqli_fetch_array ($rowz, MYSQLI_ASSOC)){
				$price = $rows['quantity'] * $rows['price'];
				$prettyPrice = number_format($price, 2, '.', ',');
				echo "<tr>";
				echo "<td align=\"left\">{$rows['title']}</td>";
				echo "<td align=\"left\"><img src='images/".$rows['image']."' width='100px' alt='Image not found'></td>";
				echo "<td align=\"left\">{$rows['quantity']}</td>";
				echo "<td align=\"left\">\$$prettyPrice</td>";
				echo "</tr>";
				

			}
			
			
			/*
			$price = $row['quantity'] * $row['price'];
			$prettyPrice = number_format($price, 2, '.', ',');
				// Display each record:
				echo "\t<tr>
					<td align=\"left\">{$row['orderID']}</td>
					<td align=\"left\">{$row['orderdate']}</td>
					<td align=\"left\">{$row['title']}</td>
					<td align=\"left\"><img src='images/".$row['image']."' width='100px' alt='Image not found'></td>
					<td align=\"left\">{$row['quantity']}</td>
					<td align=\"left\">{$row['price']}</td>
					<td align=\"left\">$prettyPrice</td>
					<td align=\"left\">{$row['totals']}</td>
				</tr>\n";*/
		//echo "</tr>\n";
		}
		
		echo"</table>";



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
