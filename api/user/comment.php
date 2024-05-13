<?php
require "../../utils/db.php";
require "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!IS_USER_LOGGED_IN() || $_SESSION['user']['usertype'] == 1) {
        http_response_code(401); // Unauthorized
        echo json_encode(["error" => "not authorized"]);
        exit;
    }

    $userid = $_SESSION['user']['id'];
    $score = mysqli_real_escape_string($conn, $_POST["score"]);
    $comment = mysqli_real_escape_string($conn, $_POST["comment"]);
    $orderdetailid = mysqli_real_escape_string($conn, $_POST["orderdetailid"]);
    $foodid = mysqli_real_escape_string($conn, $_POST["foodid"]);

    //first delete the user's previous comment with same details
    $sql = "DELETE FROM comments WHERE userid = $userid AND orderdetailid = $orderdetailid";
    $result = mysqli_query($conn, $sql);
    //create new comment
    $sql = "INSERT INTO comments (userid, orderdetailid, foodid, score, comment) VALUES($userid, $orderdetailid, $foodid, $score, '$comment')";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Failed to create comment"]);
        exit;
    }
    echo json_encode(["success" => true]);
    exit;

} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
?>