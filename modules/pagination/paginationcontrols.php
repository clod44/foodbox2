<?php
/* WIP?>
<nav>
    <form action="" method="get">
        <?php
        $p = $_SESSION['pagination']['page'] + 1; //starts from 1 now
        $disabled = $p < 2 ? 'disabled' : '';
        $perpage = $_SESSION['pagination']['perpage'];
        ?>
        <div class="d-flex gap-1 flex-wrap align-items-center justify-content-center">
            <button class="btn btn-sm btn-outline-primary <?= $disabled ?>" name="p" type="submit"
                value="<?= $p - 2 ?>">Previous</button>
            <?php
            if ($p - 2 > 0) {
                ?>
                <button class="btn btn-sm btn-outline-primary <?= $disabled ?>" name="p" type="submit"
                    value="<?= $p - 3 ?>"><?= $p - 2 ?></button>
                <?php
            }
            if ($p - 1 > 0) {
                ?>
                <button class="btn btn-sm btn-outline-primary <?= $disabled ?>" name="p" type="submit"
                    value="<?= $p - 2 ?>"><?= $p - 1 ?></button>
                <?php
            }
            ?>
            <button class="btn btn-sm btn-primary" name="p" type="submit" value="<?= $p - 1 ?>"><?= $p ?></button>
            <button class="btn btn-sm btn-outline-primary" name="p" type="submit"
                value="<?= $p ?>"><?= $p + 1 ?></button>
            <button class="btn btn-sm btn-outline-primary" name="p" type="submit"
                value="<?= $p + 1 ?>"><?= $p + 2 ?></button>
            <button class="btn btn-sm btn-outline-primary" name="p" type="submit" value="<?= $p ?>">Next</button>
        </div>
        <hr class="my-2">
        <div class="d-flex gap-1 flex-wrap align-items-center justify-content-center">
            <button class="btn btn-sm btn<?= $perpage == 5 ? "" : '-outline' ?>-primary" name="perpage" type="submit"
                value="5">5</button>
            <button class="btn btn-sm btn<?= $perpage == 10 ? "" : '-outline' ?>-primary" name="perpage" type="submit"
                value="10">10</button>
            <button class="btn btn-sm btn<?= $perpage == 20 ? "" : '-outline' ?>-primary" name="perpage" type="submit"
                value="20">20</button>
            <button class="btn btn-sm btn<?= $perpage == 50 ? "" : '-outline' ?>-primary" name="perpage" type="submit"
                value="50">50</button>
        </div>
    </form>
</nav>
<?php */ ?>