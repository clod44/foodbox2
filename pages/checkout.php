<?php
if (!IS_USER_LOGGED_IN()) {
    header("Location: ?page=profile");
    exit();
} else if ($_SESSION['user']['usertype'] == 1) {
    header("Location: ?page=profile");
    exit();
}

if (isset($_POST["order-confirm"])) {
    $orderid = $_POST["order-confirm"];
    $addressid = $_POST["addressid"];
    if ($addressid == -1) {
        alert("Please select an address.");
    } else {

        //update the order's "orderconfirmed" to 1 and addressid to $addressid
        $sql = "UPDATE orders SET orderconfirmed=1, addressid=$addressid WHERE id=$orderid";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            alert("Error confirming order: " . mysqli_error($conn));
        }
    }
}
if (isset($_POST["delete-orderdetail"])) {
    $orderdetailid = $_POST["delete-orderdetail"];
    //get the orderdetail
    $sql = "SELECT * FROM orderdetails WHERE id = $orderdetailid";
    $result = mysqli_query($conn, $sql);
    $orderdetail = mysqli_fetch_assoc($result);

    //get the order
    $sql = "SELECT * FROM orders WHERE id = {$orderdetail['orderid']}";
    $result = mysqli_query($conn, $sql);
    $order = mysqli_fetch_assoc($result);

    //delete the order detail
    $sql = "DELETE FROM orderdetails WHERE id = $orderdetailid";
    $result = mysqli_query($conn, $sql);

    //check how many orderdetails are connected to the order
    $sql = "SELECT * FROM orderdetails WHERE orderid = {$order['id']}";
    $result = mysqli_query($conn, $sql);
    $orderdetails = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $amount = count($orderdetails);
    //if its 0, delete the order
    if ($amount == 0) {
        $sql = "DELETE FROM orders WHERE id = {$order['id']}";
        $result = mysqli_query($conn, $sql);
    }
}


$isEmpty = false;
?>
<div class="container p-4">
    <?php
    $sql = "SELECT * FROM orders WHERE userid={$_SESSION['user']['id']} AND orderconfirmed=0";
    $result = mysqli_query($conn, $sql);
    $order = mysqli_fetch_assoc($result);
    ?>

    <!--this code is a bit different than the yourbox.php's html-->
    <div>
        <div class="ms-3 flex-grow-1 border border-primary rounded shadow p-4">
            <h3 class="m-0 p-0 text-center mb-2">📦 Your Box 📦</h3>
            <hr class="p-0 m-2 border-primary">
            <div class="rounded w-100 mb-2">
                <?php
                if ($order != null) {
                    UPDATE_BOX_PRICES();
                    //fetch order details
                    $sql = "SELECT * FROM orderdetails WHERE orderid={$order['id']}";
                    $result = mysqli_query($conn, $sql);
                    $orderdetails = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    $isEmpty = count($orderdetails) == 0;
                    //for each orderdetail
                    foreach ($orderdetails as $orderdetail) {
                        //fetch food
                        $sql = "SELECT * FROM foods WHERE id={$orderdetail['foodid']}";
                        $result = mysqli_query($conn, $sql);
                        $food = mysqli_fetch_assoc($result);

                        ?>
                        <div class="d-flex flex-wrap p-1 gap-2 align-items-start">
                            <img src="<?= GET_IMAGE($food['image']) ?>" class="rounded shadow w-100"
                                style="height:10rem; object-fit:cover;">
                            <div class="d-flex flex-column flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <p class="m-0 p-0 fs-6 fw-bold"><?= $food['name'] ?></p>
                                    <form action="" method="post">
                                        <button type="submit" name="delete-orderdetail" value="<?= $orderdetail['id'] ?>"
                                            class="btn btn-lg btn-outline m-0 p-0 fs-2 hover-scale">✖️</button>
                                    </form>
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

                                        $sql = "SELECT answers.* FROM orderdetailquestionanswers, answers WHERE orderdetailquestionanswers.questionid={$question['id']} AND orderdetailquestionanswers.answerid=answers.id AND orderdetailquestionanswers.orderdetailid={$orderdetail['id']}";
                                        $result = mysqli_query($conn, $sql);
                                        $answers = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                        ?>
                                        <ul class="m-0 mb-2 ps-2">
                                            <ul class="ps-2">
                                                <p class="fs-7 m-0"><span class="fw-bold fs-6"><?= $question['title'] ?></span>
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
                                        x</span>
                                    <!--
                                <input type="number" class="form-control" placeholder="amount" value="1">
                                    -->
                                </div>
                            </div>
                        </div>
                        <hr class="border-primary m-0 p-0 my-2">

                        <?php
                    }
                    if ($isEmpty) {
                        ?>
                        <p class="m-0 p-0 my-5 text-center">🐫 Your box is empty 🌵</p>
                        <?php
                    }
                } else {
                    $isEmpty = true;
                    ?>
                    <p class="m-0 p-0 my-5 text-center">🐫 Your box is empty 🌵</p>
                    <?php
                } ?>
            </div>
            <div class="d-flex flex-nowrap justify-content-center align-items-center mb-3">
                <p class="m-0 p-0">Total: </p>
                <p class="m-0 p-0 fw-bold">$<?= FixedDecimal(GET_BOX_PRICE($_SESSION['user']['id'])) ?></p>
            </div>

            <form action="" method="post">
                <div class="input-group input-group-lg flex-wrap align-items-center h-100">
                    <span class="input-group-text">Teslim adresi:</span>
                    <select class="form-select text-center" name="addressid">
                        <?php
                        $sql = "SELECT * FROM addresses WHERE userid={$_SESSION['user']['id']}";
                        $result = mysqli_query($conn, $sql);
                        if (!$result) {
                            echo "<p>No address found</p>";
                        } else {
                            $addresses = mysqli_fetch_all($result, MYSQLI_ASSOC);
                        }

                        $selectedAddressID = $_SESSION["user"]["addressid"];

                        if (count($addresses) == 0) {
                            ?>
                            <option selected value=" -1">📌 Add an addresses from your profile! 📌</option>
                            <?php
                        } else {
                            foreach ($addresses as $index => $address) {
                                $isSelected = $address['id'] == $selectedAddressID ? "selected" : "";
                                ?>
                                <option value="<?= $address['id'] ?>" <?= $isSelected ?>>
                                    <?= ($index + 1) . GET_ADDRESS_TEXT($address['id']) ?>
                                </option>
                            <?php }
                        } ?>
                    </select>
                </div>

                <br>
                <p class="fs-7 text-muted text-center">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odit,
                    ea.
                </p>
                <button type="submit" name="order-confirm" value="<?= $order['id'] ?>"
                    class="btn btn-lg fw-bold w-100 btn-primary btn-shadow hover-scale <?= $isEmpty ? "disabled" : "" ?>">
                    Confirm the Box ✅
                </button>
            </form>
        </div>
    </div>


</div>