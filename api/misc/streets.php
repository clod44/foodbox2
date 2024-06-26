<?php
require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $districtid = $_GET["districtid"];

    $sql = "SELECT * FROM streets WHERE districtid = $districtid";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        http_response_code(404);
        echo json_encode(["error" => "no streets found"]);
        exit;
    }
    $streets = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode(["success" => true, "streets" => $streets, "echo" => $_GET]);
    exit;
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
