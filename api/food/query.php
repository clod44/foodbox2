<?php

require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Base SQL query
    $sql = "SELECT 
        foods.id as foodid, 
        foods.name as foodname,
        foods.description as fooddescription,
        foods.price as foodprice,
        foods.onlyextra as foodonlyextra,
        foods.visible as foodvisible,
        restaurants.id as restaurantid,
        restaurants.name as restaurantname
        FROM foods, restaurants " .
        (isset($_GET["categories"]) ? ", foodcategories" : "") .
        " WHERE foods.restaurantid = restaurants.id";

    //"AND" conditions 
    $conditions = [];

    if (isset($_GET['foodid'])) {
        $foodid = $_GET['foodid'];
        $conditions[] = "foods.id = $foodid";
    }
    if (isset($_GET['price-max'])) {
        $priceMax = (float) $_GET['price-max'];
        $conditions[] = "foods.price <= $priceMax";
    }
    if (isset($_GET['price-min'])) {
        $priceMin = (float) $_GET['price-min'];
        $conditions[] = "foods.price >= $priceMin";
    }
    if (isset($_GET['keywords'])) {
        $keywords = '%' . $_GET['keywords'] . '%';
        $conditions[] = "foods.name LIKE '$keywords'";
    }
    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }


    //"OR" conditions 
    $conditions = [];
    if (isset($_GET['categories'])) {
        //for each category
        foreach ($_GET['categories'] as $categoryid) {
            $conditions[] = "(foodcategories.categoryid = $categoryid AND foodcategories.foodid=foods.id)";
        }
    }
    if (!empty($conditions)) {
        $sql .= " AND (" . implode(" OR ", $conditions) . ")";
    }
    savelog($sql);

    // Check if score-min is provided
    //TODO: implement score rating filtering


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