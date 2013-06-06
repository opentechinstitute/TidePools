<?php
require 'encrypt.php';
$str = "password";

$converter = new Encryption;
$encoded = $converter->encode($str );
$decoded = $converter->decode($encoded);    

echo "$encoded<p>$decoded";
?>