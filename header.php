<?php
session_start();
include('mysqli_connect.php');
$username=$_SESSION['username'];

$query = "select isAdmin, userID from users where username = '$username'";

$r = @mysqli_query ($dbc, $query);
$row = mysqli_fetch_array($r);
$admin = $row['isAdmin'];
$userID = $row['userID'];

$queryOrder = "select orderID from orders where userID = '$userID' and orderdate != 'null'";

$rows = @mysqli_query ($dbc, $queryOrder);
$rowz = mysqli_num_rows($rows);

if(isset($_SESSION['username']) && $admin == true &&$rowz < 1){
	echo '<div class="topnav" style="width:600px">';
}else if (isset($_SESSION['username']) && $admin == true) {
	echo '<div class="topnav" style="width:750px">';
} else if (isset($_SESSION['username']) && $rowz < 1) {
	echo '<div class="topnav" style="width:450px">';
}else if (isset($_SESSION['username'])) {
	echo '<div class="topnav" style="width:600px">';
}else {
	echo '<div class="topnav" style="width:450px">';
}
?>
	<ul>
		<li><a href = "index.php">Mississippi</a>
		<li><a href = "User_Cart.php">Cart</a>
		<?php
		/*echo $_SESSION['username'];
		if (!isset($_SESSION['username'])) {
			echo "Not signed in";
		}*/
		if($rowz > 0){
			echo '<li><a href = "order_history.php">Order History</a>';
		}
		if (isset($_SESSION['username'])) {
			echo '<li><a href = "logout.php">Logout</a>';
		} else {
			echo '<li><a href = "login.php">Login</a>';
		}
		if (isset($_SESSION['username']) && $admin == true) {
			echo '<li><a href = "admin.php">Add Books</a>';
		}
		?>
	</ul>
</div>