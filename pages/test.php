<div class="container p-5">

    <div class="input-group mb-3">
        <span class="input-group-text">Search food:</span>
        <input type="text" class="form-control fw-bold" placeholder="food name here" onkeyup="showResults(this.value)"
            autocomplete="off">
    </div>
    <ul id="results" class="list-group">

    </ul>
    <hr class="my-3 border-primary">
    <select class="form-select text-bg-primary" onchange="showUserDetails(this.value)">
        <option value="" selected>Select an user</option>
        <?php
        $sql = "SELECT * FROM users";
        $result = mysqli_query($conn, $sql);
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($users as $user) {
            echo "<option value='" . $user['id'] . "'>" . $user['name'] . "</option>";
        }
        ?>
    </select>
    <table class="table" id="table" style="display: none">
        <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">name</th>
                <th scope="col">username</th>
                <th scope="col">email</th>
                <th scope="col">phone</th>
            </tr>
        </thead>
        <tbody id="table-row">

        </tbody>
    </table>

</div>

<script>
    var spinnerHtml = "<div class='spinner-border text-danger' role = 'status' > <span class='visually-hidden'>Loading...</span></div>";

    function showResults(str) {
        var results = document.getElementById("results");
        results.innerHTML = spinnerHtml;
        if (str.length == 0) {
            results.innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    results.innerHTML = "";
                    var data = JSON.parse(this.responseText);
                    console.log(data);
                    var foods = data.foods;
                    results.innerHTML += `<p class="fw-bold">${foods.length} Results found</p>`;
                    foods.forEach(food => {
                        results.innerHTML += "<li class='list-group-item p-0 px-3 border-danger'>" + food.name + "</li>";
                    });

                }
            };
            xmlhttp.open("GET", "./api/misc/test.php?q=" + str, true);
            xmlhttp.send();
        }
    }

    function showUserDetails(id) {
        var table = document.getElementById("table");
        var tablerow = document.getElementById("table-row");
        tablerow.innerHTML = spinnerHtml;
        if (!id) {
            table.style.display = "none";
            tablerow.innerHTML = "";
            return;
        } else {
            table.style.display = ""; //revert back to default
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    tablerow.innerHTML = "";
                    var data = JSON.parse(this.responseText);
                    console.log(data);
                    var user = data.user;
                    tablerow.innerHTML = `
                    <tr>
                        <th scope="row">${user["id"]}</th>
                        <td>${user["name"]}</td>
                        <td>${user["username"]}</td>
                        <td>${user["email"]}</td>
                        <td>${user["phone"]}</td>
                    </tr>
                    `;

                }
            };
            xmlhttp.open("GET", "./api/misc/test2.php?userid=" + id, true);
            xmlhttp.send();
        }
    }
</script>