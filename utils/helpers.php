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
    $logFile = __DIR__ . '/debug.log';
    $timestamp = date('Y-m-d H:i:s');
    $formattedTimestamp = "[" . $timestamp . "]";

    $logEntry = $formattedTimestamp . " - " . $message . PHP_EOL;
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
        //alert("File is an - " . $check["mime"] . ".");
        $uploadOk = 1;
    } else {
        alert("File is not an image.");
        $uploadOk = 0;
    }
    if (file_exists($target_file)) { // this isn't supposed to happen
        alert("Sorry, file already exists. try again.");
        $uploadOk = 0;
    }
    if ($_FILES["file"]["size"] > 500000) {
        alert("Sorry, your file is too large.");
        $uploadOk = 0;
    }
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    ) {
        alert("Sorry, only JPG, JPEG, PNG files are allowed.");
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        alert("Sorry, your file was not uploaded. kill yourself.");
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            alert("The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.");

            // invoke the callback function with necessary arguments
            $callback($newFileName, $target_dir);

        } else {
            alert("Sorry, there was an error uploading your file.");
        }
    }
}

function console_log($message)
{
    echo '<script>';
    echo 'console.log(' . $message . ')';
    echo '</script>';
}


function GET_BOX_PRICE($userid, $orderid = null)
{
    global $conn;
    if ($orderid == null) {
        $sql = "SELECT * FROM orders WHERE userid=$userid AND orderconfirmed=0";
    } else {
        $sql = "SELECT * FROM orders WHERE id=$orderid";
    }
    $result = mysqli_query($conn, $sql);
    $order = mysqli_fetch_assoc($result);
    if ($order == null) {
        return 0;
    }

    $sql = "SELECT * FROM orderdetails WHERE orderid={$order['id']}";
    $result = mysqli_query($conn, $sql);
    $orderdetails = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if ($orderdetails == null) {
        return 0;
    }

    $price = 0;
    foreach ($orderdetails as $orderdetail) {
        $price += GET_ORDERDETAIL_PRICE($orderdetail['id']);
    }
    return $price;
}
function GET_ORDERDETAIL_PRICE($orderdetailid)
{
    global $conn;
    $sql = "SELECT * FROM orderdetails WHERE id=$orderdetailid";
    $result = mysqli_query($conn, $sql);
    $orderdetail = mysqli_fetch_assoc($result);
    $price = $orderdetail['price'];

    $sql = "SELECT * FROM orderdetailquestionanswers WHERE orderdetailid=$orderdetailid";
    $result = mysqli_query($conn, $sql);
    $orderdetailquestionanswers = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if ($orderdetailquestionanswers == null) {
        return $price;
    }

    foreach ($orderdetailquestionanswers as $orderdetailquestionanswer) {
        $price += $orderdetailquestionanswer['price'];
    }
    return $price;
}

function UPDATE_BOX_PRICES()
{
    global $conn;
    $sql = "SELECT * FROM orders WHERE userid={$_SESSION['user']['id']} AND orderconfirmed=0";
    $result = mysqli_query($conn, $sql);
    $order = mysqli_fetch_assoc($result);

    $sql = "SELECT * FROM orderdetails WHERE orderid={$order['id']}";
    $result = mysqli_query($conn, $sql);
    $orderdetails = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($orderdetails as $orderdetail) {
        $sql = "SELECT * FROM foods WHERE id={$orderdetail['foodid']}";
        $result = mysqli_query($conn, $sql);

        $food = mysqli_fetch_assoc($result);
        $sql = "UPDATE orderdetails SET price={$food['price']} WHERE id={$orderdetail['id']}";
        $result = mysqli_query($conn, $sql);

        $sql = "SELECT * FROM orderdetailquestionanswers WHERE orderdetailid={$orderdetail['id']}";
        $result = mysqli_query($conn, $sql);
        $orderdetailquestionanswers = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($orderdetailquestionanswers as $orderdetailquestionanswer) {
            $sql = "SELECT * FROM answers WHERE id={$orderdetailquestionanswer['answerid']}";
            $result = mysqli_query($conn, $sql);
            $answer = mysqli_fetch_assoc($result);

            $sql = "UPDATE orderdetailquestionanswers SET price={$answer['price']} WHERE id={$orderdetailquestionanswer['id']}";
            $result = mysqli_query($conn, $sql);
        }
    }
}

function GET_ADDRESS_TEXT($addressid)
{
    global $conn;
    $sql = "SELECT * FROM addresses WHERE id=$addressid";
    $result = mysqli_query($conn, $sql);
    $address = mysqli_fetch_assoc($result);
    if ($address == null) {
        return "⚠️ address not found ⚠️";
    }
    $streetid = $address['streetid'];
    $districtid = $address['districtid'];
    $cityid = $address['cityid'];

    $sql = "SELECT * FROM cities WHERE id=$cityid";
    $result = mysqli_query($conn, $sql);
    $city = mysqli_fetch_assoc($result);

    $sql = "SELECT * FROM districts WHERE id=$districtid";
    $result = mysqli_query($conn, $sql);
    $district = mysqli_fetch_assoc($result);

    $sql = "SELECT * FROM streets WHERE id=$streetid";
    $result = mysqli_query($conn, $sql);
    $street = mysqli_fetch_assoc($result);

    $cityName = $city['name'] ?? 'not found';
    $districtName = $district['name'] ?? 'not found';
    $streetName = $street['name'] ?? 'not found';


    return "📌 " . $streetName . ", " . $districtName . ", " . $cityName;
}
?>