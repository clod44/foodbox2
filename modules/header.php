<?php

//lets not remember the page
$PAGE = isset ($_GET['page']) ? $_GET['page'] : "home"; //(isset($_SESSION['page']) ? $_SESSION['page'] : "home");


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
    <script src="./utils/modalGenerator.js"></script>
    <link rel="stylesheet" href="index.css">

    <title>Foodbox</title>
</head>

<body>

    <nav class="bg-light shadow navbar navbar-expand-md p-0 px-4  border-top border-start border-end">
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
                    <li>
                        <div class="input-group flex-nowrap align-items-center h-100">
                            <select class="form-select form-select-sm text-center" id="inputGroupSelect01">
                                <?php for ($i = 0; $i < 3; $i++) { ?>
                                    <option <?= ($i == 0 ? "selected" : "") ?> value="<?= $i ?>">📌 1234 Elm Street,
                                        Springfield, NY 12345, United States</option>
                                <?php } ?>
                            </select>
                        </div>

                    </li>
                    <li class="nav-item hover-scale">
                        <a class="nav-link <?php echo ($PAGE == "home" ? "active fw-bold" : ""); ?>" href="./">Home</a>
                    </li>
                    <li class="nav-item hover-scale">
                        <a class="nav-link <?php echo ($PAGE == "profile" ? "active fw-bold" : ""); ?>"
                            href="?page=profile">
                            <?php
                            echo isset ($_SESSION['user']) ? $_SESSION['user']['name'] : "Profile";
                            ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>