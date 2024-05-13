<?php
//this needs to be put as much high as possible in order to access its variables
$_SESSION["pagination"] = [
    "page" => $_REQUEST["p"] ?? ($_SESSION["pagination"]["page"] ?? 0),
    "perpage" => $_REQUEST["perpage"] ?? ($_SESSION["pagination"]["perpage"] ?? 999)
];
?>