<?php

require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    //savelog(implode(", ", $_GET));
    // Base SQL query
    $sql = "SELECT foods.* FROM foods, restaurants " .
        (isset($_GET["categories"]) ? ", foodcategories" : "") .
        (isset($_GET["favorites-only"]) ? ", favoriterestaurants" : "") .
        " WHERE foods.restaurantid = restaurants.id" .
        (isset($_GET["restaurantid"]) ? " AND foods.restaurantid={$_GET['restaurantid']} " : "");

    //"AND" conditions 
    $conditions = [];
    //savelog($sql);

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
    if (isset($_GET['favorites-only'])) {
        $conditions[] = "favoriterestaurants.userid = {$_SESSION['user']['id']} AND favoriterestaurants.restaurantid = foods.restaurantid";
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
    //savelog($sql);

    // Check if score-min is provided
    //TODO: implement score rating filtering


    // Check if sort-dir and sort-type are provided
    if (isset($_GET['sort-dir']) && isset($_GET['sort-type'])) {
        $sortDir = $_GET['sort-dir'] == "ASC" ? "ASC" : "DESC";
        $sortType = $_GET['sort-type'] == "price" ? "price" : "food_name";
        $sql .= " ORDER BY $sortType $sortDir";
    }

    //pagination
    $p = isset($_GET['page']) ? $_GET['page'] : 0;
    $perpage = isset($_GET['perpage']) ? $_GET['perpage'] : 10;
    $sql .= " LIMIT " . ($p * $perpage) . ", $perpage";


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