<?php

require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $userid = $_GET['userid'];
    $sql = "SELECT * FROM users WHERE id=$userid";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    echo json_encode(["success" => true, "user" => $user, "echo" => $_GET]);
    exit;

} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}


?>