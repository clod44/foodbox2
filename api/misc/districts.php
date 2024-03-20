<?php
require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $province = $_POST["province"];

    $sql = "SELECT * FROM districts WHERE province_id = $province";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $districts = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode(["districts" => $districts]);
        exit;
    } else {
        http_response_code(404);
        echo json_encode(["error" => "no districts found"]);
        exit;
    }
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
