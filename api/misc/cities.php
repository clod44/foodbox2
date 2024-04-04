<?php
require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "SELECT * FROM cities";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $cities = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode(["cities" => $cities]);
        exit;
    } else {
        http_response_code(404);
        echo json_encode(["error" => "no cities found!"]);
        exit;
    }
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
