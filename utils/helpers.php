<?php


function UPDATE_SESSION_USER()
{
    if (!isset ($_SESSION['user'])) {
        return false;
    }
    global $conn;
    $sql = "SELECT * FROM users WHERE id={$_SESSION['user']['id']}";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        return false;
    }
    $_SESSION['user'] = mysqli_fetch_assoc($result);
}

function IS_USER_LOGGED_IN()
{
    return isset ($_SESSION['user']);
}
?>