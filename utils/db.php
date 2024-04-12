<?php
$username = "root";
$password = "";
$servername = "localhost";
$dbname = "foodbox-2";

$conn = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8mb4");

if (!$conn) {
    die("connection failed: " . mysqli_connect_error());
}

date_default_timezone_set('UTC');  // Set timezone (change as needed)

session_start();

?>