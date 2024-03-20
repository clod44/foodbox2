<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "./utils/db.php";
require_once "./utils/helpers.php";
session_start();

require "./modules/header.php";


// Define the base directory for the pages
$pagesDir = "./pages/";

// Get the page from the query string or set default
$PAGE = isset ($_GET['page']) ? $_GET['page'] : 'home';

// Construct the file path
$pageFilePath = $pagesDir . $PAGE . ".php";

// Check if the page file exists
if (file_exists($pageFilePath)) {
    // Include the page file
    require $pageFilePath;
} else {
    // Include the notfound.php page
    require $pagesDir . "notfound.php";
}


require_once "./modules/footer.php";
?>