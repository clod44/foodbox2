<?php
/*
little terminology here
in the database this whole "extra options" is named after "questions" and "answers"
its actually just question and answer generation.

so

"extra" is synonymous with "question"
"choice" is synonymous with "answer"

TR to ENG did dirty on this one.
and yes i do regret not sticking to one of the two
*/
$foodid = $_GET['foodid'] ?? null;
if ($foodid == null) {
    echo "there was an error loading food extras info";
    $backButton = "
    <a href='?page=panel&panel=editfood' class='btn btn-outline-primary'>⬅️ Go Back to editing
        tab</a>";
    echo $backButton;
    exit();
}


$sql = "SELECT * FROM foods WHERE restaurantid={$_SESSION['user']['id']}";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    echo "no food in the database? thats weird...";
    echo $backButton;
    exit();
}
$foods = mysqli_fetch_all($result, MYSQLI_ASSOC);

$backButton = "
<a href='?page=panel&panel=editfood&foodid=" . $foodid . "' class='btn btn-outline-primary'>⬅️ Go Back to editing
    tab</a>";


//handle post request
if (isset($_POST['newextra'])) {
    $foodid = $_POST['newextra'];//holds the id of the food
    $sql = "INSERT INTO questions (foodid) VALUES ($foodid)";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "there was an error adding the extra";
        echo $backButton;
        exit();
    }
    //add a choice. every question must have at least one choice
    $questionid = mysqli_insert_id($conn);
    $sql = "INSERT INTO answers (questionid) VALUES ($questionid)";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "there was an error adding the extra's default answer";
        echo $backButton;
        exit();
    }

    alert("added succesfully");
}

//fetch all questions
$sql = "SELECT * FROM questions WHERE foodid=$foodid";
$result = mysqli_query($conn, $sql);
$questions = null;
if (mysqli_num_rows($result) != 0) {
    $questions = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>


<div class="container p-4">
    <a href="?page=panel&panel=editfood&foodid=<?= $foodid ?>" class="btn btn-outline-primary">⬅️ Go Back to editing
        tab</a>

    <h2 class="text-center">Extras</h2>
    <?php
    $sql = "SELECT * FROM foods WHERE id=$foodid";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        echo "there was an error loading this food";
        exit();
    }
    $food = mysqli_fetch_assoc($result);
    ?>
    <ul class="list-group mb-3">
        <?php
        //echo a li for each field of the $food with the field's name
        foreach ($food as $key => $value) {
            echo "<li class='list-group-item p-1'><b>$key</b>: $value</li>";
        }
        ?>
    </ul>

    <hr class="my-5">



    <div class="rounded shadow p-4 rounded">
        <div class="d-flex justify-content-between mb-3">
            <span class="h2 text-center">Existing Entries:</span>
            <form method="post">
                <button type="submit" name="newextra" value="<?= $food['id'] ?>"
                    class="btn btn-lg btn-warning btn-shadow hover-scale fw-bold">➕New
                    Extra➕</button>
            </form>
        </div>
        <div>
            <?php
            if ($questions == null) {
                echo "<li class='list-group-item'>no extras yet</li>";
            } else {
                foreach ($questions as $question) {
                    ?>
                    <ul class="list-group shadow border border-primary mb-3">
                        <li class="list-group-item">
                            <span>
                                <b>title:</b> <?= $question['title'] ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <span>
                                <b>text:</b> <?= $question['text'] ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <span>
                                <?php
                                $types = [
                                    1 => 'checkbox',
                                    2 => 'radio',
                                    3 => 'input'
                                ]
                                    ?>
                                <b>type:</b> <?= $types[$question['type']] ?>
                            </span>
                        </li>

                        <li class="list-group-item">
                            <span>
                                <b>required answer:</b> <?= ($question['required'] == "1" ? "✅" : "❌") ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <span>
                                <!--TODO: make linked food look more descriptive than an id-->
                                <b>linked food:</b> <?= $question['foodid'] ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <span>
                                <?php
                                //get the amount of choices connected to this question
                                $sql = "SELECT count(id) as amount FROM answers WHERE questionid={$question['id']}";
                                $result = mysqli_query($conn, $sql);
                                $amount = mysqli_fetch_assoc($result)['amount'];
                                ?>
                                <b>Choices:</b> <?= $amount ?>
                            </span>
                        </li>

                        <li class="list-group-item">
                            <div class="d-flex gap-2">
                                <a href="?page=panel&panel=editextra&foodid=<?= $food['id'] ?>&extraid=<?= $question['id'] ?>"
                                    class="btn btn-primary btn-shadow flex-grow-1 hover-scale">✏️Edit✏️</a>
                            </div>
                        </li>

                    </ul>
                    <?php
                }
            }
            ?>
        </div>

    </div>

</div>