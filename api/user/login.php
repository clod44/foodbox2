<?php
require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $isRestaurant = isset($_POST["is-restaurant"]);

    $sql = "SELECT * FROM " . ($isRestaurant ? "restaurants" : "users") . " WHERE Username = ? AND Password = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $user;
        echo json_encode(["success" => true]);
        exit;
    } else {
        unset($_SESSION['user']);
        http_response_code(401);
        echo json_encode(["error" => "Invalid username or password"]);
        exit;
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}


