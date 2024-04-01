<?php

require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Base SQL query
    $sql = "SELECT 
        foods.id as food_id, 
        foods.name as food_name,
        foods.description as food_description,
        foods.price as food_price,
        restaurants.id as restaurant_id,
        restaurants.name as restaurant_name
        FROM foods, restaurants 
        WHERE foods.restaurant_id = restaurants.id";

    // Array to store conditions
    $conditions = [];

    // Check if categories are provided
    // TODO: implement category filtering

    // Check if price-max is provided
    if (isset($_GET['price-max'])) {
        $priceMax = (float) $_GET['price-max'];
        $conditions[] = "foods.price <= $priceMax";
    }

    // Check if price-min is provided
    if (isset($_GET['price-min'])) {
        $priceMin = (float) $_GET['price-min'];
        $conditions[] = "foods.price >= $priceMin";
    }

    // Check if score-min is provided
    //TODO: implement score rating filtering

    // Add conditions to the SQL query
    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }

    // Check if sort-dir and sort-type are provided
    if (isset($_GET['sort-dir']) && isset($_GET['sort-type'])) {
        $sortDir = $_GET['sort-dir'] == "ASC" ? "ASC" : "DESC";
        $sortType = $_GET['sort-type'] == "price" ? "price" : "food_name";
        $sql .= " ORDER BY $sortType $sortDir";
    }

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        http_response_code(404);
        echo json_encode(["error" => "food not found"]);
        exit;
    }

    $foodAndRestaurantDetails = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode(["success" => true, "foodAndRestaurantDetails" => $foodAndRestaurantDetails, "echo" => $_GET]);
    exit;

} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}


?>