<?php
/*TODO: for security;
- is user logged in
- is user a restaurant
- is user owns the said food
- is said extra connected to the food
*/

$backButton = "
<a href='?page=panel&panel=extras' class='btn btn-outline-primary'>⬅️ Go Back to Extras
    tab</a>";
$foodid = $_GET['foodid'] ?? null;
if ($foodid == null) {
    echo "no food given";
    echo $backButton;
    exit();
}

$sql = "SELECT * FROM foods WHERE id=$foodid";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    echo "food not found in db";
    echo $backButton;
    exit();
}
$food = mysqli_fetch_assoc($result);
$backButton = "
<a href='?page=panel&panel=extras&foodid=" . $foodid . "' class='btn btn-outline-primary'>⬅️ Go Back to Extras
    tab</a>";




$extraid = $_GET['extraid'] ?? null;
if ($extraid == null) {
    echo "no extra id given";
    echo $backButton;
    exit();
}


//utils
//fetch all foods
$sql = "SELECT * FROM foods WHERE restaurantid={$_SESSION['user']['id']}";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    echo "no food found in the restaurant database?";
    echo $backButton;
    exit();
}
$foods = mysqli_fetch_all($result, MYSQLI_ASSOC);



if (isset($_POST['saveextra'])) {//saves the details(update)
    $questionid = $_POST['saveextra'];
    $title = $_POST['title'];
    $text = $_POST['text'];
    $type = $_POST['type'];
    $required = isset($_POST['required']) ? '1' : '0';

    $sql = "UPDATE questions SET title='$title', text='$text', type=$type, required=$required WHERE id=$questionid";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        alert("succesfully saved");
    } else {
        alert("failed to save");
    }
}
if (isset($_POST['deleteextra'])) {//saves the details(update)
    $questionid = $_POST['deleteextra'];

    $sql = "DELETE FROM questions WHERE id=$questionid";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        alert("succesfully deleted");
    } else {
        alert("failed to delete");
    }
}
if (isset($_POST['addchoice'])) { //adds a choice to the extra(insert)
    $questionid = $_POST['addchoice'];
    $sql = "INSERT INTO answers (questionid) VALUES ($questionid)";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        alert("failed to add a new choice");
    } else {
        alert("added a new choice");
    }

}
if (isset($_POST['deletechoice'])) { //deletes the said choice from extra(delete)
    //TODO:check choice count. if its one, dont allow the choice to be deleted
    $answerid = $_POST['deletechoice'];

    //TODO: while checking answer count, combine finding your own question and its answers in one query
    $sql = "SELECT * FROM answers WHERE id=$answerid";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        alert("failed to delete choice");
        exit();
    }
    $answer = mysqli_fetch_assoc($result);

    //find the current choice amount
    $sql = "SELECT count(id) as amount FROM answers WHERE questionid={$answer['questionid']}";
    $result = mysqli_query($conn, $sql);
    $amount = mysqli_fetch_assoc($result)['amount'];
    if ($amount < 2) {
        //too little answers already. we cant delete this answer
        alert("you cant delete your last choice. edit it or delete the question instead.");
    } else {
        $sql = "DELETE FROM answers WHERE id=$answerid";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            alert("failed to delete choice");
        } else {
            alert("deleted choice");
        }
    }
}
if (isset($_POST['savechoices'])) {
    $questionid = $_POST['savechoices'];
    $texts = $_POST['texts'];
    $prices = $_POST['prices'];
    $ordervals = $_POST['ordervals'];
    $linkedfoods = $_POST['linkedfoods'];

    // Delete existing choices for the specified questionid
    $sql = "DELETE FROM answers WHERE questionid=$questionid";
    $result = mysqli_query($conn, $sql);
    //just assume above code works. 

    // Insert new choices using INSERT INTO VALUES syntax
    $sql = "INSERT INTO answers (questionid, foodid, text, price, orderval) VALUES ";

    $valueSets = [];
    for ($i = 0; $i < count($texts); $i++) {
        $linkedfood = mysqli_real_escape_string($conn, $linkedfoods[$i]);
        $text = mysqli_real_escape_string($conn, $texts[$i]);
        $price = floatval($prices[$i]);
        $orderval = intval($ordervals[$i]);
        $valueSets[] = "('$questionid', '$linkedfood', '$text', '$price', '$orderval')";
    }

    $sql .= implode(", ", $valueSets);
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        alert("error inserting new values: " . mysqli_error($conn));
    } else {
        alert("saved all choices succesfully");
    }
}


//question
$sql = "SELECT * FROM questions WHERE id=$extraid";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    echo "extra not found in db";
    echo $backButton;
    exit();
}
$question = mysqli_fetch_assoc($result);

//choices
$sql = "SELECT * FROM answers WHERE questionid=$extraid ORDER BY orderval";
$result = mysqli_query($conn, $sql);
$answers = null;
if (mysqli_num_rows($result) == 0) {
    //TODO: this is weird. questions should have at least one choice attached to them but here we have none. for now, add a choice
    $sql = "INSERT INTO answers (questionid) VALUES ($extraid)";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "something fishy going on here...";
    }
} else {
    $answers = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>


<div class="container p-4">

    <a href="?page=panel&panel=extras&foodid=<?= $foodid ?>" class="btn btn-outline-primary">⬅️ Go Back to Extras
        tab</a>

    <h2 class="text-center">Edit Extra</h2>

    <p>current food:</p>
    <ul class="list-group mb-3">
        <?php
        //echo a li for each field of the $food with the field's name
        foreach ($food as $key => $value) {
            echo "<li class='list-group-item p-1'><b>$key</b>: $value</li>";
        }
        ?>
    </ul>

    <hr class="my-5">

    <h3 class="text-center">Edit Extra</h3>

    <!--QUESTION DETAILS-->
    <form method="post" class="border border-primary rounded p-3 mb-3 bg-primary">
        <div class="input-group mb-3 shadow">
            <span class="input-group-text">Type :</span>
            <select class="form-select" name="type">
                <option value="1" <?= ($question['type'] == "1" ? "selected" : "") ?>>Checkbox : Multiselect</option>
                <option value="2" <?= ($question['type'] == "2" ? "selected" : "") ?>>Radio : Singleselect</option>
                <option value="3" <?= ($question['type'] == "3" ? "selected" : "") ?>>Input : Text input</option>
            </select>
        </div>

        <div class="input-group mb-3 shadow">
            <span class="input-group-text">Title :</span>
            <input type="text" class="form-control" name="title" placeholder="..." required
                value="<?= $question['title'] ?>">
        </div>

        <div class="input-group mb-3 shadow">
            <span class="input-group-text">Text :</span>
            <textarea class="form-control" name="text" placeholder="..." required><?= $question['text'] ?></textarea>
        </div>

        <div class="form-check form-switch mb-3 bg-light rounded">
            <input class="form-check-input" type="checkbox" role="switch" name="required" <?= ($question['required'] == "1" ? "checked" : "") ?>>
            <label class="form-check-label fw-bold">Required to be answered</label>
        </div>

        <div class="d-flex gap-3">
            <button type="submit" name="saveextra" value="<?= $extraid ?>"
                class="btn btn-lg btn-success btn-shadow hover-scale flex-grow-1">💾Save💾</button>
            <button type="submit" name="deleteextra" value="<?= $extraid ?>"
                class="btn btn-lg btn-danger btn-shadow hover-scale">🗑️Delete🗑️</button>

        </div>

    </form>

    <!--ANSWER CHOICES-->
    <div class="d-flex flex-column gap-3 p-3 rounded border border-primary mb-3">
        <form method="post">
            <?php
            if ($answers == null) {
                echo "<p class='text-center'>This question is corrupt. please delete it immediately</p>";
                exit();
            } else {
                foreach ($answers as $answer) {
                    ?>
                    <div class="input-group shadow mb-3">
                        <span class="input-group-text">></span>
                        <input type="text" class="form-control" name="texts[]" placeholder="custom text"
                            value="<?= $answer['text'] ?>" required>

                        <span class="input-group-text">+$</span>
                        <input type="number" min="0.00" step="0.01" class="form-control" name="prices[]"
                            value="<?= $answer['price'] ?>" required>

                        <span class="input-group-text">↕️</span>
                        <input type="number" class="form-control" name="ordervals[]" value="<?= intval($answer['orderval']) ?>"
                            required>

                        <select class="form-select" name="linkedfoods[]">
                            <option value="-1" <?= ($answer['foodid'] == "-1" ? "selected" : "") ?>>No Linked food</option>
                            <?php
                            foreach ($foods as $food) {
                                $selected = "";
                                if ($food['id'] == $answer['foodid']) {
                                    $selected = "selected";
                                }
                                echo "<option value='{$food['id']}' $selected>{$food['id']} : {$food['name']} : $ {$food['price']}</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" name="deletechoice" value="<?= $answer['id'] ?>"
                            class="btn btn-danger hover-scale btn-shadow">🗑️</button>
                    </div>
                    <?php
                }
            }
            ?>
            <div class="d-flex gap-3">
                <button type="submit" name="savechoices" value="<?= $extraid ?>"
                    class="btn btn-success hover-scale btn-shadow flex-grow-1">💾Save All Choices💾</button>
                <button type="submit" name="addchoice" value="<?= $extraid ?>"
                    class="btn btn-lg btn-warning hover-scale btn-shadow flex-grow-1">➕Add New
                    Choice➕</button>
            </div>
        </form>
    </div>

</div>