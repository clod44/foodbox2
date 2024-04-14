<?php

require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $foodid = $_GET['foodid'];

    $sql = "SELECT restaurants.* FROM restaurants,foods WHERE restaurants.id=foods.restaurantid AND foods.id=$foodid";
    $result = mysqli_query($conn, $sql);

    $restaurant = mysqli_fetch_assoc($result);
    echo json_encode(["success" => true, "restaurant" => $restaurant, "echo" => $_GET]);
    exit;
} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
?>