<?php
require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $district = $_POST["district"];

    $sql = "SELECT * FROM streets WHERE district_id = $district";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $streets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode(["streets" => $streets]);
        exit;
    } else {
        http_response_code(404);
        echo json_encode(["error" => "no streets found"]);
        exit;
    }
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
