<html>
	<head>
		<title>Mississippi</title>
		<meta charset="utf-8">
		<link href="includes/style.css" rel="stylesheet" type="text/css">
	</head>
<?php
  session_start();
  include('mysqli_connect.php');
  include('includes/header.html');
  include("header.php");
  require_once('./config.php');

  $token  = $_POST['stripeToken'];
  $email  = $_POST['stripeEmail'];
  
  $totalamt = $_POST['totalamt'];
  
  $customer = \Stripe\Customer::create(array(
      'email' => $email,
      'source'  => $token
  ));

  $charge = \Stripe\Charge::create(array(
      'customer' => $customer->id,
      'amount'   => $totalamt,
      'currency' => 'cad'
  ));

$amount = number_format(($totalamt / 100), 2);
  echo '<div id="center" style="position:relative; left:15%;">';
  echo '<h3>Successfully charged $'.$amount.' </h3>';
  // Clear the cart:
  unset($_SESSION['cart']);
  include('checkout.php');
  include ('includes/footer.html');
    echo '</div>';
  //checkout();
?>