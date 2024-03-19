<?php

if (isset ($_POST['logout'])) {
    session_unset();
}
if (SESSION_READ("user") == null) {
    require "./modules/login.php";
} else {
    ?>
    <form method="POST">
        <input type="submit" name="logout" value="logout">
    </form>
    <p>this is your profile</p>






    <?php
}
?>