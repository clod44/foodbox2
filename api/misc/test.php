<?php

require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $keywords = $_GET['q'];
    $sql = "SELECT * FROM foods WHERE name LIKE '%" . $keywords . "%'";
    $result = mysqli_query($conn, $sql);
    $foods = mysqli_fetch_all($result, MYSQLI_ASSOC);



    echo json_encode(["success" => true, "foods" => $foods, "echo" => $_GET]);
    exit;

} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}


?>