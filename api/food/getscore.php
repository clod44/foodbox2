<?php

require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $foodid = $_GET['foodid'];


    //get score average of the comments given to the restaurant
    $sql = "SELECT avg(comments.score) as avgscore FROM comments WHERE comments.foodid=$foodid";
    $result = mysqli_query($conn, $sql);
    $score = mysqli_fetch_assoc($result)['avgscore'];

    echo json_encode(["success" => true, "score" => $score ?? 0, "echo" => $_GET]);
    exit;
} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
?>