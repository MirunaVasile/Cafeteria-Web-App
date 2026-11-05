<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "restaurant_db"; 

$conexiune = mysqli_connect($host, $user, $pass, $db_name);

if (!$conexiune) {
    die("Conexiunea a esuat: " . mysqli_connect_error());
}
?>