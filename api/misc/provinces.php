<?php
require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "SELECT * FROM provinces";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $provinces = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode(["provinces" => $provinces]);
        exit;
    } else {
        http_response_code(404);
        echo json_encode(["error" => "no provinces found!"]);
        exit;
    }
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
