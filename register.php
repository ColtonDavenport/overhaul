<html>
<head>
<title> Create an account </title>
<link type='text/css' rel='stylesheet' href='includes/style.css'/>
<link type='text/css' rel='stylesheet' href='includes/login-style.css'/>
<script>
function validation() {
	//email validation variables
	var email = document.getElementById('email').value;
	var emailRGEX = /[\w]+@[a-zA-Z]{2,}.[a-zA-Z]{2,}/;
	var emailResult = emailRGEX.test(email);
	
	//postal code validation variables
	var postalCode = document.getElementById('postal').value;
	var postalRGEX = /[A-Za-z]\d[A-Za-z][\s]?\d[A-Za-z]\d/;
	var postalResult = postalRGEX.test(postalCode);
	
	if (document.getElementById('pass').value.length < 4) {
		alert("Password must be at least 4 characters in length");
		return false;
	}//min password length met?
	
	if (document.getElementById('postal').value.length > 6) {
		alert("Postal Code cannot be longer than 6 characters");
		return false;
	}//min password length met?
	
	if (document.getElementById('pass').value != document.getElementById('passtest').value){
		alert("Passwords must match.");
		return false;
	}// check if passwords match
	
	if(emailResult == false){
		alert("Please enter a valid email");
		return false;
    }//check for valid email
	
	if(postalResult == false){
		alert("Please enter a valid postal code");
		return false;
    } //check for valid postal code
	
return true;
}
</script>
</head>
<body>
<?php
session_start();
include('phpAux/mysqli_connect.php');
include("header.php");	

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	//collecting customer data
	$fName = $_POST['fName'];
	$lName = $_POST['lName'];
	$email = $_POST['email'];
	$address = $_POST['address'];
	$postal = $_POST['postal'];
	$username = $_POST['username'];
	$pass = $_POST['pass'];
	

	//Query to add to database
	$query = "INSERT INTO users(fName, lName, email, address, postal, username, pass, regDate) 
				VALUES ('$fName','$lName', '$email', '$address', '$postal', '$username', SHA1('$pass'), CURDATE())";
	
	if(!mysqli_query($dbc, $query)){
			echo "Error: ". mysqli_error($dbc);
		}else{
			$_SESSION['loginMessage'] = "New Account Created";
			header("Location: http://deepblue.cs.camosun.bc.ca/~ics19908/Overhaul/login.php");
		}
		
} else { //Display form

?>
<div class="login-panel">
<h3>Enter your information to create an account</h3>
	<form action="register.php" method="POST" onsubmit="return validation();">
		<table>
		<tr style="background-color:#ffc907"> <td>First Name:       </td><td><input type="text" name="fName" id="fName" required /></td></tr>
		<tr style="background-color:orange">  <td>Last Name:        </td><td><input type="text" name="lName" id="lName" required /></td></tr>
		<tr style="background-color:#ffc907"> <td>User Name:        </td><td><input type="text" name="username" id="username" required /></td></tr>
		<tr style="background-color:orange">  <td>Password:         </td><td><input type="password" name="pass" id="pass" required /></td></tr>
		<tr style="background-color:#ffc907"> <td>Verify Password:  </td><td><input type="password" name="passtest" id="passtest" required /></td></tr>
		<tr style="background-color:orange">  <td>Email Address:    </td><td><input type="text" name="email" id="email" required /></td></tr>
		<tr style="background-color:#ffc907"> <td>Shipping address: </td><td><input type="text" name="address" id="address" required /></td></tr>
		<tr style="background-color:orange">  <td>Postal Code:      </td><td><input type="text" name="postal" id="postal" required /></td></tr>
		</table>
		<br>
		<div class="centering-wrap"> <input type="submit" value="REGISTER"  /> </div>
	</form>
<h4>Already have an account? Login <a href="login.php">HERE</a>.</h4>
</div>
<?php
}//just to close out that last else statement
?>
</body>