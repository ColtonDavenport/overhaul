<html>
<head>
<title>Login Page</title>
<link type='text/css' rel='stylesheet' href='includes/style.css'/>
<link type='text/css' rel='stylesheet' href='includes/login-style.css'/>
</head>

<body>
<?php
session_start();
include("phpAux/mysqli_connect.php");
include("header.php");

if (isset($_SESSION['username'])) { // if the SESSION is already set

	// redirect to main store page
	header("Location: http://deepblue.cs.camosun.bc.ca/~ics19908/Overhaul/index.php");
} elseif (isset($_POST['username'])) { // else if method someone is trying to login
		
		//validate login attempt
	    $username=$_POST['username'];
		$pass=$_POST['pass'];
		// create query
		$query = "SELECT username, pass, userID, isAdmin, termsaccept, cartID from users WHERE username='$username' AND pass = SHA1('$pass')";
		// run query
		$r = mysqli_query ($dbc, $query);
		
		// check that username and password matched a record
		if (mysqli_num_rows($r) == 1) { // login credentials were correct
			$row = mysqli_fetch_array($r);
			$userID = $row['userID'];//grab the user id
			$termsaccept = $row['termsaccept'];//returns a 1 or 0
			$isAdmin = $row['isAdmin'];
			if($row['cartID'] == null) {
				$insert = "insert into orders (userID) VALUES ($userID)";
				if(mysqli_query($dbc, $insert)) {
					$cartID = $dbc->insert_id;
					$update = "update users set cartID = $cartID where userID = $userID";
					mysqli_query($dbc, $update);
				}
			} else {
				$cartID = $row['cartID'];
			}

			
			// log this login
			$insert = "UPDATE users SET  lastlogin = CURDATE() WHERE userID = $userID";
			$s = mysqli_query ($dbc, $insert);
			
			
			$_SESSION['username']=$username;
			$_SESSION['userID']=$userID;
			$_SESSION['termsaccept']=$termsaccept;
			$_SESSION['isAdmin'] = $isAdmin;
			$_SESSION['cartID'] = $cartID;

			if($termsaccept) {
				header("Location: http://deepblue.cs.camosun.bc.ca/~ics19908/Overhaul/index.php");
			} else {
				header("Location: http://deepblue.cs.camosun.bc.ca/~ics19908/Overhaul/terms.php");
			}
		} else { // login failed
			$_SESSION['loginMessage'] = "Invalid Login Credentials";
		}
		
}	

?>
	<div class="login-panel">
		<h3>Enter your login information.</h3>
<?php 
if(isset($_SESSION['loginMessage'])) { 
?>
		<br>
		<br>
		<hr>
		<h3><?php echo "{$_SESSION['loginMessage']}"; ?></h3>
		<hr>
<?php 
unset($_SESSION['loginMessage']); 
} // end loginMessage if
?>
		<form action="login.php" method="POST">
		<table>
			<tr>
				<td>
					<label for="username">Username:</label>
				</td>
				<td>
					<input type="text" name="username" id="username" required />
				</td>
			</tr>
			<tr>
				<td>
					<label for="pass">Password:</label>
				</td>
				<td>
					<input type="password" name="pass" id="pass" required /></p>
				</td>
			</tr>
		</table>
		<br>
		<div class="centering-wrap"> <input class="submit-button" type="submit" value="Log In"/> </div>
		</form>
		<h4>Don't have an account? Click <a href="register.php">HERE</a> to create one.</h4>
	</div>
	
</body>
</html>