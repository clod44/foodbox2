<?php

require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $foodID = $_GET['foodID'];


    $sql = "SELECT * FROM foods WHERE foods.id=$foodID";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        http_response_code(404);
        echo json_encode(["error" => "food not found"]);
        exit;
    }


    $food = mysqli_fetch_assoc($result);
    echo json_encode(["success" => true, "food" => $food, "echo" => $_GET]);
    exit;

} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}


?>