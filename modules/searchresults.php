<div class="d-flex flex-column align-items-center gap-4">

    <div id="search-results-container" class="d-flex flex-wrap gap-3 justify-content-center align-items-start">

    </div>

    <nav aria-label="...">
        <ul class="pagination">
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
            <li class="page-item active" aria-current="page">
                <span class="page-link">1</span>
            </li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul>
    </nav>

    <script>

        $(document).ready(function () {
            $.ajax({
                type: "GET",
                url: "./api/food/query.php",
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