<?php
require_once "../utils/db.php";
require_once "../utils/helpers.php";

header("Content-Type:application/xml");
$id = $_GET["q"];
$sql = "SELECT * FROM users where id = $id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

$data = '<?xml version="1.0" encoding="UTF-8"?>';
$data .=
    '<user>
        <username>' . $user["username"] . '</username>
        <email>' . $user["email"] . '</email>
    </user>';
echo $data;
?>