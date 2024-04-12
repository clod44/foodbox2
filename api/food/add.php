<?php

require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!IS_USER_LOGGED_IN()) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized"]);
        exit;
    }
    if ($_SESSION['user']['usertype'] != 1) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized"]);
        exit;
    }
    $foodName = $_POST["name"];
    $foodDescription = $_POST["description"];
    $onlyExtra = $_POST["onlyExtra"];

    $foodPrice = $_POST["price"];
    $restaurantID = $_SESSION['user']['id'];
    $sql = "INSERT INTO foods (name, description, restaurantid, onlyextra, price) VALUES ('$foodName', '$foodDescription', $restaurantID , $onlyExtra, $foodPrice)";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to add food"]);
    }
    echo json_encode(["success" => true, "echo" => $_POST]);
    exit;
} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}


?>