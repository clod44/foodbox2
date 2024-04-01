function GenerateSearchResult(data) {
    var searchResutHtml = `
    <div class='card border-primary shadow hover-scale' style='width: 18rem;'>
        <a href='?page=restaurant&restaurantID=${data['restaurant_id']}&selectedFoodID=${data['food_id']}' class='card-body p-0'
            style='display: block; margin: -2px;'>
        <img src='./media/sample.jpg' class='card-img-top w-100' style='object-fit: cover; height: 7rem;'>
            <div class='p-2'>
                <div class='d-flex justify-content-between'>
                    <h5 class='card-title m-0 p-0 fw-bold'>
                        ${data['food_name']}
                    </h5>
                    <!--TODO:implement food rating-->
                    <h5 class='card-title m-0 p-0'>4.9⭐</h5>
                </div>
                <hr class='p-0 m-0 border-primary'>
                <p class='card-text fs-7 p-0 m-0' style='height:3.2em;overflow-y:hidden;'>
                    ${data['food_description']}
                </p>
                <div class='d-flex justify-content-between align-items-center m-0 p-0'>
                    <span class='badge text-bg-warning fw-bold m-0'>
                        $
                        ${data['food_price']}
                    </span>
                    <a href='?page=restaurant&restaurantID=${data['restaurant_id']}' class='fs-7 p-0 px-2 m-0 text-end fw-bold'>- ${data['restaurant_name']}</a>
                </div>
            </div>
        </a>
    </div>
    `;
    $('#search-results-container').append(searchResutHtml); // Append the generated HTML to the search-result-container
}

function ClearSearchResults() {
    $('#search-results-container').empty();
}
