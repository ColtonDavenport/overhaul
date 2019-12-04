<?php
require_once('vendor/autoload.php');

$stripe = [
  "secret_key"      => "sk_test_4GZsGi6D2H3TO8fECB6mzIJg00hdSTUc10",
  "publishable_key" => "pk_test_HKSbrOiakC6tdohgKA32rfCe000XgdyOoR",
];

\Stripe\Stripe::setApiKey($stripe['secret_key']);
?>