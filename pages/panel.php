<?php
if (!IS_USER_LOGGED_IN()) {
    header("Location: ?page=login");
    exit();
}
if ($_SESSION['user']['UserType'] != 1) {
    header("Location: ?page=home");
    exit();
}



$PANEL = isset($_GET['panel']) ? $_GET['panel'] : 'orders';
?>
<ul class="nav nav-tabs justify-content-center sticky-top text-bg-light">
    <li class="nav-item">
        <a class="nav-link <?= $PANEL == 'orders' ? ' active' : ''; ?>" href="?page=panel&panel=orders">Orders</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $PANEL == 'addfood' ? ' active' : ''; ?>" href="?page=panel&panel=addfood">Add
            Food</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $PANEL == 'editfood' ? ' active' : ''; ?>" href="?page=panel&panel=editfood">Edit
            Food</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $PANEL == 'details' ? ' active' : ''; ?>" href="?page=panel&panel=details">Details</a>
    </li>
</ul>
<?php

$pagesDir = "./pages/panel/";
$pageFilePath = $pagesDir . $PANEL . ".php";

if (file_exists($pageFilePath))
    require $pageFilePath;
else
    require $pagesDir . "notfound.php";
?>