<?php

if (isset($_POST['search'])) {
    $keywords = $_POST['search_keywords'];
    $sort = $_POST['sort'];
    $dir = $_POST['dir'];

    $sql = "SELECT * FROM orders WHERE {$sort} LIKE '%{$keywords}%' ORDER BY {$sort} {$dir}";
    $result = mysqli_query($conn, $sql);
    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

} else {
    $sql = "SELECT * FROM orders WHERE restaurantid={$_SESSION['user']['id']}";
    $result = mysqli_query($conn, $sql);
    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>


<div class="container p-4">
    <form class="mb-3" action="?page=panel&panel=orders" method="post">
        <div class="input-group">
            <input type="text" class="form-control text-center" name="search_keywords" placeholder="Search by order ID"
                value="<?php if (isset($_POST['search_keywords']))
                    echo $_POST['search_keywords']; ?>">
            <button class="btn btn-primary hover-scale" type="submit" name="search">Filter 🔎</button>
        </div>
        <div class="input-group input-group-sm">
            <select class="form-select text-center" name="sort">
                <option value="id" <?php if (isset($_POST['sort']) && $_POST['sort'] == "id")
                    echo "selected"; ?>>OrderID🧾
                </option>
                <option value="userid" <?php if (isset($_POST['sort']) && $_POST['sort'] == "userid")
                    echo "selected"; ?>>UserID👤</option>
                <option value="timestamp" <?php if (isset($_POST['sort']) && $_POST['sort'] == "timestamp")
                    echo "selected"; ?>>Date📅</option>
            </select>
            <select class="form-select text-center" name="dir">
                <option value="DESC" <?php if (isset($_POST['dir']) && $_POST['dir'] == "DESC")
                    echo "selected"; ?>>DESC⬇️
                </option>
                <option value="ASC" <?php if (isset($_POST['dir']) && $_POST['dir'] == "ASC")
                    echo "selected"; ?>>ASC⬆️
                </option>
            </select>
        </div>
    </form>
    <div>
        <div class="d-flex flex-column gap-4 rounded border border-primary p-1">
            <?php
            //fetch orders for this restaurant
            /*

id
restaurantid
userid
addressid
approvalpersonnel
deliverypersonnel
status
orderconfirmed
timestamp
active


            */

            foreach ($orders as $order) {
                OrderRecord($order);
                ?>
                <hr>
                <?php
            } ?>
        </div>
    </div>
</div>


<?php
function OrderRecord($order)
{
    global $conn;
    $addressText = GET_ADDRESS_TEXT($order["addressid"]);

    //fetch user
    $sql = "SELECT * FROM users WHERE id={$order['userid']}";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    ?>
    <div class="d-flex flex-nowrap p-1 gap-2 align-items-start border border-primary rounded text-bg-secondary">
        <img src="./media/sample.jpg" class="rounded shadow d-none d-sm-block" style="height:3rem;">
        <div class="d-flex flex-column flex-grow-1">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <div class="">

                            <button class="accordion-button collapsed p-1" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse_<?= $order["id"] ?>">
                                <div class="d-flex flex-wrap">
                                    <span class="mx-2">
                                        📅<?= $order["timestamp"] ?>
                                    </span>
                                    <span class="mx-2">
                                        🧾<?= $order["id"] ?>
                                    </span>
                                    <span class="mx-2">
                                        👤<?= $user["id"] ?>-<?= $user["name"] ?>
                                    </span>
                                    <span class="mx-2">
                                        <?= $addressText ?>
                                    </span>
                                </div>
                            </button>

                            <form action="" class="form-change-status">
                                <input type="hidden" name="orderid" value="<?= $order["id"] ?>">
                                <div class="btn-group w-100 flex-wrap" role="group">
                                    <input type="radio" id="status_<?= $order["id"] ?>_-1" class="btn-check" name="status"
                                        value="-1" <?= ($order["status"] == -1) ? "checked" : "" ?> autocomplete="off">
                                    <label class="btn btn-outline-danger"
                                        for="status_<?= $order["id"] ?>_-1">❌Rejected</label>
                                    <input type="radio" id="status_<?= $order["id"] ?>_0" class="btn-check" name="status"
                                        value="0" <?= ($order["status"] == 0) ? "checked" : "" ?> autocomplete="off">
                                    <label class="btn btn-outline-warning"
                                        for="status_<?= $order["id"] ?>_0">⌛Pending</label>
                                    <input type="radio" id="status_<?= $order["id"] ?>_1" class="btn-check" name="status"
                                        value="1" <?= ($order["status"] == 1) ? "checked" : "" ?> autocomplete="off">
                                    <label class="btn btn-outline-warning"
                                        for="status_<?= $order["id"] ?>_1">🍳Preparing</label>
                                    <input type="radio" id="status_<?= $order["id"] ?>_2" class="btn-check" name="status"
                                        value="2" <?= ($order["status"] == 2) ? "checked" : "" ?> autocomplete="off">
                                    <label class="btn btn-outline-warning"
                                        for="status_<?= $order["id"] ?>_2">🛵Delivering</label>
                                    <input type="radio" id="status_<?= $order["id"] ?>_3" class="btn-check" name="status"
                                        value="3" <?= ($order["status"] == 3) ? "checked" : "" ?> autocomplete="off">
                                    <label class="btn btn-outline-success"
                                        for="status_<?= $order["id"] ?>_3">✅Delivered</label>
                                </div>
                            </form>

                        </div>

                    </h2>
                    <div id="collapse_<?= $order["id"] ?>" class="accordion-collapse collapse"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body p-1">
                            <ol class="m-0 ps-3">
                                <?php
                                //fetch order details
                                $sql = "SELECT * FROM orderdetails WHERE orderid={$order["id"]}";
                                $result = mysqli_query($conn, $sql);
                                $orderdetails = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                foreach ($orderdetails as $orderdetail) {
                                    //fetch food
                                    $sql = "SELECT * FROM foods WHERE id={$orderdetail['foodid']}";
                                    $result = mysqli_query($conn, $sql);
                                    $food = mysqli_fetch_assoc($result);
                                    ?>
                                    <li>
                                        <div class="d-flex flex-wrap p-1 gap-2 align-items-start">
                                            <img src="<?= GET_IMAGE($food['image']) ?>" class="rounded shadow"
                                                style="height:3rem;">
                                            <div class="d-flex flex-column flex-grow-1">
                                                <!-- Added flex-grow-1 class -->
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <p class="m-0 p-0 fs-6 fw-bold"><?= $food['name'] ?></p>
                                                </div>
                                                <?php
                                                //fetch given answers
                                                $sql = "SELECT * FROM orderdetailquestionanswers
                                                                WHERE orderdetailid = {$orderdetail['id']}";
                                                $result = mysqli_query($conn, $sql);
                                                //(o)rder(d)etail(q)uestion(a)nswers
                                                $odqas = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                                $questionsByQuestionID = [];
                                                $odqasByQuestionID = [];
                                                if (count($odqas) > 0) {
                                                    foreach ($odqas as $odqa)
                                                        $odqasByQuestionID[$odqa['questionid']] = [];
                                                    foreach ($odqas as $odqa)
                                                        $odqasByQuestionID[$odqa['questionid']][] = $odqa;

                                                    foreach ($odqas as $odqa) {
                                                        //find its question
                                        
                                                        console_log(json_encode($odqa));
                                                        $sql = "SELECT * FROM questions WHERE id={$odqa['questionid']}";
                                                        $result = mysqli_query($conn, $sql);
                                                        $question = mysqli_fetch_assoc($result);
                                                        $questionsByQuestionID[$question['id']] = $question;
                                                        ?>
                                                        <?php
                                                    }
                                                    /*
                                                    now we have
                                                    $answersByQuestions = [
                                                        questionID => [
                                                            answerGiven1, //this is a "orderdetailquestionanswer" record
                                                            ?answerGiven2,
                                                            ?answerGiven3,
                                                        ],
                                                    ]
                                                    and 
                                                    $questionsByQuestions = [
                                                        questionID => question
                                                    ] 
                                                    */
                                                    //console_log(json_encode($questions));
                                        
                                                    foreach ($questionsByQuestionID as $question) {
                                                        //console_log(json_encode($answersByQuestion[$question['id']]));
                                                        ShowQuestionAndAnswers($question, $odqasByQuestionID[$question['id']]);
                                                    }
                                                } ?>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text text-bg-warning ms-auto fw-bold"
                                                        id="basic-addon1">$<?= FixedDecimal($food['price'], 2) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="border-primary m-0 p-0 my-2">
                                    </li>
                                <?php } ?>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="input-group input-group-sm">
                <span class="input-group-text fw-bold text-bg-success"
                    id="basic-addon1">$<?= GET_BOX_PRICE(null, $order["id"]) ?></span>
                <!--
                            <input type="number" readonly class="form-control" placeholder="amount" value="1">
                            -->
            </div>
        </div>
    </div>
    <?php
}
?>



<script>
    //on jquery document load
    $(document).ready(function () {
        //when a radio button is clicked inside form-change-status
        $('form.form-change-status input[type="radio"]').click(function () {
            //get the value of the clicked radio button
            var status = $(this).val();
            //find the orderid named input field in this form
            var orderid = $(this).closest('form').find('input[name="orderid"]').val();
            $.ajax({
                type: "POST",
                url: "./api/restaurant/order/changestatus.php",
                data: {
                    orderid: orderid,
                    status: status
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        console.log("succesfully changed the status")
                    } else {
                        console.log("Error:", response.error);
                        alert(response.error);
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.responseText ? xhr.responseText : 'Unknown error';
                    console.log("Error:", errorMessage); // Log the error message to the console
                    alert('An error occurred:\n' + errorMessage);
                }
            });
        })
    })

</script>

<?php

function ShowQuestionAndAnswers($question, $odqas)
{
    global $conn;
    //odqas = orderdetailquestionanswers related to this question. given answers to this question
    ?>

    <ul class="m-0 mb-2 ps-2">
        <ul class="m-0 ps-1">
            <p class="fs-7 m-0"><span class="fw-bold fs-6"><?= $question['title'] ?></span>
                <?= $question['text'] ?>
            </p>
            <?php
            foreach ($odqas as $odqa) {
                //fetch actual answer for information like "text"
                $sql = "SELECT * FROM answers WHERE id={$odqa['answerid']}";
                $result = mysqli_query($conn, $sql);
                $answer = mysqli_fetch_assoc($result);
                //console_log(json_encode($answer));
                ?>
                <li>
                    <p class="fs-7 m-0"><?= $answer['text'] ?>
                        <?php
                        $isFree = $odqa['price'] == 0;
                        if (!$isFree) {
                            ?>
                            <span class="ms-2 fs-7 badge text-bg-warning">$<?= FixedDecimal($odqa['price'], 2) ?>
                            </span>
                            <?php
                        }
                        ?>
                    </p>
                </li>
                <?php
            }
            ?>
        </ul>
    </ul>
    <?php
}
?>