<div class="container">
    <!-- a food adding control panel for a restaurant. food's features: Name	Description	RestaurantID	OnlyExtra	Price-->
    <form id="add-food-form">
        <div class="input-group mb-3 shadow">
            <span class="input-group-text">Name:</span>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="input-group mb-3 shadow">
            <span class="input-group-text">Description:</span>
            <textarea name="description" cols="30" rows="10" class="form-control" required></textarea>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" name="onlyExtra" type="checkbox" role="switch" value="false">
            <label class="form-check-label">Only meant to be an Extra. Hide from search
                results.</label>
        </div>
        <div class="input-group mb-3 shadow">
            <span class="input-group-text">Price</span>
            <input type="number" class="form-control" name="price" step="0.01" required>
            <span class="input-group-text">$</span>
        </div>

        <p class="text-center">Your new food will be invisible to everyone. You need to edit it in the <a
                href="?page=panel&panel=editfood" class="text-bg-primary rounded px-1">Edit food</a> to
            make it visible
            after adding it.
        </p>
        <button type="submit" class="w-100 btn btn-lg btn-primary hover-scale shadow">Add Now</button>
    </form>

</div>

<script>
    $(document).ready(function () {
        $('#add-food-form').submit(function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            //checkboxes send "on" or "off" data. we want 0 or 1;
            var isChecked = $('#add-food-form input[name="onlyExtra"]').is(':checked');
            formData += "&onlyExtra=" + (isChecked ? "1" : "0");

            $.ajax({
                url: './api/food/add.php',
                type: 'POST',
                data: formData,
                success: function (response) {
                    console.log(response); // Log the response to the console
                    response = JSON.parse(response); // Parse the response to JSON
                    console.log(response); // Log the response to the console
                    if (response.success) {
                        alert('Food added successfully');
                    } else {
                        alert('food adding failed:\n' + response.error);
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).error : 'Unknown error';
                    console.log(errorMessage); // Log the error message to the console
                    alert('Registration failed:\n' + errorMessage);
                }
            });
        });
    });


</script>