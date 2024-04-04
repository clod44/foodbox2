<?php

require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $foodID = $_GET['foodID'];

    $sql = "SELECT categories.* FROM foodcategories, categories WHERE foodcategories.FoodID=$foodID AND categories.CategoryID = foodcategories.CategoryID";
    $result = mysqli_query($conn, $sql);

    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode(["success" => true, "categories" => $categories, "echo" => $_GET]);
    exit;
} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
?>