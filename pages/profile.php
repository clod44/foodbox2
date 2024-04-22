<?php
if (isset($_POST['pfp-upload'])) {
    $uploadedFileName = $_FILES["file"]["name"];

    // Define the callback function logic
    $updateProfilePicture = function ($newFileName, $target_dir) {
        global $conn;
        $tableName = $_SESSION['user']['usertype'] == 1 ? "restaurants" : "users";
        $sql = "UPDATE $tableName SET image='$newFileName' WHERE id={$_SESSION['user']['id']}";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            alert("Error updating profile picture: " . mysqli_error($conn));
        }
    };

    UPLOAD_IMAGE($uploadedFileName, "pfp_", $updateProfilePicture);

    UPDATE_SESSION_USER();
    //TODO: you also need to refresh again because some other parts of the page already loaded with old data (like header)
}
if (isset($_POST['add-address'])) {
    /*
    normal users have address recordings with their id, 
    restaurants have -1 for the "userid" in the address recording.
    instead of having one to many relationships, restaurants actually hold the id of their address on theirself.
    
    ^ what the fuck does that mean???
    addressid is just address id and nothing else. 
    "userid" in the addresses table is "-1" if the user is a restaurant 
    (because restaurant's id isnt in the users table).
    */
    $cityid = $_POST['city'];
    $districtid = $_POST['district'];
    $streetid = $_POST['street'];
    $addressname = $_POST['name'];
    if ($cityid < 0 || $districtid < 0 || $streetid < 0) {
        alert("Please select a city, district, and street");
    } else {
        $isRestaurant = $_SESSION['user']['usertype'] == 1;
        if (!$isRestaurant) {
            $sql = "INSERT INTO addresses (name, userid, cityid, districtid, streetid) VALUES ('$addressname', {$_SESSION['user']['id']}, $cityid, $districtid, $streetid)";
        } else {
            $sql = "INSERT INTO addresses (name, userid, cityid, districtid, streetid) VALUES ('$addressname', -1, $cityid, $districtid, $streetid)";
        }
        $result = mysqli_query($conn, $sql);
        $addressid = mysqli_insert_id($conn);
        $tableName = $_SESSION['user']['usertype'] == 1 ? "restaurants" : "users";
        $sql = "UPDATE $tableName SET addressid=$addressid WHERE id={$_SESSION['user']['id']}";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            alert("Error adding address: " . mysqli_error($conn));
        }
    }
    UPDATE_SESSION_USER();
}
if (isset($_POST['delete-address'])) {
    $addressid = $_POST['delete-address'];
    $sql = "DELETE FROM addresses WHERE id=$addressid";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        alert("Error deleting address: " . mysqli_error($conn));
    }
    $isRestaurant = $_SESSION['user']['usertype'] == 1;
    if ($isRestaurant) {
        $sql = "UPDATE restaurants SET addressid=-1 WHERE id={$_SESSION['user']['id']}";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            alert("Error updating restaurant address: " . mysqli_error($conn));
        }
    }
    UPDATE_SESSION_USER();
}




if (!IS_USER_LOGGED_IN()) {
    require "./modules/login.php";
} else {
    ?>
    <div class="container px-4 mt-4">
        <div class="row">
            <div class="col-md-4 text-center mb-3">
                <form id="pfp-upload-form" method="post" enctype="multipart/form-data">
                    <img class="rounded mb-2 shadow border border-primary"
                        src="<?= GET_IMAGE($_SESSION['user']['image']) ?>" style="object-fit:cover;">
                    <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                    <div class="input-group mb-3 border border-primary">
                        <input class="form-control form-control-sm file-input" type="file" accept=".jpg, .jpeg, .png"
                            name="file">
                        <button name="pfp-upload" class="btn btn-sm btn-outline-primary" type="submit">Upload</button>
                    </div>
                </form>
            </div>

            <div class="col-md-8">
                <div class="card mb-4 shadow">
                    <div class="card-header text-primary fw-bold">Account Details</div>
                    <div class="card-body">
                        <form id="profileDetailsForm">
                            <div class="mb-3">
                                <label class="small mb-1" for="inputUsername">
                                    Username (how your name will appear to other users on the site)
                                </label>
                                <input readonly class="form-control" id="inputUsername" type="text"
                                    value="<?= $_SESSION['user']['username'] ?? null ?>">
                            </div>
                            <div class="col mb-3">
                                <label class="small mb-1">Full Name</label>
                                <input class="form-control" name="name" type="text" placeholder="Enter your full name"
                                    name="name" value="<?= $_SESSION['user']['name'] ?? null ?>">
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1">Email address</label>
                                <input readonly class="form-control" type="email" placeholder="Enter your email address"
                                    value="<?= $_SESSION['user']['email'] ?? null ?>">
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputPhone">Phone number</label>
                                    <input class="form-control" name="phone" type="tel"
                                        placeholder="Enter your phone number"
                                        value="<?= $_SESSION['user']['phone'] ?? null ?>">
                                </div>
                            </div>
                            <div class="row gap-3 mb-3">
                                <button name="save-changes" class="col btn btn-primary" type="button" name="operation"
                                    value="savechanges">Save changes 💾</button>
                                <button name="logout" class="col btn btn-outline-dark" type="button"> Log
                                    Out 🔌</button>
                                <?php
                                if ($_SESSION['user']['usertype'] == 1) {
                                    ?>
                                    <a href="?page=panel" class="col btn btn-warning hover-scale" type="button"> Control
                                        Panel ⚙️</a>
                                    <?php
                                }
                                ?>
                            </div>
                        </form>

                        <hr>
                        <h2>You addresses: </h2>
                        <?php
                        //fetch adresses
                        $isRestaurant = $_SESSION['user']['usertype'] == 1;
                        if ($isRestaurant) {
                            $sql = "SELECT * FROM addresses WHERE id={$_SESSION['user']['addressid']}";
                            echo "<p class='fs-7'>restaurants can only have 1 address at max</p>";
                        } else {
                            $sql = "SELECT * FROM addresses WHERE userid={$_SESSION['user']['id']}";
                        }
                        $result = mysqli_query($conn, $sql);
                        if (!$result) {
                            echo "<p>No address found</p>";
                        } else {
                            $addresses = mysqli_fetch_all($result, MYSQLI_ASSOC);
                        }
                        if (count($addresses) == 0) {
                            echo "<p>No address found</p>";
                        } else {
                            ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">City</th>
                                        <th scope="col">District</th>
                                        <th scope="col">Street</th>
                                        <th scope="col">✈️</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($addresses as $index => $address) {
                                        $sql = "SELECT * FROM cities WHERE id={$address['cityid']}";
                                        $result = mysqli_query($conn, $sql);
                                        $city = mysqli_fetch_assoc($result);

                                        $sql = "SELECT * FROM districts WHERE id={$address['districtid']}";
                                        $result = mysqli_query($conn, $sql);
                                        $district = mysqli_fetch_assoc($result);

                                        $sql = "SELECT * FROM streets WHERE id={$address['streetid']}";
                                        $result = mysqli_query($conn, $sql);
                                        $street = mysqli_fetch_assoc($result);

                                        ?>
                                        <tr>
                                            <th scope="row"><?= $index + 1 ?></th>
                                            <td><?= $address['name'] ?></td>
                                            <td><?= $city['name'] ?></td>
                                            <td><?= $district['name'] ?></td>
                                            <td><?= $street['name'] ?></td>
                                            <td>
                                                <form method="POST">
                                                    <button type="submit" name="delete-address" value="<?= $address['id'] ?>"
                                                        class="btn btn-sm btn-danger hover-scale btn-shadow">🗑️</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    } ?>
                                </tbody>
                            </table>
                            <?php
                        } ?>
                        <h2>Add address:</h2>
                        <form method="post">
                            <div class="input-group input-group-sm">
                                <input class="form-control" name="name" placeholder="Address Name" type="text" required>
                                <select class="form-select text-center" name="city" id="cities">
                                    <option selected value="-1">Select City</option>
                                    <?php
                                    $sql = "SELECT * FROM cities";
                                    $result = mysqli_query($conn, $sql);
                                    $cities = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                    foreach ($cities as $city) {
                                        echo "<option value='" . $city['id'] . "'>" . $city['name'] . "</option>";
                                    }
                                    ?>
                                </select>
                                <select class="form-select text-center" name="district" id="districts">
                                    <option selected value="-1">Select City</option>
                                </select>
                                <select class="form-select text-center" name="street" id="streets">
                                    <option selected value="-1">Select City</option>
                                </select>
                                <button name="add-address" class="btn btn-success btn-shadow hover-scale"
                                    type="submit">Add📌</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#profileDetailsForm').on('submit', function (event) {
                event.preventDefault();
            });

            $("#profileDetailsForm button[name='logout']").click(function (event) {
                event.preventDefault();


                $.ajax({
                    type: "POST",
                    url: "./api/user/logout.php",
                    success: function (response) {
                        console.log(response)
                        response = JSON.parse(response); // Parse the response to JSON
                        console.log(response)
                        if (response.success) {
                            window.location.href = '?page=profile&refresh=1';
                        } else {
                            console.log(response.error); // Log the error message to the console
                            alert('logout failed:\n' + response.error);
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).error : 'Unknown error';
                        console.log(errorMessage); // Log the error message to the console
                        alert('logout failed:\n' + errorMessage);
                    }
                });
            });

            $("#profileDetailsForm button[name='save-changes']").click(function (event) {
                event.preventDefault();

                var formDataArray = $("#profileDetailsForm").serializeArray();
                var formData = {};
                formDataArray.forEach(function (item) {
                    formData[item.name] = item.value;
                });

                var newData = {
                    name: formData['name'],
                    phone: formData['phone'],
                    province: formData['city'],
                    district: formData['district'],
                    street: formData['street'],
                }

                $.ajax({
                    type: "POST",
                    url: "./api/user/update.php",
                    data: newData,
                    success: function (response) {
                        console.log(response)
                        response = JSON.parse(response); // Parse the response to JSON
                        console.log(response)
                        if (response.success) {
                            alert("succesful");
                            window.location.href = '?page=profile&refresh=1';
                        } else {
                            console.log(response.error); // Log the error message to the console
                            alert('update failed:\n' + response.error);
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).error : 'Unknown error';
                        console.log(errorMessage); // Log the error message to the console
                        alert('update failed:\n' + errorMessage);
                    }
                });
            });

            $('#pfp-upload-form').on('change', '.file-input', function () {
                // Get selected file
                const file = this.files[0];

                // Find the preview image within the same form
                const form = $(this).closest('form');
                const previewImage = form.find('img');

                // Check if file is selected and is an image
                if (file && file.type.startsWith('image')) {
                    // Create a FileReader instance
                    const reader = new FileReader();

                    // Set onload event handler
                    reader.onload = function (e) {
                        // Update the src attribute of the preview image to the loaded image data
                        previewImage.attr('src', e.target.result);
                    };

                    // Read the selected file as Data URL (base64 string)
                    reader.readAsDataURL(file);
                }
            });


            $('#cities').on('change', function () {
                let cityid = $(this).val();
                fetchDistricts(cityid);
            })
            $('#districts').on('change', function () {
                let districtid = $(this).val();
                fetchStreets(districtid);
            })
            function fetchDistricts(cityid) {
                $('#streets').empty();
                $('#streets').append('<option selected value="-1">Select Street</option>');
                $('#districts').empty();
                $('#districts').append('<option selected value="-1">Select District</option>');
                $.ajax({
                    type: "GET",
                    url: "./api/misc/districts.php",
                    data: {
                        cityid: cityid
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            let districts = response.districts;
                            if (Array.isArray(districts)) {
                                districts.forEach(function (district) {
                                    $('#districts').append('<option value="' + district['id'] + '">' + district['name'] + '</option>');
                                });
                            }
                        } else {
                            console.log("Error:", response.error);
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.responseText ? xhr.responseText : 'Unknown error';
                        console.log("Error:", errorMessage); // Log the error message to the console
                        alert('An error occurred:\n' + errorMessage);
                    }
                });
            }
            function fetchStreets(districtid) {
                $('#streets').empty();
                $('#streets').append('<option selected value="">Select Street</option>');
                $.ajax({
                    type: "GET",
                    url: "./api/misc/streets.php",
                    data: {
                        districtid: districtid
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            let streets = response.streets;
                            if (Array.isArray(streets)) {
                                streets.forEach(function (street) {
                                    $('#streets').append('<option value="' + street['id'] + '">' + street['name'] + '</option>');
                                });
                            }
                        } else {
                            console.log("Error:", response.error);
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.responseText ? xhr.responseText : 'Unknown error';
                        console.log("Error:", errorMessage); // Log the error message to the console
                        alert('An error occurred:\n' + errorMessage);
                    }
                });
            }
        });
    </script>

    <?php
}
?>