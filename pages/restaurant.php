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

if (isset($_POST['favorite-restaurant'])) {
    //check if we already favorited, if yes, remove it
    //if no, add it
    $restaurantid_ = $_POST['favorite-restaurant'];
    $sql = "SELECT * FROM favoriterestaurants WHERE userid={$_SESSION['user']['id']} AND restaurantid=$restaurantid_";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $sql = "DELETE FROM favoriterestaurants WHERE userid={$_SESSION['user']['id']} AND restaurantid=$restaurantid_";
        $result = mysqli_query($conn, $sql);
    } else {
        $sql = "INSERT INTO favoriterestaurants (userid, restaurantid) VALUES({$_SESSION['user']['id']}, $restaurantid_)";
        $result = mysqli_query($conn, $sql);
    }
}

if (isset($_POST["order-food"])) {
    $foodid = $_POST["order-food"];

    $sql = "SELECT * FROM questions WHERE foodid=$foodid";
    $result = mysqli_query($conn, $sql);
    $questions = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $answers = [];

    foreach ($questions as $question) {
        $sql = "SELECT * FROM answers WHERE questionid={$question['id']}";
        $result = mysqli_query($conn, $sql);
        $answers[$question['id']] = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    //for each questions
    $givenAnswers = [];
    foreach ($questions as $question) {
        $thisAnswers = $answers[$question['id']];
        $foundAnswers = [];

        if ($question["type"] == 1) { //checkbox
            foreach ($thisAnswers as $answer) {
                if (isset($_POST["checkbox_" . $answer['id']])) {
                    $foundAnswers[] = $answer['id'];
                }
            }
        } else if ($question["type"] == 2) { //radio
            //radios only have one answer and they must be named same so i used the question id for name and answerid for value
            if (isset($_POST["radio_" . $thisAnswers[0]['questionid']])) {
                $foundAnswers[] = $_POST["radio_" . $thisAnswers[0]['questionid']]; //this returns answerid
            }
        }
        $givenAnswers[$question['id']] = $foundAnswers;
    }
    //console_log(json_encode($givenAnswers));

    //get the restaurantid from foods table
    $sql = "SELECT * FROM foods WHERE id=$foodid";
    $result = mysqli_query($conn, $sql);
    $food = mysqli_fetch_assoc($result);

    //orders
    //first off, remove all other orders which restaurantid!=$restaurantid
    $sql = "DELETE FROM orders WHERE restaurantid!={$food['restaurantid']} AND userid={$_SESSION['user']['id']}";
    $result = mysqli_query($conn, $sql);

    //check if we already have an order record with orderconfirmed=0
    $sql = "SELECT * FROM orders WHERE userid={$_SESSION['user']['id']} AND restaurantid={$food['restaurantid']} AND orderconfirmed=0";
    $result = mysqli_query($conn, $sql);
    $orderid = -1;
    if (mysqli_num_rows($result) < 1) {
        //create the order first
        $sql = "INSERT INTO orders (restaurantid, userid) VALUES({$food['restaurantid']}, {$_SESSION['user']['id']})";
        $result = mysqli_query($conn, $sql);
        $orderid = mysqli_insert_id($conn);
    } else {
        $order = mysqli_fetch_assoc($result);
        $orderid = $order['id'];
    }

    //add an order details
    //	id	orderid	foodid	price	active	
    $sql = "INSERT INTO orderdetails (orderid, foodid, price) VALUES($orderid, $foodid, {$food['price']})";
    $result = mysqli_query($conn, $sql);
    $orderdetailid = mysqli_insert_id($conn);

    //for each question add its answers to the orderdetailquestionanswers
    //id	orderdetailid	questionid	answerid	price	active	
    foreach ($givenAnswers as $questionid => $answers) {
        foreach ($answers as $answerid) {
            //fetch the answer details to get price
            $sql = "SELECT * FROM answers WHERE id=$answerid";
            $result = mysqli_query($conn, $sql);
            $answer = mysqli_fetch_assoc($result);

            $sql = "INSERT INTO orderdetailquestionanswers (orderdetailid, questionid, answerid, price) VALUES($orderdetailid, $questionid, $answerid, {$answer['price']})";
            $result = mysqli_query($conn, $sql);
        }
    }

}


//optional
$selectedfoodid = $_GET['selectedfoodid'] ?? null;
?>


<div class="container p-4">
    <div class="px-3 d-flex gap-5">
        <img src="<?= GET_IMAGE($restaurant['image']) ?>" class="rounded shadow"
            style="height:10rem;width:10rem;object-fit:cover;">
        <div class="h-100 flex-grow-1">
            <div class="d-flex justify-content-between align-items-center flex-nowrap">
                <h2 class="fw-bold text-primary m-0">
                    <?= $restaurant['name'] ?>
                </h2>
                <!--favorite button-->
                <?php
                $isRestaurant = $_SESSION['user']['usertype'] == 1;
                if (!$isRestaurant) {
                    //check if we favorited
                    $sql = "SELECT * FROM favoriterestaurants WHERE userid={$_SESSION['user']['id']} AND restaurantid=$restaurantid";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        $favorited = "";
                    } else {
                        $favorited = "outline-";
                    }
                    ?>
                    <form method="POST">
                        <button class="btn btn-lg btn-<?= $favorited ?>primary btn-shadow p-2 m-2 px-4"
                            name="favorite-restaurant" value="<?= $restaurant['id'] ?>" type="submit">
                            <i class=" bi bi-heart fs-3"></i>
                        </button>
                    </form>
                    <?php
                } ?>

            </div>
            <p class="lead fs-6">
                <?= $restaurant['description'] ?>
            </p>
            <p class="fw-bold">4.2/5⭐(+3000)</p>
            <div class="accordion accordion-flush p-1 m-0" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed p-0 m-0 text-primary" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false"
                            aria-controls="flush-collapseOne">
                            Comments
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <div class="p-3 rounded border border-primary d-flex flex-column gap-3"
                                style="height:15rem;overflow-x:hidden; overflow-y:auto;">
                                <?php
                                for ($i = 0; $i < 15; $i++) {
                                    ?>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold">Name Surname <span>- <?= date("d/m/Y"); ?></span></span>
                                            <span>⭐⭐⭐⭐</span>
                                        </div>
                                        <span>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem,
                                            quidem.</span>
                                        <div class="d-flex justify-content-end">
                                            <span>- <a href="#" class="fst-italic">Lorem, ipsum.</a></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                    <div class="row align-items-start justify-content-around rounded p-2">
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
                                    class="food hover-scale m-0 p-3 border border-primary text-bg-light border-2 rounded shadow overflow-hidden"
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
                                        <img src="<?= GET_IMAGE($food['image']) ?>" class="rounded"
                                            style="width:5rem;height:5rem;object-fit:cover;">
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
        <?php require "./modules/yourbox.php" ?>
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
                        let foods = response.foods;
                        if (Array.isArray(foods)) {
                            foods.forEach(function (food) {
                                ShowFoodModalFromFood(food);
                            });
                        } else {
                            console.log("Error:", response.error);
                        }
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).error : 'Unknown error';
                    console.log(errorMessage); // Log the error message to the console
                    alert('query failed:\n' + errorMessage);
                }
            });
        }
        function ShowFoodModalFromFood(food) { //foodAndRestaurantDetail
            $.ajax({
                type: "GET",
                url: "./api/food/getquestions.php",
                data: {
                    foodid: food['id']
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        CreateFoodModalFromAllData(food, response.questions, response.answers);
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
        function CreateFoodModalFromAllData(food, questions, answers) {
            //data is foodAndRestaurantDetail
            const modalData = [
                new ModalImage(GET_IMAGE(food['image'])),
                new ModalTitle(food['name']),
                new ModalLabel(food['description'])
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
                            modalData.push(new ModalCheckbox(answer['text'], "checkbox_" + answer['id'], "some value", answer['price']));
                            break;
                        case "2": //radio
                            //here the radio name must be something that the answers share in common
                            modalData.push(new ModalRadio(answer['text'], "radio_" + answer['questionid'], answer['id'], answer['price']));
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
            const modal = new ModalCreator("Viewing: " + food['name'], modalData, "Add to Cart", "?page=restaurant&restaurantid=" + food['restaurantid'], "order-food", food['id']);
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