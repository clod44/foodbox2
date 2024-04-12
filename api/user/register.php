<?php
require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $repeatPassword = $_POST["password-repeat"];
    $isRestaurant = isset($_POST["is-restaurant"]);

    if ($password !== $repeatPassword) {
        http_response_code(400);
        echo json_encode(["error" => "Passwords do not match"]);
        exit;
    }

    $tableName = $isRestaurant ? "restaurants" : "users";

    $sql = "SELECT * FROM $tableName WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        http_response_code(400);
        echo json_encode(["error" => "Username already exists"]);
        exit;
    }

    $sql = "INSERT INTO $tableName (name, username, email, password) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $name, $username, $email, $password);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $newUserId = mysqli_insert_id($conn);
        $sql = "SELECT * FROM $tableName WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $newUserId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $newUser = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $newUser;
        echo json_encode(["success" => true]);
        exit;
    } else {
        unset($_SESSION['user']);
        http_response_code(500);
        echo json_encode(["error" => "Registration failed: " . mysqli_error($conn)]);
        exit;
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
?>