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

if (isset($_POST['favorite-restaurant']) && IS_USER_LOGGED_IN()) {
    if (IS_USER_LOGGED_IN()) {
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
    } else {
        alert("please login first");
    }
}

if (isset($_POST["order-food"])) {
    if (!IS_USER_LOGGED_IN() || $_SESSION['user']['usertype'] == 1) {
        header("Location: ?page=profile");
        exit();
    }
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
    <!--restaurant info-->
    <div>
        <div class="px-3 row">
            <div class="col-12 col-sm-4">
                <img src="<?= GET_IMAGE($restaurant['image']) ?>" class="rounded shadow w-100"
                    style="object-fit:cover;">
            </div>
            <div class="col-12 col-sm-8">
                <div class="d-flex justify-content-between align-items-center flex-nowrap w-100">
                    <h2 class="fw-bold text-primary m-0">
                        <?= $restaurant['name'] ?>
                    </h2>
                    <!--favorite button-->
                    <?php
                    $isRestaurant = false;
                    if (IS_USER_LOGGED_IN())
                        $isRestaurant = $_SESSION['user']['usertype'] == 1;
                    if (!$isRestaurant && IS_USER_LOGGED_IN()) {
                        //check if we favorited
                        $sql = "SELECT * FROM favoriterestaurants WHERE userid={$_SESSION['user']['id']} AND restaurantid=$restaurantid";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            $favorited = "";
                        } else {
                            $favorited = "outline-";
                        }
                    }
                    ?>

                    <form method="POST">
                        <button class="btn btn-lg btn-<?= $favorited ?>primary btn-shadow p-2 m-2 px-4"
                            name="favorite-restaurant" value="<?= $restaurant['id'] ?>" type="submit">
                            <i class=" bi bi-heart fs-3"></i>
                        </button>
                    </form>


                </div>
                <p class="lead fs-6">
                    <?= $restaurant['description'] ?>
                </p>
                <?php
                //get score average of the comments given to the restaurant
                $sql = "SELECT avg(comments.score) as avgscore FROM comments, foods WHERE comments.foodid=foods.id AND foods.restaurantid=$restaurantid";
                $result = mysqli_query($conn, $sql);
                $avgscore = mysqli_fetch_assoc($result)['avgscore'];
                ?>
                <p class="fw-bold"><?= $avgscore != null ? number_format($avgscore, 1) : "??" ?>/5.0⭐</p>

            </div>
        </div>
        <!--comments-->
        <div class="row accordion accordion-flush p-1 px-3 m-0" id="accordionFlushComment">
            <div class="accordion-item p-0">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed p-0 m-0 text-primary" type="button"
                        data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false"
                        aria-controls="flush-collapseOne">
                        <?php
                        $sql = "SELECT count(comments.id) FROM comments, foods WHERE comments.foodid=foods.id AND foods.restaurantid=$restaurantid";
                        $result = mysqli_query($conn, $sql);
                        $commentCount = mysqli_fetch_assoc($result)['count(comments.id)'];
                        ?>
                        Comments (<?= $commentCount ?>)
                    </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushComment">
                    <div class="accordion-body px-0">
                        <div class="p-3 rounded border border-primary d-flex flex-column gap-3"
                            style="height:15rem;overflow-x:hidden; overflow-y:auto;">
                            <?php
                            //fetch comments
                            $sql = "SELECT comments.* FROM comments, foods WHERE comments.foodid=foods.id AND foods.restaurantid=$restaurantid ORDER BY comments.id DESC";
                            $result = mysqli_query($conn, $sql);
                            $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
                            foreach ($comments as $comment) {
                                //fetch user
                                $sql = "SELECT * FROM users WHERE id={$comment['userid']}";
                                $result = mysqli_query($conn, $sql);
                                $user = mysqli_fetch_assoc($result);
                                //split name and make it like "J*** K***"
                                $name = $user['name'];
                                $words = preg_split('/\s+/', $name);
                                $name = "";
                                foreach ($words as $word) {
                                    $name .= substr($word, 0, 1) . "*** ";
                                }

                                //fetch food
                                $sql = "SELECT * FROM foods WHERE id={$comment['foodid']}";
                                $result = mysqli_query($conn, $sql);
                                $food = mysqli_fetch_assoc($result);
                                ?>
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold"><?= $name ?><span>-
                                                <?= $comment['timestamp'] ?></span></span>
                                        <span>
                                            <?php for ($i = 0; $i < $comment['score']; $i++)
                                                echo "⭐"; ?>
                                        </span>
                                    </div>
                                    <span><?= $comment['comment'] ?></span>
                                    <div class="d-flex justify-content-end">
                                        <span>- <a
                                                href="?page=restaurant&restaurantid=<?= $food['restaurantid'] ?>&selectedfoodid=<?= $food['id'] ?>"
                                                class="fst-italic"><?= $food['name'] ?></a></span>
                                    </div>
                                </div>
                                <hr>
                                <?php
                            }
                            ?>
                            <p class="text-center m-0"> <?= $commentCount ?> comments</p>
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
        $sql = "SELECT * FROM menus WHERE restaurantid=$restaurantid";
        $result = mysqli_query($conn, $sql);
        $menus = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($menus as $menu) {
            ?>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#menu_<?= $menu['id'] ?>"><?= $menu['name'] ?></a>
            </li>
            <?php
        } ?>
    </ul>

    <div class="row">
        <!--yourbox-->
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="d-sm-none">
                <button class="btn btn-primary w-100 p-0 col-span-12" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseYourbox" aria-expanded="false" aria-controls="collapseYourbox">
                    Yourbox
                </button>
            </div>

            <div class="collapse d-sm-block" id="collapseYourbox">
                <?php require "./modules/yourbox.php"; ?>
            </div>
        </div>

        <!--restaurat menu content-->
        <div class="col col-sm-6 col-md-8 d-flex flex-column gap-3">
            <?php
            foreach ($menus as $menu) {
                ?>
                <div id="menu_<?= $menu['id'] ?>">
                    <h3 class="mb-3"><?= $menu['name'] ?> - <span class="h5"><?= $menu['description'] ?></span>
                    </h3>
                    <div class="row align-items-start justify-content-around rounded p-2">
                        <?php
                        $sql = "SELECT foods.* FROM foods, menus, menufoods WHERE foods.id=menufoods.foodid AND menus.id=menufoods.menuid AND menus.id={$menu['id']} AND menus.restaurantid=$restaurantid";
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
    </div>

</div>
<script>
    $(document).ready(function () {
        $('.food').click(function () {
            var foodid = $(this).data('food-id');
            ShowFoodModalFromID(foodid);
        });
        function ShowFoodModalFromID(foodid) {
            console.log("amongus");
            var food = null;
            //get food info from endpoint
            $.ajax({
                type: "GET",
                url: "./api/food/query.php",
                data: {
                    foodid: foodid
                },
                dataType: 'json',
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
                    console.log(errorMessage);
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
                    console.log(errorMessage);
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
            const modal = new ModalCreator("Viewing: " + food['name'], modalData, "Add to Cart", "?page=restaurant&restaurantid=" + food['restaurantid'], "order-food", food['id']);
            modal.show();
        }

        <?php
        if (isset($_GET['selectedfoodid'])) {
            echo "ShowFoodModalFromID('{$_GET['selectedfoodid']}');";
        }
        ?>
    });
</script>