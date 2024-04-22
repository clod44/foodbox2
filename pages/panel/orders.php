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
                //fetch address
                $sql = "SELECT * FROM addresses WHERE id={$order['addressid']}";
                $result = mysqli_query($conn, $sql);
                $address = mysqli_fetch_assoc($result);

                $sql = "SELECT * FROM cities WHERE id={$address['cityid']}";
                $result = mysqli_query($conn, $sql);
                $city = mysqli_fetch_assoc($result);

                $sql = "SELECT * FROM districts WHERE id={$address['districtid']}";
                $result = mysqli_query($conn, $sql);
                $district = mysqli_fetch_assoc($result);

                $sql = "SELECT * FROM streets WHERE id={$address['streetid']}";
                $result = mysqli_query($conn, $sql);
                $street = mysqli_fetch_assoc($result);

                $addressText = $street['name'] . ", " . $district['name'] . ", " . $city['name'];

                //fetch user
                $sql = "SELECT * FROM users WHERE id={$order['userid']}";
                $result = mysqli_query($conn, $sql);
                $user = mysqli_fetch_assoc($result);

                ?>
                <div class="d-flex flex-nowrap p-1 gap-2 align-items-start border border-primary rounded text-bg-secondary">
                    <img src="./media/sample.jpg" class="rounded shadow" style="height:3rem;">
                    <div class="d-flex flex-column flex-grow-1">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <div class="p-3 pt-0">

                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
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
                                                    📌<?= $addressText ?>
                                                </span>
                                            </div>
                                        </button>

                                        <form action="" class="form-change-status">
                                            <input type="hidden" name="orderid" value="<?= $order["id"] ?>">
                                            <div class="btn-group w-100 flex-wrap" role="group">
                                                <input type="radio" id="status_<?= $order["id"] ?>_-1" class="btn-check"
                                                    name="status" value="-1" <?= ($order["status"] == -1) ? "checked" : "" ?>
                                                    autocomplete="off">
                                                <label class="btn btn-outline-danger"
                                                    for="status_<?= $order["id"] ?>_-1">❌Rejected</label>
                                                <input type="radio" id="status_<?= $order["id"] ?>_0" class="btn-check"
                                                    name="status" value="0" <?= ($order["status"] == 0) ? "checked" : "" ?>
                                                    autocomplete="off">
                                                <label class="btn btn-outline-warning"
                                                    for="status_<?= $order["id"] ?>_0">⌛Pending</label>
                                                <input type="radio" id="status_<?= $order["id"] ?>_1" class="btn-check"
                                                    name="status" value="1" <?= ($order["status"] == 1) ? "checked" : "" ?>
                                                    autocomplete="off">
                                                <label class="btn btn-outline-warning"
                                                    for="status_<?= $order["id"] ?>_1">🍳Preparing</label>
                                                <input type="radio" id="status_<?= $order["id"] ?>_2" class="btn-check"
                                                    name="status" value="2" <?= ($order["status"] == 2) ? "checked" : "" ?>
                                                    autocomplete="off">
                                                <label class="btn btn-outline-warning"
                                                    for="status_<?= $order["id"] ?>_2">🛵Delivering</label>
                                                <input type="radio" id="status_<?= $order["id"] ?>_3" class="btn-check"
                                                    name="status" value="3" <?= ($order["status"] == 3) ? "checked" : "" ?>
                                                    autocomplete="off">
                                                <label class="btn btn-outline-success"
                                                    for="status_<?= $order["id"] ?>_3">✅Delivered</label>
                                            </div>
                                        </form>

                                    </div>

                                </h2>
                                <div id="collapse_<?= $order["id"] ?>" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ol class="m-0">
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
                                                    <div class="d-flex flex-nowrap p-1 gap-2 align-items-start">
                                                        <img src="<?= GET_IMAGE($food['image']) ?>" class="rounded shadow"
                                                            style="height:3rem;">
                                                        <div class="d-flex flex-column flex-grow-1">
                                                            <!-- Added flex-grow-1 class -->
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <p class="m-0 p-0 fs-6 fw-bold"><?= $food['name'] ?></p>
                                                            </div>
                                                            <?php
                                                            //get orderdetailquestionanswers grouped with questions
                                                            //this looks a bit goofy because "only_full_group_by" was left "false"
                                                            $sql = "SELECT 
                                                                    orderdetailid,
                                                                    questionid,
                                                                    MAX(answerid) AS answerid,
                                                                    MAX(price) AS price,
                                                                    MAX(id) AS max_id
                                                                FROM 
                                                                    orderdetailquestionanswers
                                                                WHERE 
                                                                    orderdetailid = {$orderdetail['id']}
                                                                GROUP BY 
                                                                    orderdetailid, questionid;
                                                                ";
                                                            $result = mysqli_query($conn, $sql);
                                                            $orderdetailquestionanswers = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                                            if ($orderdetailquestionanswers) {
                                                                foreach ($orderdetailquestionanswers as $orderdetailquestionanswer) {
                                                                    $sql = "SELECT * FROM questions WHERE id={$orderdetailquestionanswer['questionid']}";
                                                                    $result = mysqli_query($conn, $sql);
                                                                    $question = mysqli_fetch_assoc($result);

                                                                    $sql = "SELECT answers.* FROM orderdetailquestionanswers, answers WHERE orderdetailquestionanswers.questionid={$question['id']} AND orderdetailquestionanswers.answerid=answers.id";
                                                                    $result = mysqli_query($conn, $sql);
                                                                    $answers = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                                                    ?>
                                                                    <ul class="m-0 mb-2">
                                                                        <ul>
                                                                            <p class="fs-7 m-0"><span
                                                                                    class="fw-bold fs-6"><?= $question['title'] ?></span>
                                                                                <?= $question['text'] ?>
                                                                            </p>
                                                                            <?php
                                                                            foreach ($answers as $answer) {
                                                                                ?>
                                                                                <li>
                                                                                    <p class="fs-7 m-0"><?= $answer['text'] ?>
                                                                                        <?php
                                                                                        $isFree = $answer['price'] == 0;
                                                                                        if (!$isFree) {
                                                                                            ?>
                                                                                            <span
                                                                                                class="ms-2 fs-7 badge text-bg-warning">$<?= FixedDecimal($answer['price'], 2) ?>
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
                <hr>
                <?php
            } ?>
        </div>
    </div>
</div>
<script>
    //on jquery document load
    $(document).ready(function () {
        //when a radio button is clicked inside form-change-status
        $('form.form-change-status input[type="radio"]').click(function () {
            //get the value of the clicked radio button
            var status = $(this).val();
            var orderid = $("form.form-change-status input[name='orderid']").val();
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