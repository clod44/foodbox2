<?php if (isset($_POST['createmenu'])) {
    $sql = "INSERT INTO menus (restaurantid) VALUES ({$_SESSION['user']['id']})";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo mysqli_error($conn);
    }
    alert("new menu created. dont forget to customize it and make it visible to customers");
}
if (isset($_POST['savemenufoods'])) {
    $foodids = $_POST['foodids'];
    //first. clear all the attached foods from this menu 
    $sql = "DELETE FROM menufoods WHERE menuid={$_POST['menuid']}";
    $result = mysqli_query($conn, $sql);
    //now add the new ones 
    foreach ($foodids as $foodid) {
        $sql = "INSERT INTO menufoods (menuid, foodid) VALUES ({$_POST['menuid']}, $foodid)";
        $result = mysqli_query(
            $conn,
            $sql
        );
        if (!$result) {
            echo mysqli_error($conn);
            exit();
        }
    }
    alert("menu foods updated");
}
if
(isset($_POST['savemenudetails'])) {
    $sql = "UPDATE menus SET name='{$_POST['name']}', description='{$_POST['description']}', visible={$_POST['visible']} WHERE id={$_POST['menuid']}";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo mysqli_error($conn);
        exit();
    }
    alert("menu updated");
} ?>
<div class="container">
    <div class="d-flex flex-column align-items-center">

        <div class="border bg-light border-primary rounded p-2 shadow">
            <form action="?page=panel&panel=editmenu" method="post">
                <p class="text-center">select a menu or <button class="btn btn-sm btn-outline-primary" type="submit"
                        name="createmenu">create a new menu</button>
                </p>
            </form>

            <?php
            $menuid = $_GET['menuid'] ?? -1;

            //fetch menus
            $sql = "SELECT * FROM menus WHERE restaurantid={$_SESSION['user']['id']}";
            $result = mysqli_query($conn, $sql);
            $menus = mysqli_fetch_all($result, MYSQLI_ASSOC);
            ?>
            <span>
                <select id="menu-select" class="form-select" aria-label="Default select example">
                    <option <?= ($menuid == -1 ? "selected" : "") ?> value="-1">no selection</option>
                    <?php
                    foreach ($menus as $menu) {
                        echo "<option " . ($menuid == $menu['id'] ? "selected" : "") . " value='" . $menu['id'] . "'>" . $menu['name'] . "</option>";
                    }
                    ?>
                </select>
            </span>

        </div>
    </div>
    <hr class="my-2">
    <script>
        //on jquery document load
        $(document).ready(function () {
            $('#menu-select').on('change', function () {
                window.location.href = "?page=panel&panel=editmenu&menuid=" + $(this).val();
            })
        })
    </script>
    <?php
    if ($menuid == -1) {
        ?>
        <p class="text-center">no menu selected</p>
    <?php } else {
        $sql = "SELECT * FROM menus WHERE id={$menuid}";
        $result = mysqli_query($conn, $sql);
        $menu = mysqli_fetch_assoc($result);
        ?>
        <div class="d-flex flex-column align-items-center">
            <div class="border bg-light border-primary rounded p-2 shadow text-center">

                <h3>Edit Menu: <span class="fw-bold fs-4"><?= $menu['name'] ?></span></h3>

                <form action="?page=panel&panel=editmenu&menuid=<?= $menuid ?>" method="post">
                    <input type="hidden" name="menuid" value="<?= $menuid ?>">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Menu Name:</span>
                        <input type="text" class="form-control" placeholder="Menu name" required
                            value="<?= $menu['name'] ?>" name="name">
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="description here" id="floatingTextarea_<?= $menuid ?>"
                            name="description" style="height: 5rem;"><?= $menu['description'] ?></textarea>
                        <label for=" floatingTextarea_<?= $menuid ?>">Description</label>
                    </div>
                    <div class="input-group mb-3 align-items-center justify-content-center">
                        <span class="input-group-text">Visible to customers?</span>
                        <div class="input-group-text">
                            <div class="form-check form-switch">
                                <input name="visible" class="form-check-input" type="checkbox" value="1"
                                    <?= ($menu['visible'] == 1 ? "checked" : "") ?>>
                            </div>
                        </div>

                    </div>
                    <button class="btn btn-success btn-shadow hover-scale" type="submit" name="savemenudetails">Save
                        💾</button>
                </form>

            </div>
        </div>
        <hr class="my-2">
        <?php
        $sql = "SELECT * FROM menufoods WHERE menuid={$menuid}";
        $result = mysqli_query($conn, $sql);
        $selectedFoods = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $sql = "SELECT * FROM foods WHERE restaurantid={$_SESSION['user']['id']}";
        $result = mysqli_query($conn, $sql);
        $foods = mysqli_fetch_all($result, MYSQLI_ASSOC);
        ?>

        <div class="d-flex flex-column align-items-center py-2 pb-5">

            <div class="flex border border-primary rounded bg-light shadow overflow-hidden">
                <p class="text-center text-bg-primary m-0">Select foods:</p>

                <form action="?page=panel&panel=editmenu&menuid=<?= $menuid ?>" method="post">
                    <input type="hidden" name="menuid" value="<?= $menuid ?>">
                    <div class="p-3">
                        <?php
                        foreach ($foods as $food) {
                            $selected = in_array($food['id'], array_column($selectedFoods, 'foodid'));
                            ?>
                            <div class="form-check">
                                <input name="foodids[]" class="form-check-input" type="checkbox" value="<?= $food['id'] ?>"
                                    <?= ($selected ? "checked" : "") ?>>
                                <label class="form-check-label">
                                    <?= $food['id'] ?> - <?= $food['name'] ?> - <?= $food['price'] ?>
                                </label>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <button class="btn btn-success w-100 rounded-0 hover-scale" type="submit"
                        name="savemenufoods">Save💾</button>
                </form>
            </div>
        </div>
        <?php
    } ?>


</div>