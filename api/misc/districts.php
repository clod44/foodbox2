<?php
require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $cityid = $_GET["cityid"];

    $sql = "SELECT * FROM districts WHERE cityid = $cityid";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        http_response_code(404);
        echo json_encode(["error" => "no districts found"]);
        exit;
    }
    $districts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode(["success" => true, "districts" => $districts, "echo" => $_GET]);
    exit;
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
