<div class="input-group border-primary shadow">
    <input type="text" class="form-control" placeholder="" id="search-box">
    <button id="search-btn" class="btn btn-lg btn-primary btn-shadow" type="button" id="button-addon2">Search🔎</button>
</div>
<script>
    $(document).ready(function () {

        // Define interval as a global variable
        var interval;

        $('#search-btn').click(function () {
            var keywords = $('#search-box').val();
            $('#search-results-container').empty();
            $.ajax({
                type: "GET",
                url: "./api/food/query.php",
                dataType: 'json',
                data: {
                    keywords: keywords
                },
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        console.log("Food retrieved");
                        var foodsAndRestaurantDetails = response.foodAndRestaurantDetails;
                        if (Array.isArray(foodsAndRestaurantDetails)) {
                            foodsAndRestaurantDetails.forEach(function (foodAndRestaurantDetail) {
                                GenerateSearchResult(foodAndRestaurantDetail);
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
                    console.log("Error:", errorMessage);
                    alert('An error occurred:\n' + errorMessage);
                }
            });
        });

        //placeholder animation
        var placeholders = [
            "😋 Find your favorite dish...",
            "👀 Search for restaurants nearby...",
            "🌍 Discover new flavors...",
            "💦 Looking for something delicious?",
            "🍔 Explore culinary delights..."
        ];

        function animatePlaceholder(placeholder) {
            var index = 0;
            interval = setInterval(function () { // Assign interval to the global variable
                $('#search-box').attr('placeholder', placeholder.slice(0, index));
                index++;
                if (index > placeholder.length) {
                    clearInterval(interval);
                    setTimeout(function () {
                        $('#search-box').attr('placeholder', placeholder);
                        startAnimation();
                    }, 2000);
                }
            }, 50);
        }

        function startAnimation() {
            var randomIndex = Math.floor(Math.random() * placeholders.length);
            var randomPlaceholder = placeholders[randomIndex];

            animatePlaceholder(randomPlaceholder);
        }

        startAnimation();

        // Stop animation when input is focused
        $('#search-box').focus(function () {
            clearInterval(interval); // Clear the animation interval when input is focused
        });
    });
</script>