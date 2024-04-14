<?php
if (!isset($_GET['restaurantid'])) {
    echo "there was an error loading restaurant products info";
    exit;
}
$restaurantid = $_GET['restaurantid'];
//fetch restaurant info
$sql = "SELECT * FROM restaurants WHERE id=$restaurantid";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    echo "restaurant not found";
    exit;
}
$restaurant = mysqli_fetch_assoc($result);

//optional
$selectedfoodid = $_GET['selectedfoodid'] ?? null;
?>


<div class="container p-4">
    <div class="px-3 d-flex gap-5">
        <img src="./media/sample.jpg" class="rounded shadow" style="height:10rem;">
        <div class="h-100 flex-grow-1">
            <div class="d-flex justify-content-between align-items-center flex-nowrap">
                <h2 class="fw-bold text-primary m-0">
                    <?= $restaurant['name'] ?>
                </h2>
                <!--favorite button-->

                <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                    <input type="checkbox" class="btn-check" id="btncheck1" autocomplete="off">
                    <label class="btn btn-outline-primary pb-1" for="btncheck1"><i class="bi bi-heart fs-3"></i></label>
                </div>

            </div>
            <p class="lead fs-6">
                <?= $restaurant['description'] ?>
            </p>
            <p class="fw-bold">4.2/5⭐(+3000)</p>
        </div>
    </div>

    <!--restaurant menus-->
    <ul class="nav nav-tabs mb-3 sticky-top bg-light" role="tablist">
        <!--menus-->
        <?php
        for ($i = 0; $i < 5; $i++) { ?>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#menu_<?= $i ?>">Menu
                    <?= $i ?>
                </a>
            </li>
            <?php
        } ?>
    </ul>

    <div class="row">
        <!--restaurat menu content-->
        <div class="col d-flex flex-column gap-3">
            <?php
            for ($i = 0; $i < 5; $i++) { ?>
                <div id="menu_<?= $i ?>">
                    <h3 class="mb-3">🍔 Menu
                        <?= $i ?>
                    </h3>
                    <div class="row align-items-start justify-content-around">
                        <?php
                        //for now, keep fetching all the foods
                        $sql = "SELECT * FROM foods WHERE restaurantid=$restaurantid AND onlyextra=0";
                        $result = mysqli_query($conn, $sql);
                        $foods = mysqli_fetch_all($result, MYSQLI_ASSOC);
                        foreach ($foods as $food) {
                            ?>
                            <div class="col-12 col-md-6 p-2">
                                <!--clickable-->
                                <div data-food-id="<?= $food['id'] ?>"
                                    class="food hover-scale m-0 p-3 border border-primary rounded shadow overflow-hidden"
                                    style="cursor:pointer;">
                                    <div class="d-flex align-items-center justify-content-between w-100 h-100 m-0 p-0">
                                        <div class="d-flex flex-column align-items-start justify-content-between m-0 p-0">
                                            <p class="m-0 p-0 fw-bold lead">
                                                <?= $food['name'] ?>
                                            </p>
                                            <p class="text-break m-0 p-0 fs-7 small">
                                                <?= $food['description'] ?>
                                            </p>
                                            <span class="badge text-bg-warning fw-bold">
                                                <?= $food['price'] ?>$
                                            </span>
                                        </div>
                                        <img src="./media/sample.jpg" class="rounded" style="width:5rem;">
                                    </div>

                                </div>
                            </div>

                        <?php } ?>
                    </div>
                </div>
                <hr class="border-primary">
                <?php
            } ?>

        </div>
        <!--your food box-->
        <div class="col-4 m-0 p-0">
            <div class="w-100 border border-primary rounded shadow p-4">
                <h3 class="m-0 p-0 text-center mb-2">📦 Your Box 📦</h3>
                <hr class="p-0 m-2 border-primary">
                <div class="rounded w-100 mb-2" style="height:20rem;overflow-x:hidden; overflow-y:auto;">
                    <?php
                    for ($i = 0; $i < 3; $i++) {
                        ?>
                        <div class="d-flex flex-nowrap p-1 gap-2 align-items-start">
                            <img src="./media/sample.jpg" class="rounded shadow" style="height:3rem;">
                            <div class="d-flex flex-column flex-grow-1"> <!-- Added flex-grow-1 class -->
                                <div class="d-flex justify-content-between align-items-start">
                                    <p class="m-0 p-0 fs-6 fw-bold">McChicken RedFlaming</p>
                                    <button class="btn btn-lg btn-outline m-0 p-0 fs-2">⚙️</button>
                                </div>
                                <ul class="m-0 mb-2">
                                    <?php
                                    for ($j = 0; $j < 3; $j++) {
                                        ?>
                                        <li>
                                            <p class="fs-7 m-0">Something extra heooooooo</p>
                                        </li>
                                    <?php } ?>
                                </ul>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text" id="basic-addon1">$69.00 x</span>
                                    <input type="number" class="form-control" placeholder="amount" value="1">
                                </div>
                            </div>
                        </div>
                        <hr class="border-primary m-0 p-0 my-2">
                    <?php } ?>
                </div>
                <div class="d-flex flex-nowrap justify-content-between align-items-center mb-3">
                    <p class="m-0 p-0">Total:</p>
                    <p class="m-0 p-0 fw-bold">$132.00</p>
                </div>
                <button class="btn btn-lg w-100 btn-primary btn-shadow">
                    Confirm the Box ✅
                </button>
            </div>
        </div>
    </div>

</div>



<script>

    $(document).ready(function () {
        // Event listener for the button to trigger dynamic modal
        $('.food').click(function () {
            var foodid = $(this).data('food-id');
            ShowFoodModalFromID(foodid);
        });

        function ShowFoodModalFromID(foodid) {

            var food = null;
            //get food info from endpoint
            $.ajax({
                type: "GET",
                url: "./api/food/query.php",
                data: {
                    foodid: foodid
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
                                ShowFoodModalFromFood(foodAndRestaurantDetail);
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
                    console.log(errorMessage); // Log the error message to the console
                    alert('query failed:\n' + errorMessage);
                }
            });
        }

        function ShowFoodModalFromFood(data) { //foodAndRestaurantDetail
            $.ajax({
                type: "GET",
                url: "./api/food/getquestions.php",
                data: {
                    foodid: data['foodid']
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        console.log("Questions retrieved");
                        CreateFoodModalFromAllData(data, response.questions, response.answers);
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
        function CreateFoodModalFromAllData(data, questions, answers) {
            //data is foodAndRestaurantDetail
            const modalData = [
                new ModalImage("./media/sample.jpg"),
                new ModalTitle(data['foodname']),
                new ModalLabel(data['fooddescription']),
                /*
                new ModalInput("biggest wish:", "mywish1"),
                new ModalInput("best wish:", "mywish2"),
                new ModalTitle("Drink?:"),
                new ModalRadio("CocaCola", "drink", 1, 10, true),
                new ModalRadio("Pepsi", "drink", 2, 15, true),
                new ModalTitle("Extras?:"),
                new ModalCheckbox("Tomato", "extras", "tomato", 12),
                new ModalCheckbox("Salad", "extras", "salad", 0),
                new ModalCheckbox("Melon", "extras", "melon", 5),
                new ModalTitle("Final:"),
                new ModalCheckbox("Agree to terms", "terms", "1", 0),
            */
            ];

            questions.forEach(function (question, index, array) {
                modalData.push(new ModalTitle(question['title']));
                modalData.push(new ModalLabel(question['text']));
                let type = question['type'];
                console.log(answers);
                /*
                I keep forgeting so here is a reminder.
                "answers" is an object containing few arrays that contains multiple answer datas, keyed by the questionid's
                answers = {
                    "1": [
                        {id: 1, text: "somethnig", price: 10}, //answers of questionid=1
                        {id: 2, text: "somethnig", price: 10} 
                    ]
                }
                */
                answers[question['id']].forEach(function (answer, index, array) {
                    switch (type) {
                        case "1": //checkbox
                            modalData.push(new ModalCheckbox(answer['text'], "answers_" + answer['questionid'] + "[]", "something", answer['price']));
                            break;
                        case "2": //radio
                            modalData.push(new ModalRadio(answer['text'], "drink", 1, answer['price'], true));
                            break;
                        /*
                        //we will screw off the text input as it requires db modification and i dont really care this tbh
                        case "3": //input
                            modalData.push(new ModalRadio(answer['text'], "drink", 1, answer['price'], true));
                            break;
                        */
                        default:
                            modalData.push(new ModalLabel("Not implemented answer type: " + type));
                            break;
                    }
                });

            });

            // Create an instance of ModalCreator
            const modal = new ModalCreator("Viewing: " + data['foodname'], modalData, "Add to Cart");
            console.log("asd");
            // Show the modal
            modal.show();
        }

        <?php
        if (isset($_GET['selectedfoodid'])) {
            echo "ShowFoodModalFromID('{$_GET['selectedfoodid']}');";
        }
        ?>
    });
</script>