<?php
require "./modules/pagination/paginationhead.php";
?>

<div class="container p-3">
    <div class="row">
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="d-sm-none">
                <button class="btn btn-primary w-100 p-0 col-span-12" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters">
                    Filters
                </button>
            </div>

            <div class="collapse d-sm-block" id="collapseFilters">
                <?php require "./modules/filters.php"; ?>
            </div>
        </div>
        <div class="col col-sm-6 col-md-8">
            <?php require "./modules/search.php"; ?>
            <br>
            <?php require "./modules/searchresults.php"; ?>
        </div>
    </div>
</div>