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
                        <input class="form-control form-control-lg file-input" type="file" accept=".jpg, .jpeg, .png"
                            name="file">
                        <button name="pfp-upload" class="btn btn-outline-primary" type="submit">Upload</button>
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

        });
    </script>

    <?php
}
?>