<DOCTYPE! html>
<?php session_start()?>
<html>

<head>
	<title>Mississippi</title>
	<meta charset="utf-8">
	<link href="includes/style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<?php include("mysqli_connect.php");
	include("header.php");?>
		
	<div class="content">
		
		<div id="browsebar">
			<img src="includes/logo.png">
			
			<ul id="browseList">
				<li>
					<div class="browse-head">
						Browse By Category
					</div>
					<div id="categories">
							<table>
								<?php include('browseBy.php') ?>
							</table>
					</div>
				</li>
			</ul>
			<div class="centering-wrap">
				<button onclick="View_Products.php"> BROWSE </button>
			</div>
		</div> <!-- End browse bar -->
		
		<div id="center">
			<?php include("printBooks.php")?>
		</div> <!-- End center div -->
		
		<!--<div id="cartbar">
			<h3>Cart</h3>
			<ul id="CartList">
					<li>Hog Book X 1 - $19.95</li>
					<li>Bog Hook X 2 - $21.50</li>
					<li>Kog Hoob X 1 - $3.21</li>
					<hr>
					<li id="total">Total -- $40</li> 
			</ul>
		</div>-->
		
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
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="includes/scripts.js"></script>
</body>

</html>
