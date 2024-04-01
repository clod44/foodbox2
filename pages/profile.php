<?php
if (!IS_USER_LOGGED_IN()) {
    require "./modules/login.php";
} else {
    ?>
    <div class="container px-4 mt-4">
        <div class="row">
            <div class="col-md-4 text-center mb-3">
                <img class="rounded mb-2" src="./media/sample.jpg">
                <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div><button
                    class="btn btn-primary" type="button">Upload new image</button>
            </div>

            <div class="col-md-8">
                <div class="card mb-4">
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
                            <div class="row gap-2 mb-3">
                                <label class="small mb-1">Address</label>
                                <select class="mx-3 row form-select form-select-sm text-center" name="province">
                                </select>
                                <select class="mx-3 row form-select form-select-sm text-center" name="district">
                                </select>
                                <select class="mx-3 row form-select form-select-sm text-center" name="street">
                                </select>
                            </div>
                            <div class="row gap-3 mb-3">
                                <button name="save-changes" class="col btn btn-primary" type="button" name="operation"
                                    value="savechanges">Save changes 💾</button>
                                <button name="logout" class="col btn btn-outline-dark" type="button"> Log
                                    Out 🔌</button>
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
                            alert("succesful");
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
                    province: formData['province'],
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



            function fetchProvinces() {
                $.ajax({
                    url: './api/misc/provinces.php',
                    method: 'POST',
                    success: function (response) {
                        response = JSON.parse(response);
                        $("#profileDetailsForm select[name='province']").empty();
                        response.provinces.forEach(function (province) {
                            $("#profileDetailsForm select[name='province']").append('<option value="' + province.id + '">' + province.name + '</option>');
                        });
                        $("#profileDetailsForm select[name='province']").val(<?= $_SESSION["user"]["province_id"]; ?>);
                    },
                    error: function (xhr, status, error) {
                        console.error('Failed to fetch districts:', error);
                    }
                });
            }
            function fetchDistricts(provinceId) {
                $.ajax({
                    url: './api/misc/districts.php',
                    method: 'POST',
                    data: { province: provinceId },
                    success: function (response) {
                        response = JSON.parse(response);
                        $("#profileDetailsForm select[name='district']").empty();
                        response.districts.forEach(function (district) {
                            $("#profileDetailsForm select[name='district']").append('<option value="' + district.id + '">' + district.name + '</option>');
                        });
                        if ($("#profileDetailsForm select[name='province']").val() == <?= $_SESSION["user"]["province_id"]; ?>) {
                            $("#profileDetailsForm select[name='district']").val(<?= $_SESSION["user"]["district_id"]; ?>);
                        } else {
                            $("#profileDetailsForm select[name='district']").append('<option value="-1" selected> Select District </option>');
                            $("#profileDetailsForm select[name='street']").empty();
                            $("#profileDetailsForm select[name='street']").append('<option value="-1" selected> Select Street </option>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Failed to fetch districts:', error);
                    }
                });
            }
            function fetchStreets(districtId) {
                $.ajax({
                    url: './api/misc/streets.php',
                    method: 'POST',
                    data: { district: districtId },
                    success: function (response) {
                        response = JSON.parse(response);
                        $("#profileDetailsForm select[name='street']").empty();
                        response.streets.forEach(function (street) {
                            $("#profileDetailsForm select[name='street']").append('<option value="' + street.id + '">' + street.name + '</option>');
                        });
                        if ($("#profileDetailsForm select[name='district']").val() == <?= $_SESSION["user"]["district_id"]; ?>) {
                            $("#profileDetailsForm select[name='street']").val(<?= $_SESSION["user"]["street_id"]; ?>);
                        } else {
                            $("#profileDetailsForm select[name='street']").append('<option value="-1" selected> Select Street </option>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Failed to fetch streets:', error);
                    }
                });
            }
            fetchProvinces();
            fetchDistricts(<?= $_SESSION["user"]["province_id"]; ?>);
            fetchStreets(<?= $_SESSION["user"]["district_id"]; ?>);




            $("#profileDetailsForm select[name='district']").on('change', function () {
                var districtId = $(this).val();
                // Fetch streets based on selected district
                fetchStreets(districtId); // Corrected the parameter to pass the districtId
            });

            $("#profileDetailsForm select[name='province']").on('change', function () {
                var provinceId = $(this).val();
                fetchDistricts(provinceId); // Fetch districts based on selected province
            });
        });

    </script>
    <?php
}
?>