<?php
require "../../utils/db.php";
require "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!IS_USER_LOGGED_IN()) {
        http_response_code(401); // Unauthorized
        echo json_encode(["error" => "User not logged in."]);
        exit;
    }

    $userid = $_SESSION['user']['id'];
    $tableName = ($_SESSION['user']['usertype'] == 1 ? 'restaurants' : 'users');
    $addressid = $_POST["addressid"];

    //update data
    $sql = "UPDATE $tableName SET addressid='$addressid' WHERE id=$userid";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Database error: " . mysqli_error($conn)]);
        exit;
    }

    UPDATE_SESSION_USER();
    echo json_encode(["success" => true]);
    exit;

} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
?>