<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "./utils/db.php";
require_once "./utils/helpers.php";

require "./modules/header.php";

$pagesDir = "./pages/";
$PAGE = isset ($_GET['page']) ? $_GET['page'] : 'home';
$pageFilePath = $pagesDir . $PAGE . ".php";

if (file_exists($pageFilePath))
    require $pageFilePath;
else
    require $pagesDir . "notfound.php";

require_once "./modules/footer.php";
?>