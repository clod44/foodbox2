<div class="p-3 border border-primary rounded shadow">

    <div class="">
        <h5>Price Range</h5>
        <div class="input-group input-group-sm mb-3 flex-nowrap">
            <span class="input-group-text">$</span>
            <input type="text" class="form-control" id="price-min" placeholder="Min">
            <span class="input-group-text">to</span>
            <input type="text" class="form-control" id="price-max" placeholder="Max">
        </div>
    </div>
    <h5>Rating</h5>

    <div class="input-group input-group-sm mb-3 flex-nowrap align-items-center ">
        <span id="score-label" class="input-group-text">1⭐</span>
        <input type="range" class="ms-2 form-range" min="1" max="5" id="score">
    </div>

    <h5 id="categories-title">Categories</h5>
    <div class="rounded mb-3" style="height:10rem; overflow-x:hidden; overflow-y:auto">
        <?php
        $sql = "SELECT * FROM categories";
        $result = mysqli_query($conn, $sql);
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        ?>
        <?php foreach ($categories as $category): ?>
            <div class="form-check">
                <input class="form-check-input category-checkbox" type="checkbox" value="<?= $category['id'] ?>">
                <label class="form-check-label">
                    <?= $category['emoji'] ?> -
                    <?= $category['name'] ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="form-check form-switch mb-3 flex-nowrap">
        <input class="form-check-input" type="checkbox" value="1" id="favorites">
        <label class="form-check-label" for="favorites">
            Favorited Only💖
        </label>
    </div>

    <div class="input-group mb-3 flex-nowrap">
        <select class="form-select form-select-sm text-center" id="inputGroupSelect01">
            <option selected value="1">Sort</option>
            <option value="1">Rating</option>
            <option value="2">Price</option>
        </select>

        <select class="form-select form-select-sm text-center" id="inputGroupSelect01">
            <option value="1">⬆️</option>
            <option selected value="2">⬇️</option>
        </select>
    </div>
    <button type="submit" class="btn btn-lg btn-primary btn-shadow w-100">Search🔎</button>

</div>


<script>
    // jQuery code to update slider label value
    $(document).ready(function () {
        $('#score').on('input', function () {
            $('#score-label').text($(this).val() + '⭐');
        });


        // Add event handler for checkbox change
        $('.category-checkbox').change(function () {
            var count = $('.category-checkbox:checked').length;
            $('#categories-title').text('Categories' + (count > 0 ? (' (' + count + '☑️)') : ''));
        });

    });
</script>