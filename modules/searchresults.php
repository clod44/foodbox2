<div class="d-flex flex-column align-items-center gap-4">

    <div id="search-results-container" class="d-flex flex-wrap gap-3 justify-content-center align-items-start">

    </div>

    <?php
    require "./modules/pagination/paginationcontrols.php";
    ?>

    <script>

        $(document).ready(function () {
            //initial load
            $.ajax({
                type: "GET",
                url: "./api/food/query.php",
                data: {
                    page: <?= $_SESSION['pagination']['page'] ?>,
                    perpage: <?= $_SESSION['pagination']['perpage'] ?>
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        let foods = response.foods;
                        if (Array.isArray(foods)) {
                            foods.forEach(function (food) {
                                GenerateSearchResult(food);
                            });
                        }
                    } else {
                        console.log("Error:", response.error);
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).error : 'Unknown error';
                    console.log("Error:", errorMessage); // Log the error message to the console
                    alert('An error occurred:\n' + errorMessage);
                }
            });
        });
    </script>
</div>