<?php

$sql = "SELECT * FROM foods WHERE only_extra = 0 AND active = 1";
$result = mysqli_query($conn, $sql);
$foods = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div class="d-flex flex-column align-items-center gap-4">

    <div class="d-flex flex-wrap gap-3 justify-content-center align-items-start">
        <?php
        foreach ($foods as $food) {
            ?>
            <div class="card border-primary shadow hover-scale" style="width: 18rem;">
                <a href="?page=restaurant&restaurantID=1&selectedProductID=2" class="card-body p-0"
                    style="display: block; margin: -2px;">
                    <img src="./media/sample.jpg" class="card-img-top w-100" style="object-fit: cover; height: 7rem;">
                    <div class="p-2">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title m-0 p-0 fw-bold">
                                <?= $food['name'] ?>
                            </h5>
                            <h5 class="card-title m-0 p-0">4.9⭐</h5>
                        </div>
                        <hr class="p-0 m-0 border-primary">
                        <p class="card-text fs-7 p-0 m-0" style="height:3.2em;overflow-y:hidden;">
                            <?= $food['description'] ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center m-0 p-0">
                            <span class="badge text-bg-warning fw-bold m-0">
                                $
                                <?= $food['price'] ?>
                            </span>
                            <a href="?page=restaurant&restaurantID=1" class="fs-7 p-0 px-2 m-0 text-end fw-bold">- Abraham
                                Döner</a>
                        </div>
                    </div>
                </a>
            </div>


            <?php
        }
        ?>
    </div>

    <nav aria-label="...">
        <ul class="pagination">
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
            <li class="page-item active" aria-current="page">
                <span class="page-link">1</span>
            </li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul>
    </nav>
</div>