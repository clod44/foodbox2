<div class="input-group border-primary shadow">
    <input type="text" class="form-control" placeholder="" id="search-box">
    <button class="btn btn-lg btn-primary btn-shadow" type="button" id="button-addon2">Search🔎</button>
</div>

<script>
    $(document).ready(function () {
        var placeholders = [
            "😋 Find your favorite dish...",
            "👀 Search for restaurants nearby...",
            "🌍 Discover new flavors...",
            "💦 Looking for something delicious?",
            "🍔 Explore culinary delights..."
        ];

        function animatePlaceholder(placeholder) {
            var index = 0;
            var interval = setInterval(function () {
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