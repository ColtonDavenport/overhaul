<html>
<head>
<title>Login Page</title>
<link type='text/css' rel='stylesheet' href='includes/style.css'/>
<link type='text/css' rel='stylesheet' href='includes/login-style.css'/>
</head>
<body>
<div id="container">
<?php
session_start();
include('phpAux/mysqli_connect.php');
	$username = $_SESSION['username'];
	$userID = $_SESSION['userID'];
	$termsaccept = $_SESSION['termsaccept'];
	if(isset($_POST['agree'])){
		
		$_SESSION['username']=$username;
		$_SESSION['userID']=$userID;
		$_SESSION['termsaccept']=$termsaccept;
		
		$terms = "UPDATE users SET termsaccept = 1 WHERE userID = $userID";
		$s = @mysqli_query ($dbc, $terms);

		$date = "UPDATE users SET  lastlogin = CURDATE() WHERE userID = $userID";
		$s = @mysqli_query ($dbc, $date);
		
		echo "<script> alert('') </script>";
		header("Location: http://deepblue.cs.camosun.bc.ca/~ics19908/Overhaul/index.php");
		
	} elseif(isset($_POST['disagree'])){
		
		$insert = "UPDATE users SET termsaccept = 0 WHERE userID = $userID";
		$s = @mysqli_query ($dbc, $insert);
		unset($_SESSION);
		session_destroy();
		session_start();
		$_SESSION['loginMessage'] = "You must agree to terms to log in";
		header("Location: http://deepblue.cs.camosun.bc.ca/~ics19908/Overhaul/login.php");
		
	} else {
		?>
			<div class="login-panel">
				<h3>Privacy Policy Change</h3>
				<br>
				<p>Hello, the government of Canada will be introducing a new privacy law know as 
				the "General Data Protection Regulation". This law will require that individuals 
				must give their explicit permission for their data to be used and gives individuals the 
				right to know who is accessing their information and what it will be used for. All 
				companies collection and/ or using personal information on Canadian citizen must comply
				with this new law.</p>
				<h4>What information are we collecting?</h4>
				<p>We are simply tracking the date of which a users last login took place, as well as 
				whether or not they have accepted the terms of this new privacy policy change.</p>
				<h4>How will we use this information?</h4>
				<p>This information is being used to monitor the frequency of site traffic and to let 
				us know if users have accepted this policy change.</p>
				<form action="terms.php" method="POST">
					<input type="hidden" name="username" id="username" value="<?php  echo $username; ?>">
					<input type="hidden" name="userID" id="userID" value="<?php echo $userID; ?>">
					<input type="hidden" name="termsaccept" id="termsaccept" value="<?php echo $termsaccept; ?>">
					<input type = "submit" name="agree" value="I Agree"/>
					<input type = "submit" name="disagree" value="I Disagree"/>
				</form>
			</div>
		<?php
	}

?>
</div>
</body>
</html>