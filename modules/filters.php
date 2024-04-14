<div class="p-3 border border-primary rounded shadow">

    <form id="filter-form">
        <div class="">
            <h5>Price Range</h5>
            <div class="input-group input-group-sm mb-3 flex-nowrap">
                <span class="input-group-text">$</span>
                <input type="text" class="form-control" id="price-min" name="price-min" placeholder="Min" value="0">
                <span class="input-group-text">to</span>
                <input type="text" class="form-control" id="price-max" name="price-max" placeholder="Max" value="99">
            </div>
        </div>
        <h5>Rating</h5>

        <div class="input-group input-group-sm mb-3 flex-nowrap align-items-center ">
            <span id="score-label" class="input-group-text">1⭐</span>
            <input type="range" class="ms-2 form-range" min="1" max="5" value="1" id="score" name="score-min">
        </div>

        <h5 id="categories-title">Categories</h5>
        <div class="rounded mb-3" style="height:10rem; overflow-x:hidden; overflow-y:auto">
            <?php
            $sql = "SELECT * FROM categories";
            $result = mysqli_query($conn, $sql);
            $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
            ?>
            <?php foreach ($categories as $category):
                //find the amount of foods with this category
                $sql = "SELECT * FROM foodcategories WHERE categoryid={$category['id']}";
                $result = mysqli_query($conn, $sql);
                $categoryContentCount = mysqli_num_rows($result);
                //if ($categoryContentCount < 1)
                //    continue;
                ?>
                <div class="form-check">
                    <input class="form-check-input category-checkbox" type="checkbox" name="categories[]"
                        value="<?= $category['id'] ?>">
                    <label class="form-check-label">
                        <?= $category['emoji'] ?> -
                        <?= $category['name'] ?>
                        <span class="fs-7 text-muted">
                            <?= "(" . $categoryContentCount . ")" ?>
                        </span>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="form-check form-switch mb-3 flex-nowrap">
            <input class="form-check-input" type="checkbox" value="1" id="favorites" name="favorites-only">
            <label class="form-check-label" for="favorites">
                Favorited Only💖
            </label>
        </div>

        <div class="input-group mb-3 flex-nowrap">
            <select class="form-select form-select-sm text-center" name="sort-type">
                <option selected value="price">Price</option>
                <option value="rating">Rating</option>
            </select>

            <select class="form-select form-select-sm text-center" name="sort-dir">
                <option value="DESC">⬆️</option>
                <option selected value="ASC">⬇️</option>
            </select>
        </div>
        <button type="submit" class="btn btn-lg btn-primary btn-shadow w-100">Search🔎</button>
    </form>
</div>


<script>
    $(document).ready(function () {
        $('#score').on('input', function () {
            $('#score-label').text($(this).val() + '⭐');
        });

        $('.category-checkbox').change(function () {
            var count = $('.category-checkbox:checked').length;
            $('#categories-title').text('Categories' + (count > 0 ? (' (' + count + '☑️)') : ''));
        });

        $("#filter-form").submit(function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            ClearSearchResults();
            $.ajax({
                type: "GET",
                url: "./api/food/query.php",
                data: formData,
                dataType: 'json', // Specify JSON dataType to automatically parse response as JSON
                success: function (response) {
                    console.log(response.echo)
                    if (response.success) {

                        let foods = response.foods;
                        if (Array.isArray(foods)) {
                            foods.forEach(function (food) {
                                GenerateSearchResult(food);
                            });
                        }
                    } else {
                        console.log(response.error); // Log the error message to the console
                        alert('filter failed:\n' + response.error);
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.responseText ? xhr.responseText : 'Unknown error';
                    console.log(errorMessage); // Log the error message to the console
                    alert('filter failed:\n' + errorMessage);
                }
            });
        });

    });

</script>