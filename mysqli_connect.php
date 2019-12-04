<?php
DEFINE ('DB_USER', 'ICS199Group08_prod');
DEFINE ('DB_PASSWORD', 'Tempp08');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'ICS199Group08_prod');

// Make the connection:
$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
mysqli_set_charset($dbc, 'utf8');
?>
