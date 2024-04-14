<?php


if (isset($_POST['savefood'])) {
    $foodid = $_POST['foodid'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $onlyExtra = isset($_POST['onlyExtra']) ? 1 : 0;
    $visible = isset($_POST['visible']) ? 1 : 0;
    $price = $_POST['price'];

    $sql = "UPDATE foods SET name = '$name', description = '$description', onlyextra = $onlyExtra, visible = $visible, price = $price WHERE id = $foodid";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        alert("Error updating food: " . mysqli_error($conn));
    }

}


?>


<div class="container">
    <form id="search-food-form">
        <div class=" input-group mb-3 shadow">
            <span class="input-group-text">Name:</span>
            <input type="text" name="name" class="form-control" placeholder="Coca col..." required>
            <button class="btn btn-primary btn-shadow hover-scale" type="submit">Search 🔎</button>
        </div>
    </form>

    <!--this is a bit funky. we do flex-wrap to not wrap it because the items are buttons and all fucked up??? idk man-->
    <div id="search-results" class="d-flex rounded border border-primary p-2 flex-wrap align-items-start gap-2"
        style="height:15rem;overflow-x:hidden;overflow-y:auto;">
        <p class="text-center">search a food by its name</p>
    </div>
    <span class="fs-7 text-center text-muted d-block my-4">click a food from above to edit it</span>

    <div id="editing-area" class="d-none rounded border border-primary p-3 py-5">
        <h2 class="text-center get-title">Editing: "Test Burger"</h2>
        <form action="?page=panel&panel=editfood&foodid=-1" method="post">
            <div class="input-group input-group-sm d-none mb-3">
                <span class="input-group-text">ID:</span>
                <input type="text" class="form-control get-foodid" name="foodid" required>
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
            <button type="submit" name="savefood" class="w-100 btn btn-lg btn-primary hover-scale btn-shadow">Save
                Changes</button>
        </form>

        <hr class="m-5">

        <div class="d-flex flex-column justify-content-center align-items-center">
            <h2>Change Picture</h2>
            <form class="text-center">
                <img src="./media/sample.jpg" class="rounded shadow" style="height:15rem;">

                <div class="input-group input-group-sm d-none mb-3">
                    <span class="input-group-text">ID:</span>
                    <input type="text" class="form-control get-foodid" name="foodid" readonly>
                </div>

                <span class="fs-7 text-center text-muted d-block my-2">Uploaded Picture takes immediate change.</span>
                <button class="btn btn-lg btn-primary hover-scale btn-shadow disabled">Upload New Picture</button>
            </form>
        </div>

        <hr class="m-5">

        <div class="d-flex flex-column justify-content-center align-items-center">

            <h2>Categories</h2>
            <form id="category-edit" class="text-center">
                <div class="input-group input-group-sm d-none mb-3">
                    <span class="input-group-text">ID:</span>
                    <input type="text" class="form-control get-foodid" name="foodid" readonly>
                </div>

                <div class="rounded mb-3" style="height:10rem; overflow-x:hidden; overflow-y:auto">
                    <?php
                    $sql = "SELECT * FROM categories";
                    $result = mysqli_query($conn, $sql);
                    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    ?>
                    <?php foreach ($categories as $category) {
                        ?>
                        <div class="form-check a-category">
                            <input data-categoryid="<?= $category['id'] ?>" class="form-check-input category-checkbox"
                                type="checkbox" name="categories[]" value="<?= $category['id'] ?>">
                            <label class="form-check-label">
                                <?= $category['emoji'] ?> -
                                <?= $category['name'] ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
                <button type="submit" class="w-100 btn btn-lg btn-primary hover-scale btn-shadow">Save Categories as
                    is</button>
            </form>
        </div>

        <hr class="m-5">


        <div class="d-flex flex-column justify-content-center align-items-center">
            <a id="extras-btn" href="?page=panel&panel=extras&foodid=-1"
                class="btn btn-lg btn-primary hover-scale btn-shadow">➕ Edit
                Extras and
                Options ➕</a>
        </div>

    </div>

</div>


<script>

    $(document).ready(function () {

        //SEARCH A FOOD ====================================================
        $('#search-food-form').submit(function (e) {
            e.preventDefault();
            var keywords = $("#search-food-form input[name='name']").val();
            //console.log(keywords);
            $.ajax({
                url: './api/food/query.php',
                type: 'GET',
                data: {
                    restaurantid: <?= $_SESSION['user']['id'] ?>,
                    keywords: keywords
                },
                dataType: 'json',
                success: function (response) {
                    $("#search-results").empty();
                    //console.log(response);
                    if (response.success) {
                        var foodAndRestaurantDetails = response.foodAndRestaurantDetails;
                        if (Array.isArray(foodAndRestaurantDetails)) {
                            foodAndRestaurantDetails.forEach(function (data) {
                                $("#search-results").append(`         
                                <button data-foodid="${data.foodid}" class="search-result btn btn-outline-primary hover-scale shadow p-2 w-100">
                                    <div class="d-flex justify-content-start gap-2">
                                        <img src="./media/sample.jpg" class="rounded" style="height:3rem;">
                                        <span>${data.foodid} - ${data.foodname}</span>
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
                    var errorMessage = xhr.responseText ? xhr.responseText : 'Unknown error';
                    console.log(errorMessage); // Log the error message to the console
                    alert('food filter failed:\n' + errorMessage);
                    $("#search-results").empty();
                    $("#search-results").append("<p class='text-center'>there was an error filtering your foods</p>");
                }
            });
        });
        //auto search on load
        $("#search-food-form input[name=name]").val(" ").trigger("input");
        $("#search-food-form").trigger("submit");
        $("#search-food-form input[name=name]").val("");


        //SELECT A FOOD ======================================================
        $("#search-results").on("click", ".search-result", function () {
            var foodid = $(this).data("foodid");
            SelectFood(foodid);
        })
        function SelectFood(foodid) {
            //console.log(foodID);
            $("#extras-btn").attr('href', `?page=panel&panel=extras&foodid=${foodid}`);

            //get food details from ajax
            $.ajax({
                type: "GET",
                url: "./api/food/query.php",
                data: {
                    foodid: foodid
                },
                //TODO: make all calls dataType:json so you dont have to parse the response
                dataType: 'json',
                success: function (response) {
                    //console.log(response);
                    if (response.success) {
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
                    var errorMessage = xhr.responseText ? xhr.responseText : 'Unknown error';
                    console.log("Error:", errorMessage); // Log the error message to the console
                    alert('An error occurred:\n' + errorMessage);
                }
            });
        }
        <?php
        if (isset($_GET['foodid'])) {
            echo "SelectFood(" . $_GET['foodid'] . ")";
        }
        ?>


        //SHOW A FOOD =============================================================
        function ShowFoodEditing(data) {

            $("#editing-area").removeClass("d-none");
            $("#editing-area .get-title").text("Editing: " + data.foodname);

            //find the first form and change its action attribute
            $('#editing-area').find('form').attr('action', `?page=panel&panel=editfood&foodid=${data.foodid}`);
            $(".get-foodid").val(data.foodid);
            $("#editing-area input[name='name']").val(data.foodname);
            $("#editing-area textarea[name='description']").val(data.fooddescription);
            $("#editing-area input[name='price']").val(data.foodprice);

            var onlyExtra = data.foodonlyextra == 1 ? true : false;
            var visible = data.foodvisible == 1 ? true : false;
            $("#editing-area input[name='onlyExtra']").prop("checked", onlyExtra);
            $("#editing-area input[name='visible']").prop("checked", visible);

            fetchFoodCategories(data.foodid); // Fetch and update categories for the current food
        }
        // AJAX request to fetch categories for a specific food
        function fetchFoodCategories(foodid) {
            $.ajax({
                type: 'GET',
                url: './api/food/getcategories.php',
                data: {
                    foodid: foodid
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        var categories = response.categories.map(category => category.id);
                        updateCategoryCheckboxes(categories);
                    } else {
                        console.error('Failed to retrieve categories:', response.error);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching categories:', error);
                }
            });
        }
        function updateCategoryCheckboxes(categories) {
            //console.log(categories);
            $('.a-category').each(function () {
                var checkbox = $(this).find('.category-checkbox');
                if (checkbox.length > 0) {
                    //categories is an array of strings
                    var categoryID = "" + checkbox.data('categoryid');
                    var isChecked = categories.includes(categoryID);
                    //console.log(categoryID, isChecked);
                    checkbox.prop('checked', isChecked);
                } else {
                    console.error("No checkbox found inside .a-category element");
                }
            });
        }

        //CATEGORY EDIT =============================================================
        $("#category-edit").submit(function (e) {
            e.preventDefault();
            var categoryCheckboxes = $('#category-edit .category-checkbox');
            var categoryValues = [];

            categoryCheckboxes.each(function () {
                if ($(this).prop('checked')) {
                    var categoryID = $(this).data('categoryid');
                    categoryValues.push(categoryID);
                }
            });

            var foodID = $(".get-foodid").val();
            console.log(categoryValues);

            $.ajax({
                type: "POST",
                url: "./api/food/setcategories.php",
                data: {
                    foodid: foodID,
                    categories: categoryValues
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        console.log("Food categories updated");
                        alert("categories updated");
                    } else {
                        console.log("Error:", response.error);
                        alert("something went wrong");
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.responseText ? xhr.responseText : 'Unknown error';
                    console.error("Error:", errorMessage); // Log the error message to the console
                    alert('An error occurred:\n' + errorMessage);
                }
            });
        })


    });


</script>