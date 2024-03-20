<?php
if (SESSION_READ("user") == null) {
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
                        <form id="profileEdit">
                            <div class="mb-3">
                                <label class="small mb-1" for="inputUsername">
                                    Username (how your name will appear to other users on the site)
                                </label>
                                <input readonly class="form-control" id="inputUsername" type="text"
                                    value="<?= $_SESSION['user']['username'] ?? null ?>">
                            </div>
                            <div class="col mb-3">
                                <label class="small mb-1" for="inputFullName">Full Name
                                    name</label>
                                <input class="form-control" id="inputFullName" type="text"
                                    placeholder="Enter your full name" value="<?= $_SESSION['user']['name'] ?? null ?>">
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1" for="inputEmailAddress">Email address</label>
                                <input class="form-control" id="inputEmailAddress" type="email"
                                    placeholder="Enter your email address"
                                    value="<?= $_SESSION['user']['email'] ?? null ?>">
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputPhone">Phone number</label>
                                    <input class="form-control" id="inputPhone" type="tel"
                                        placeholder="Enter your phone number" value="555-123-4567">
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputBirthday">Birthday</label>
                                    <input class="form-control" id="inputBirthday" type="text" name="birthday"
                                        placeholder="Enter your birthday" value="06/10/1988">
                                </div>
                            </div>
                            <div class="row gap-2 mb-3">
                                <label class="small mb-1">Address</label>
                                <select class="mx-3 row form-select form-select-sm text-center" id="select-province">
                                    <option value="-1">Select Province</option>
                                </select>
                                <select class="mx-3 row form-select form-select-sm text-center" id="select-district">
                                    <option value="-1">Select District</option>
                                </select>
                                <select class="mx-3 row form-select form-select-sm text-center" id="select-street">
                                    <option value="-1">Select Street</option>
                                </select>
                            </div>
                            <div class="row gap-3 mb-3">
                                <button class="col btn btn-primary" type="button" name="operation" value="savechanges">Save
                                    changes 💾</button>
                                <button id="logoutBtn" class="col btn btn-outline-dark" type="button"> Log
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
            $('#profileEdit').on('submit', function (event) {
                event.preventDefault();
            });
            $('#logoutBtn').on('click', function (event) {
                event.preventDefault();

                $.ajax({
                    url: './api/user/logout.php',
                    method: 'POST',
                    success: function (response) {
                        //response = JSON.parse(response);
                        console.log('Logout successful');
                        console.log(response);
                        //window.location.href = '?page=home';
                    },
                    error: function (xhr, status, error) {
                        console.error('Logout failed:', error);
                    }
                });
            });


            function fetchProvinces() {
                $.ajax({
                    url: './api/misc/provinces.php',
                    method: 'POST',
                    success: function (response) {
                        response = JSON.parse(response);
                        $('#select-provinces').empty();
                        response.provinces.forEach(function (province) {
                            $('#select-province').append('<option value="' + province.id + '">' + province.name + '</option>');
                        });
                        $('#select-provinces').append('<option value="-1"> Select Province</option > ');
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
                        $('#select-district').empty();
                        response.districts.forEach(function (district) {
                            $('#select-district').append('<option value="' + district.id + '">' + district.name + '</option>');
                        });
                        $('#select-district').append('<option value="-1">Select District</option>');
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
                        $('#select-street').empty();
                        response.streets.forEach(function (street) {
                            $('#select-street').append('<option value="' + street.id + '">' + street.name + '</option>');
                        });
                        $('#select-street').append('<option value="-1">Select Street</option>');
                    },
                    error: function (xhr, status, error) {
                        console.error('Failed to fetch streets:', error);
                    }
                });
            }
            $('#select-province').on('change', function () {
                var provinceId = $(this).val();
                fetchDistricts(provinceId);
                fetchStreets(1);
            });
            $('#select-district').on('change', function () {
                var districtId = $(this).val();
                // Fetch streets based on selected district
                fetchStreets(districtId);
            });
            fetchProvinces();
            fetchDistricts(<?php echo $_SESSION["user"]["province_id"]; ?>);
            fetchStreets(<?php echo $_SESSION["user"]["district_id"]; ?>);

            $('#select-province').val(<?php echo $_SESSION["user"]["province_id"]; ?>);
            $('#select-district').val(<?php echo $_SESSION["user"]["district_id"]; ?>);
            $('#select-street').val(<?php echo $_SESSION["user"]["street_id"]; ?>);

        });

    </script>
    <?php
}
?>