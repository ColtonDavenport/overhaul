<?php
session_start();
unset($_SESSION);
session_destroy();
session_start();
$_SESSION['loginMessage'] = "Successfully logged out";
header("Location: http://deepblue.cs.camosun.bc.ca/~ics19908/Overhaul/login.php");
?>