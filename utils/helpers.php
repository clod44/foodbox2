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

function GET_IMAGE($image)
{
    if ($image == null) {
        $imagePath = '.' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'notfound.jpg';
        return $imagePath;
    }
    $imagePath = '.' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $image;
    //check if file exists
    if (!file_exists($imagePath)) {
        $imagePath = '.' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'notfound.jpg';
    }
    return $imagePath;
}

function UPLOAD_IMAGE($uploadedFileName, $newFileNamePrefix, $callback)
{
    $target_dir = "./media/";

    $uniqueId = uniqid($newFileNamePrefix, false);
    $fileExtension = pathinfo($uploadedFileName, PATHINFO_EXTENSION);
    $newFileName = $uniqueId . "." . $fileExtension;
    $target_file = $target_dir . $newFileName;

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    if (file_exists($target_file)) {
        echo "Sorry, file already exists. try again.";
        $uploadOk = 0;
    }
    if ($_FILES["file"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.";

            // Invoke the callback function with necessary arguments
            $callback($newFileName, $target_dir);

        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}


?>