<?php

//lets not remember the page
$PAGE = isset($_GET['page']) ? $_GET['page'] : "home"; //(isset($_SESSION['page']) ? $_SESSION['page'] : "home");


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--bootstrap-->
    <link rel="stylesheet" href="./lib/bootstrap/css/bootstrap-cartzilla.min.css">
    <!--TODO:use bootstrap icons locally-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <!--ajax jquey-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!--modal generator -->
    <script src="./utils/helpers.js"></script>
    <script src="./utils/modalGenerator.js"></script>
    <script src="./utils/searchResultGenerator.js"></script>
    <link rel="stylesheet" href="index.css">

    <link rel="icon" type="image/png" sizes="16x16" href="./favicon.ico">

    <link rel="manifest" href="./manifest.json">


    <title>Foodbox 2</title>
</head>

<body>

    <nav class="bg-light shadow navbar navbar-expand-md p-0 px-md-4  border-top border-start border-end">
        <div class="container-fluid">
            <a class="navbar-brand d-flex justify-content-center align-items-center hover-scale" href="./">
                <span class="p-0 m-0" style="font-size:3rem;">📦</span>
                <div class="d-flex flex-column">
                    <h1 class="p-0 m-0 text-primary" style="line-height: 1em">Foodbox</h1>
                    <h2 class="m-0 p-0 fs-7 ms-auto" style="line-height: 0em;">- since 2003</h2>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <?php
                    if (IS_USER_LOGGED_IN()) {
                        if ($_SESSION['user']['usertype'] != 1) {
                            ?>
                            <li class="me-2">
                                <div class="input-group flex-nowrap align-items-center h-100">
                                    <select class="form-select form-select-sm text-center" id="selected-address">
                                        <?php
                                        $sql = "SELECT * FROM addresses WHERE userid={$_SESSION['user']['id']}";
                                        $result = mysqli_query($conn, $sql);
                                        if (!$result) {
                                            echo "<p>No address found</p>";
                                        } else {
                                            $addresses = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                        }

                                        //get selected address id
                                        $selectedAddressID = $_SESSION["user"]["addressid"];

                                        if (count($addresses) == 0) {
                                            ?>
                                            <option selected value="-1">📌 Add an addresses from your profile! 📌</option>
                                            <?php
                                        } else {
                                            foreach ($addresses as $index => $address) {
                                                $isSelected = $address['id'] == $selectedAddressID ? "selected" : "";
                                                ?>
                                                <option value="<?= $address['id'] ?>" <?= $isSelected ?>>
                                                    <?= ($index + 1) . GET_ADDRESS_TEXT($address['id']) ?>
                                                </option>
                                            <?php }
                                        } ?>
                                    </select>
                                    <script>
                                        $(document).ready(function () {
                                            $('#selected-address').change(function () {
                                                var addressid = $(this).val();
                                                $.ajax({
                                                    type: "POST",
                                                    url: "./api/user/updateaddress.php",
                                                    data: {
                                                        addressid: addressid
                                                    },
                                                    dataType: 'json',
                                                    success: function (response) {
                                                        console.log(response)
                                                        if (response.success) {
                                                            window.location.href = '?page=profile';
                                                        } else {
                                                            console.log(response.error);
                                                            alert('Login failed:\n' + response.error);
                                                        }
                                                    },
                                                    error: function (xhr, status, error) {
                                                        var errorMessage = xhr.responseText ? xhr.responseText : 'Unknown error';
                                                        console.log(errorMessage); // Log the error message to the console
                                                        alert('Login failed:\n' + errorMessage);
                                                    }
                                                });
                                            });
                                        });
                                    </script>
                                </div>

                            </li>
                            <?php
                        }
                        if ($_SESSION['user']['usertype'] == 1) {
                            ?>
                            <div class="d-flex align-items-center">
                                <a href="?page=panel" class="col btn btn-warning hover-scale" type="button">
                                    Control
                                    Panel ⚙️
                                </a>
                            </div>

                            <?php
                        }
                    } /*?>
<li class="nav-item hover-scale align-items-center justify-content-center d-flex">
<a class="nav-link text-nowrap <?php echo ($PAGE == "home" ? "active fw-bold" : ""); ?>"
href="./">Home</a>
</li>
<?php */
                    if ((IS_USER_LOGGED_IN() && $_SESSION['user']['usertype'] != 1)) {
                        //for users which are not restaurant
                        //fetch orders which have 0,1 or 2 status
                        $sql = "SELECT count(id) FROM orders WHERE userid={$_SESSION['user']['id']} AND status IN (0,1,2) AND orderconfirmed=1";
                        $result = mysqli_query($conn, $sql);
                        $orderCount = mysqli_fetch_assoc($result)['count(id)'];
                    }
                    ?>
                    <li class="nav-item hover-scale align-items-center justify-content-center d-flex">
                        <a class="nav-link text-nowrap <?php echo ($PAGE == "orderhistory" ? "active fw-bold" : ""); ?>"
                            href="?page=orderhistory"><?= ($orderCount ?? 0) > 0 ? (
                                "<div class='spinner-grow spinner-grow-sm text-primary' role='status'>
                                    <span class='visually-hidden'>Loading...</span>
                                </div>") : "" ?>History📜</a>
                    </li>
                    <?php
                    if (IS_USER_LOGGED_IN() && $_SESSION['user']['usertype'] != 1) {
                        //for users which are not restaurant
                        $sql = "SELECT count(id) FROM orders WHERE userid={$_SESSION['user']['id']} AND orderconfirmed=0";
                        $result = mysqli_query($conn, $sql);
                        $boxItemsCount = mysqli_fetch_assoc($result)['count(id)'];
                    }
                    ?>
                    <li class="nav-item hover-scale align-items-center justify-content-center d-flex">
                        <a class="nav-link text-nowrap <?php echo ($PAGE == "checkout" ? "active fw-bold" : ""); ?>"
                            href="?page=checkout"><?= ($boxItemsCount ?? 0) > 0 ? (
                                "<span class='badge text-bg-primary'>" . $boxItemsCount . "</span>") : "" ?>My📦</a>
                    </li>

                    <li class="nav-item hover-scale text-center align-items-center">
                        <?php
                        if (IS_USER_LOGGED_IN()) {
                            ?>
                            <a href="?page=profile"
                                class="m-0 nav-link d-flex flex-nowrap w-100 h-100 gap-2 justify-content-center align-items-center">
                                <p class="m-0 p-0 fs-6 <?= ($PAGE == "profile" ? "active fw-bold" : ""); ?>">
                                    <?= $_SESSION['user']['username'] ?>
                                </p>
                                <img src="<?= GET_IMAGE($_SESSION['user']['image']) ?>" class="rounded-circle shadow"
                                    style="height:2em;width:2em;object-fit:cover;">
                            </a>
                            <?php
                        } else {
                            ?>
                            <a class="nav-link text-nowrap <?= ($PAGE == "profile" ? "active fw-bold" : ""); ?>"
                                href="?page=profile">
                                Login
                            </a>
                            <?php
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>