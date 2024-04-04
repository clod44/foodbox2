<div class="container">
    <form id="search-food-form">
        <div class="input-group mb-3 shadow">
            <span class="input-group-text">Name:</span>
            <input type="text" name="name" class="form-control" placeholder="Coca col..." required>
            <button class="btn btn-primary btn-shadow hover-scale" type="submit">Search 🔎</button>
        </div>
    </form>
    <script>

        $(document).ready(function () {
            $('#search-food-form').submit(function (e) {
                e.preventDefault();
                var keywords = $("#search-food-form input[name='name']").val();
                console.log(keywords);
                $.ajax({
                    url: './api/food/query.php',
                    type: 'GET',
                    data: {
                        restaurantID: <?= $_SESSION['user']['ID'] ?>,
                        keywords: keywords
                    },
                    success: function (response) {
                        $("#search-results").empty();
                        console.log(response);
                        var response = JSON.parse(response);
                        console.log(response);
                        if (response.success) {
                            console.log("Food retrieved");
                            //console.log(response);
                            var foodAndRestaurantDetails = response.foodAndRestaurantDetails;
                            if (Array.isArray(foodAndRestaurantDetails)) {
                                foodAndRestaurantDetails.forEach(function (data) {
                                    $("#search-results").append(`         
                                        <button data-foodID="${data.FoodID}" class="search-result btn btn-outline-primary hover-scale shadow p-2 w-100">
                                            <div class="d-flex justify-content-start gap-2">
                                                <img src="./media/sample.jpg" class="rounded" style="height:3rem;">
                                                <span>${data.FoodID} - ${data.FoodName}</span>
                                            </div>
                                        </button>
                                    `);

                                });
                            } else {
                                $("#search-results").append("<p class='text-center'>there was an error filtering your foods</p>");
                                console.log("foodsAndRestaurantDetails is not an array:", foodAndRestaurantDetails);
                            }
                        } else {
                            $("#search-results").append("<p class='text-center'>there was an error filtering your foods</p>");
                            console.log("Error:", response.error);
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).error : 'Unknown error';
                        console.log(errorMessage); // Log the error message to the console
                        alert('food filter failed:\n' + errorMessage);
                        $("#search-results").empty();
                        $("#search-results").append("<p class='text-center'>there was an error filtering your foods</p>");
                    }
                });
            });

            $("#search-results").on("click", ".search-result", function () {
                var foodID = $(this).data("foodID");
                console.log(foodID);

                //get food details from ajax
                $.ajax({
                    type: "GET",
                    url: "./api/food/query.php",
                    data: {
                        foodID: foodID
                    },
                    dataType: 'json', // Specify JSON dataType to automatically parse response as JSON
                    success: function (response) {
                        console.log(response);
                        if (response.success) {
                            console.log("Food retrieved");
                            //console.log(response);
                            var foodsAndRestaurantDetails = response.foodAndRestaurantDetails;
                            if (Array.isArray(foodsAndRestaurantDetails)) {
                                foodsAndRestaurantDetails.forEach(function (foodAndRestaurantDetail) {
                                    //console.log(foodAndRestaurantDetail); // Log each element to understand its structure
                                    ShowFoodEditing(foodAndRestaurantDetail); // Call GenerateSearchResult function
                                });
                            } else {
                                console.log("foodsAndRestaurantDetails is not an array:", foodsAndRestaurantDetails);
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
            })

            function ShowFoodEditing(foodAndRestaurantDetails) {
                $("#editing-area").removeClass("d-none");
                $("#editing-area .get-title").text("Editing: " + foodAndRestaurantDetails.FoodName);

                $(".get-foodID").val(foodAndRestaurantDetails.FoodID);
                $("#editing-area input[name='name']").val(foodAndRestaurantDetails.FoodName);
                $("#editing-area textarea[name='description']").val(foodAndRestaurantDetails.FoodDescription);
                $("#editing-area input[name='price']").val(foodAndRestaurantDetails.FoodPrice);
                //$("#editing-area input[name='picture']").val(foodAndRestaurantDetails.Picture);

                // Update checkbox states
                var onlyExtra = foodAndRestaurantDetails.FoodOnlyExtra == 1 ? true : false;
                var visible = foodAndRestaurantDetails.FoodVisible == 1 ? true : false;
                $("#editing-area input[name='onlyExtra']").prop("checked", onlyExtra);
                $("#editing-area input[name='visible']").prop("checked", visible);
            }


            $("#category-edit").submit(function (e) {
                e.preventDefault();
                var categoryCheckboxes = $('#category-edit .category-checkbox');
                var categoryValues = [];

                categoryCheckboxes.each(function () {
                    if ($(this).prop('checked')) {
                        var categoryID = $(this).data('categoryID');
                        categoryValues.push(categoryID);
                    }
                });

                var foodID = $(".get-foodID").val();
                console.log(categoryValues);



                //get food details from ajax
                $.ajax({
                    type: "GET",
                    url: "./api/food/setcategories.php",
                    data: {
                        foodID: 1,
                        categories: categoryValues
                    },
                    dataType: 'json', // Specify JSON dataType to automatically parse response as JSON
                    success: function (response) {
                        console.log(response);
                        if (response.success) {
                            console.log("Food retrieved");
                            //console.log(response);
                            var foodsAndRestaurantDetails = response.foodAndRestaurantDetails;
                            if (Array.isArray(foodsAndRestaurantDetails)) {
                                foodsAndRestaurantDetails.forEach(function (foodAndRestaurantDetail) {
                                    //console.log(foodAndRestaurantDetail); // Log each element to understand its structure
                                    ShowFoodEditing(foodAndRestaurantDetail); // Call GenerateSearchResult function
                                });
                            } else {
                                console.log("foodsAndRestaurantDetails is not an array:", foodsAndRestaurantDetails);
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
            })
        });
    </script>


    <div id="search-results" class="d-flex rounded border border-primary p-2 flex-wrap align-items-start gap-2"
        style="height:15rem;overflow-x:hidden;overflow-y:auto;">
        <p class="text-center">search a food by its name</p>
    </div>
    <span class="fs-7 text-center text-muted d-block my-4">click a food from above to edit it</span>
    <div id="editing-area" class="d-none rounded border border-primary p-3 py-5">
        <h2 class="text-center get-title">Editing: "Test Burger"</h2>
        <form>
            <div class="input-group input-group-sm d-none mb-3">
                <span class="input-group-text">ID:</span>
                <input type="text" class="form-control get-foodID" name="foodID" required>
            </div>
            <div class="input-group mb-3 shadow">
                <span class="input-group-text">Name:</span>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="input-group mb-3 shadow">
                <span class="input-group-text">Description</span>
                <textarea class="form-control" name="description" required></textarea>
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" name="onlyExtra" type="checkbox" role="switch">
                <label class="form-check-label fw-bold">Only meant to be an Extra. Hide from search
                    results.</label>
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" name="visible" type="checkbox" role="switch">
                <label class="form-check-label fw-bold">Hide this food from everyone.</label>
            </div>
            <div class="input-group mb-3 shadow">
                <span class="input-group-text">Price</span>
                <input type="number" class="form-control" name="price" step="0.01" required>
                <span class="input-group-text">$</span>
            </div>
            <button type="submit" class="w-100 btn btn-lg btn-primary hover-scale btn-shadow">Save Changes</button>
        </form>

        <hr class="m-5">

        <div class="d-flex flex-column justify-content-center align-items-center">
            <h2>Change Picture</h2>
            <form class="text-center">
                <img src="./media/sample.jpg" class="rounded shadow" style="height:15rem;">

                <div class="input-group input-group-sm d-none mb-3">
                    <span class="input-group-text">ID:</span>
                    <input type="text" class="form-control get-foodID" name="foodID" readonly>
                </div>

                <span class="fs-7 text-center text-muted d-block my-2">Uploaded Picture takes immediate change.</span>
                <button class="btn btn-lg btn-primary hover-scale btn-shadow">Upload New Picture</button>
            </form>
        </div>


        <hr class="m-5">


        <div class="d-flex flex-column justify-content-center align-items-center">

            <h2>Categories</h2>
            <form id="category-edit" class="text-center">
                <div class="input-group input-group-sm d-none mb-3">
                    <span class="input-group-text">ID:</span>
                    <input type="text" class="form-control get-foodID" name="foodID" readonly>
                </div>

                <div class="rounded mb-3" style="height:10rem; overflow-x:hidden; overflow-y:auto">
                    <?php
                    $sql = "SELECT * FROM categories";
                    $result = mysqli_query($conn, $sql);
                    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    ?>
                    <?php foreach ($categories as $category):
                        //find the amount of foods with this category
                        $sql = "SELECT * FROM foodcategories WHERE CategoryID={$category['ID']}";
                        $result = mysqli_query($conn, $sql);
                        $categoryContentCount = mysqli_num_rows($result);
                        //if ($categoryContentCount < 1)
                        //    continue;
                        ?>
                        <div class="form-check">
                            <input data-categoryID="<?= $category['ID'] ?>" class="form-check-input category-checkbox"
                                type="checkbox" name="categories[]" value="<?= $category['ID'] ?>">
                            <label class="form-check-label">
                                <?= $category['Emoji'] ?> -
                                <?= $category['Name'] ?>
                                <span class="fs-7 text-muted">
                                    <?= "(" . $categoryContentCount . ")" ?>
                                </span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="w-100 btn btn-lg btn-primary hover-scale btn-shadow">Save Categories as
                    is</button>
            </form>
        </div>

        <hr class="m-5">


        <div class="d-flex flex-column justify-content-center align-items-center">
            <a href="?page=panel&panel=extras&foodID=12" class="btn btn-lg btn-primary hover-scale btn-shadow">➕ Edit
                Extras and
                Options ➕</a>
        </div>

    </div>
</div>