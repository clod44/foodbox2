<?php
require "../../utils/db.php";
require "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!IS_USER_LOGGED_IN()) {
        http_response_code(401); // Unauthorized
        echo json_encode(["error" => "User not logged in."]);
        exit;
    }

    $userID = $_SESSION['user']['id'];
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $province = $_POST['province'];
    $district = $_POST['district'];
    $street = $_POST['street'];

    $sql = "SELECT * FROM users WHERE id=$userID";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        http_response_code(401); // Unauthorized
        echo json_encode(["error" => "User not found"]);
        exit;
    }
    // Update


    $sql = "UPDATE users SET name='$name', phone='$phone', province_id=$province, district_id=$district, street_id=$street WHERE id=$userID";
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