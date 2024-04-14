function GenerateSearchResult(food) {

    let restaurantCallback = function (restaurant) {
        var searchResutHtml = `
        <div class='card border-primary shadow hover-scale' style='width: 18rem;'>
            <a href='?page=restaurant&restaurantid=${restaurant['id']}&selectedfoodid=${food['id']}' class='card-body p-0'
                style='display: block; margin: -2px;'>
            <img src='${GET_IMAGE(food['image'])}' class='card-img-top w-100' style = 'object-fit: cover; height: 7rem;' >
            <div class='p-2'>
                <div class='d-flex justify-content-between'>
                    <h5 class='card-title m-0 p-0 fw-bold'>
                        ${food['name']}
                    </h5>
                    <!--TODO:implement food rating-->
                    <h5 class='card-title m-0 p-0'>4.9⭐</h5>
                </div>
                <hr class='p-0 m-0 border-primary'>
                    <p class='card-text fs-7 p-0 m-0' style='height:3.2em;overflow-y:hidden;'>
                        ${food['description']}
                    </p>
                    <div class='d-flex justify-content-between align-items-center m-0 p-0'>
                        <span class='badge text-bg-warning fw-bold m-0'>
                            $
                            ${parseFloat(food['price']).toFixed(2)}
                        </span>
                        <a href='?page=restaurant&restaurantid=${restaurant['id']}' class='fs-7 p-0 px-2 m-0 text-end fw-bold'>- ${restaurant['name']}</a>
            </div>
                </div >
            </a >
        </div >
        `;
        $('#search-results-container').append(searchResutHtml); // Append the generated HTML to the search-result-container
    };

    GetRestaurant(food['id'], restaurantCallback);
}
function GetRestaurant(foodid, callback) {
    $.ajax({
        type: "GET",
        url: "./api/food/getrestaurant.php",
        data: {
            foodid: foodid
        },
        dataType: 'json',
        success: function (response) {
            console.log(response);
            if (response.success) {
                callback(response.restaurant);
            } else {
                console.log("Error:", response.error);
            }
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).error : 'Unknown error';
            console.log(errorMessage); // Log the error message to the console
            alert('query failed:\n' + errorMessage);
        }
    });
}

function ClearSearchResults() {
    $('#search-results-container').empty();
}
