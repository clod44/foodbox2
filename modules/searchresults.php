<div class="d-flex flex-wrap gap-3 justify-content-center align-items-start">
    <?php
    for ($i = 0; $i < 30; $i++) {
        ?>
        <div class="card border-primary shadow" style="width: 18rem;">
            <img src="./media/sample.jpg" class="card-img-top w-100" style="object-fit:cover; height:7rem;">
            <a href="#" class="card-body p-2">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title m-0 p-0 text-primary fw-bold">Card title</h5>
                    <h5 class="card-title m-0 p-0">4.9⭐</h5>
                </div>
                <hr class="p-0 m-0 border-primary">
                <p class="card-text fs-7 p-0 m-0">Some quick example text to build on the card title and make up the
                    bulk of</p>
                <p class="fs-7 p-0 m-0 text-end fw-bold">- Abraham Döner</p>
            </a>
        </div>

        <?php
    }
    ?>
</div>