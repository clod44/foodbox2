<?php
//returns also the answers
require_once "../../utils/db.php";
require_once "../../utils/helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $foodid = $_GET['foodid'];

    $sql = "SELECT * FROM questions WHERE foodid=$foodid";
    $result = mysqli_query($conn, $sql);
    $questions = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $answers = [];
    foreach ($questions as $question) {
        $sql = "SELECT * FROM answers WHERE questionid={$question['id']}";
        $result = mysqli_query($conn, $sql);
        $answers[$question['id']] = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    echo json_encode(["success" => true, "questions" => $questions, "answers" => $answers, "echo" => $_GET]);
    exit;
} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}
?>