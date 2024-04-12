<?php

require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $foodID = $_POST['foodid'];
    $categories = $_POST['categories'];

    // Clear all categories first:
    $sqlDelete = "DELETE FROM foodcategories WHERE foodid=$foodID";
    $resultDelete = mysqli_query($conn, $sqlDelete);

    // Construct the SQL query to insert multiple rows
    $sqlInsert = "INSERT INTO foodcategories (foodid, categoryid) VALUES ";
    $values = array();
    foreach ($categories as $categoryID) {
        $values[] = "($foodID, $categoryID)";
    }
    $sqlInsert .= implode(", ", $values);

    // Execute the SQL query to insert multiple rows
    $resultInsert = mysqli_query($conn, $sqlInsert);

    if ($resultDelete && $resultInsert) {
        echo json_encode(["success" => true, "echo" => $_POST]);
    } else {
        echo json_encode(["error" => "Failed to insert categories", "echo" => $_POST]);
    }

    exit;
} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
?>