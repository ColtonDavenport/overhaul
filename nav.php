<?php # Script 19.3 - header.html
// This page begins the session, the HTML page, and the layout table.

session_start(); // Start a session.
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />	
	<title><?php echo (isset($page_title)) ? $page_title : 'Welcome!'; ?></title>
	<link rel="stylesheet" href="includes/style.css" type="text/css" media="screen" />
</head>


<body>
	<div class="topnav">
		<ul>
			<li><a href = "index.php">Mississippi</a>
			<li><a href = "cart.html">Cart</a>
			<li><a href = "log.html">Login</a>
			<li><a href = "order_history.php">Order History</a>
		</ul>
	</div>