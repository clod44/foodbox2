<?php
if (!IS_USER_LOGGED_IN()) {
    require "./modules/login.php";
} else if ($_SESSION['user']['usertype'] == 1) {
    echo "<p class='text-center lead py-5 my-5' >you cant access here</p>";
} else {
    ?>
        <div class="container px-4 mt-4">
            <!--order history-->
            <div class="p-3">
                <?php
                //fetch all orders by this user
                $sql = "SELECT * FROM orders WHERE userid={$_SESSION['user']['id']}  AND orderconfirmed=1 ORDER BY timestamp DESC";
                $result = mysqli_query($conn, $sql);
                $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
                foreach ($orders as $order) {
                    ShowOrder($order);
                }
                ?>
            </div>
        </div>
    <?php
}
?>


<?php
function ShowOrder($order)
{
    global $conn;

    //fetch restaurant
    $sql = "SELECT * FROM restaurants WHERE id={$order['restaurantid']}";
    $result = mysqli_query($conn, $sql);
    $restaurant = mysqli_fetch_assoc($result);

    //fetch order details
    $sql = "SELECT * FROM orderdetails WHERE orderid={$order['id']}";
    $result = mysqli_query($conn, $sql);
    $orderdetails = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $statusDescriptions = [
        -1 => [
            "message" => "Cancelled",
            "color" => "text-bg-danger",
        ],
        0 => [
            "message" => "Pending",
            "color" => "text-bg-dark",
        ],
        1 => [
            "message" => "Processing",
            "color" => "text-bg-primary",
        ],
        2 => [
            "message" => "On the way!",
            "color" => "text-bg-warning",
        ],
        3 => [
            "message" => "Delivered!",
            "color" => "text-bg-success",
        ]
    ];
    ?>
    <div class="accordion accordion-flush" id="accordionOrder_<?= $order['id'] ?>">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse_<?= $order['id'] ?>" aria-expanded="false"
                    aria-controls="flush-collapse_<?= $order['id'] ?>">
                    <div class="d-flex justify-content-between flex-wrap w-100 pe-3">
                        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-center"">
                            <a class=" fw-bold fs-2"
                            href="?page=restaurant&restaurantid=<?= $order['restaurantid'] ?>">
                            <?= $restaurant['name'] ?></a>
                            <a href="tel:+<?= $restaurant['phone'] ?>">tel:<?= $restaurant['phone'] ?></a>
                        </div>
                        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-center">
                            <span><?= $order['timestamp'] ?></span>
                            <span class="badge shadow px-2 py-1 <?= $statusDescriptions[$order['status']]['color'] ?>">
                                <?= $statusDescriptions[$order['status']]['message'] ?>
                            </span>
                        </div>
                    </div>
                </button>
            </h2>
            <div id="flush-collapse_<?= $order['id'] ?>" class="accordion-collapse collapse"
                data-bs-parent="#accordionOrder_<?= $order['id'] ?>">
                <div class="accordion-body p-0 pt-2">
                    <div class="p-3 border border-primary rounded bg-light">
                        <p><?= GET_ADDRESS_TEXT($order['addressid']) ?></p>
                        <h5 class="m-0 p-0">Items</h5>
                        <ul class="p-2 m-0">
                            <?php
                            foreach ($orderdetails as $orderdetail) {
                                //fetch food
                                $sql = "SELECT * FROM foods WHERE id={$orderdetail['foodid']}";
                                $result = mysqli_query($conn, $sql);
                                $food = mysqli_fetch_assoc($result);
                                ?>
                                <li>
                                    <div class="row mb-2 gap-2 rounded border border-primary bg-light shadow p-2">
                                        <div class="col-12 col-md-3">
                                            <div>
                                                <img src="<?= GET_IMAGE($food['image']) ?>" class="rounded"
                                                    style="height:5rem;object-fit:contain;">

                                            </div>
                                        </div>
                                        <div class="col">
                                            <h5 class="m-0 p-0"><?= $food['name'] ?></h5>
                                            <p class="m-0 p-0"><?= $food['description'] ?></p>
                                            <p class="m-0 p-0">Price: $<?= $orderdetail['price'] ?></p>
                                        </div>
                                    </div>
                                    <?php
                                    if ($order["status"] == 3) {
                                        ShowCommentArea($orderdetail);
                                    }
                                    ?>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
}

function ShowCommentArea($orderdetail)
{
    global $conn;
    //check if there is already a comment
    $sql = "SELECT * FROM comments WHERE orderdetailid={$orderdetail['id']}";
    $result = mysqli_query($conn, $sql);
    $comment = mysqli_fetch_assoc($result);

    ?>
    <div class="mb-3">
        <form class="comment-form">
            <input type="hidden" name="orderdetailid" value="<?= $orderdetail['id'] ?>">
            <input type="hidden" name="foodid" value="<?= $orderdetail['foodid'] ?>">
            <div class="input-group input-group-sm mb-3 flex-nowrap align-items-center ">
                <span class="input-group-text score-preview"><?= $comment['score'] ?? 4 ?>⭐</span>
                <input type="range" class="ms-2 form-range" min="1" max="5" value="<?= $comment['score'] ?? 4 ?>"
                    name="score">
                <button class="ms-4 btn btn-primary btn-shadow hover-scale"
                    type="submit"><?= isset($comment) ? "Edit" : "Review!" ?></button>
            </div>
            <div class="form-floating">
                <textarea required class="form-control" name="comment" placeholder="Leave a comment here"
                    id="floatingTextarea_<?= $orderdetail['id'] ?>"><?= $comment['comment'] ?? "" ?></textarea>
                <label for="floatingTextarea_<?= $orderdetail['id'] ?>">Your Comment:</label>
            </div>
        </form>
    </div>
    <?php
}
//script for comment areas
?>
<script>
    $(document).ready(function () {
        $('.comment-form').on('submit', function (e) {
            e.preventDefault();
            //fetch form data
            var formData = $(this).serializeArray();
            var data = [];
            formData.forEach(element => {
                data[element.name] = element.value;
            });
            $.ajax({
                type: "POST",
                url: "./api/user/comment.php",
                data: {
                    userid: <?= $_SESSION['user']['id'] ?>,
                    orderdetailid: data['orderdetailid'],
                    foodid: data['foodid'],
                    score: data['score'],
                    comment: data['comment'] ?? "",
                },
                dataType: "json",
                success: function (response) {
                    console.log(response)
                    if (response.success) {
                        alert('Comment submitted successfully!');
                    } else {
                        alert('Login failed:\n' + response.error);
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.responseText ? xhr.responseText : 'Unknown error';
                    console.log(errorMessage);
                    alert('commenting failed:\n' + errorMessage);
                }
            });
        });

        $('input[name="score"]').on('input', function () {
            $(this).parent().find('.score-preview').text($(this).val() + '⭐');
        });
    })
</script>