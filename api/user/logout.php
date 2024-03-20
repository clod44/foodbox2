<?php
require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //I ALSO HAVE TO START THE SESSION HERE?? but i can already access the session variables.. can't i?
    session_start();
    session_destroy();
    session_unset();
    session_start();
    echo json_encode(["success" => true]);
    exit;
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
