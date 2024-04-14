<?php


function UPDATE_SESSION_USER()
{
    if (!isset($_SESSION['user'])) {
        return false;
    }
    global $conn;
    $isRestaurant = $_SESSION['user']['usertype'] == 1;
    $usersTableName = ($isRestaurant ? 'restaurants' : 'users');
    $sql = "SELECT * FROM $usersTableName WHERE id={$_SESSION['user']['id']}";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        return false;
    }
    $_SESSION['user'] = mysqli_fetch_assoc($result);
}

function IS_USER_LOGGED_IN()
{
    return isset($_SESSION['user']);
}

function FixedDecimal($number, $digits = 2)
{
    return number_format((float) $number, $digits, '.', '');
}

function savelog($message)
{
    $logFile = __DIR__ . '/debug.log';  // Path to debug.log in the root directory

    $timestamp = date('Y-m-d H:i:s');  // Current date and time in a readable format
    $formattedTimestamp = "[" . $timestamp . "]";  // Format the timestamp as desired

    // Prepare the log entry with timestamp and message
    $logEntry = $formattedTimestamp . " - " . $message . PHP_EOL;

    // Append the log entry to the debug.log file
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

function alert($message)
{
    echo '<script type="text/javascript">alert("' . $message . '");</script>';
}

?>