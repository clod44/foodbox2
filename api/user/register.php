<?php
require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $repeatPassword = $_POST["password-repeat"]; // Get the repeated password
    $isRestaurant = isset ($_POST["is-restaurant"]);

    // Check if passwords match
    if ($password !== $repeatPassword) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Passwords do not match"]);
        exit;
    }

    $sql = "SELECT * FROM " . ($isRestaurant ? "restaurants" : "users") . " WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Username already exists"]);
        exit;
    }

    $sql = "INSERT INTO " . ($isRestaurant ? "restaurants" : "users") . " (name, username, email, password) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $name, $username, $email, $password);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Fetch newly registered user data
        $newUserId = mysqli_insert_id($conn);
        $sql = "SELECT * FROM " . ($isRestaurant ? "restaurants" : "users") . " WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $newUserId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $newUser = mysqli_fetch_assoc($result);

        SESSION_WRITE('user', $newUser); // Save the newly registered user to session

        echo json_encode(["success" => true]);
        exit;
    } else {
        // Registration failed
        SESSION_UNSET_KEY("user");
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Registration failed: " . mysqli_error($conn)]);
        exit;
    }
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
?>