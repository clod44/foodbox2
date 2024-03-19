<section>
    <div class="container-fluid p-4">
        <div class="row d-flex justify-content-center align-items-center gap-4">
            <div class="col-md-8 col-lg-5 col-xl-4 h-100">
                <img src="./media/sample.jpg" class="w-100 h-100 rounded" style="object-fit:cover;" alt="Sample image">
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">

                <!-- Pills navs -->
                <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="tab-login" data-bs-toggle="pill" href="#pills-login"
                            role="tab">Login</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-register" data-bs-toggle="pill" href="#pills-register"
                            role="tab">Register</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="pills-login" role="tabpanel">
                        <form id="loginForm">
                            <div class="text-center mb-3">
                                <p>Sign in</p>
                            </div>

                            <!-- Email input -->
                            <div class="form-outline mb-4">
                                <input type="text" id="loginUsername" class="form-control" name="username"
                                    placeholder="jam3s" required />
                                <label class="form-label" for="loginUsername">Username</label>
                            </div>

                            <!-- Password input -->
                            <div class="form-outline mb-4">
                                <input type="password" id="loginPassword" class="form-control" name="password"
                                    placeholder="james123" required />
                                <label class="form-label" for="loginPassword">Password</label>
                            </div>


                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary btn-block mb-4 w-100">Sign in</button>

                            <!-- Register buttons -->
                            <div class="text-center">
                                <p>Not a member? <a href="#!">Register</a></p>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="pills-register" role="tabpanel">
                        <form id="registerForm">
                            <div class="text-center mb-3">
                                <p>Sign up</p>
                            </div>

                            <!-- Name input -->
                            <div class="form-outline mb-4">
                                <input type="text" id="registerName" class="form-control" name="name"
                                    placeholder="James McCall" required />
                                <label class="form-label" for="registerName">Name</label>
                            </div>

                            <!-- Username input -->
                            <div class="form-outline mb-4">
                                <input type="text" id="registerUsername" class="form-control" name="username"
                                    placeholder="jam3s" required />
                                <label class="form-label" for="registerUsername">Username</label>
                            </div>

                            <!-- Email input -->
                            <div class="form-outline mb-4">
                                <input type="email" id="registerEmail" class="form-control" name="email"
                                    placeholder="james@gmail.com" required />
                                <label class="form-label" for="registerEmail">Email</label>
                            </div>

                            <!-- Password input -->
                            <div class="form-outline mb-4">
                                <input type="password" id="registerPassword" class="form-control" name="password"
                                    placeholder="james123" required />
                                <label class="form-label" for="registerPassword">Password</label>
                            </div>

                            <!-- Repeat Password input -->
                            <div class="form-outline mb-4">
                                <input type="password" id="registerRepeatPassword" class="form-control"
                                    name="password-repeat" placeholder="james123" required />
                                <label class="form-label" for="registerRepeatPassword">Repeat password</label>
                            </div>

                            <!-- Checkbox -->
                            <div class="form-check d-flex justify-content-center mb-4">
                                <input class="form-check-input me-2" type="checkbox" value="" id="registerCheck"
                                    name="tos" checked required />
                                <label class="form-check-label" for="registerCheck">
                                    I have read and agree to the terms
                                </label>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary btn-block mb-3 w-100">Sign in</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Function to handle login
        $("#loginForm").submit(function (event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                type: "POST",
                url: "./api/user/login.php",
                data: formData,
                success: function (response) {
                    response = JSON.parse(response); // Parse the response to JSON
                    console.log(response)
                    if (response.success) {
                        window.location.href = '?page=profile';
                    } else {
                        console.log(response.error); // Log the error message to the console
                        alert('Login failed:\n' + response.error);
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).error : 'Unknown error';
                    console.log(errorMessage); // Log the error message to the console
                    alert('Login failed:\n' + errorMessage);
                }
            });
        });




        // Function to handle registration
        $("#registerForm").submit(function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var password = $("#registerPassword").val();
            var repeatPassword = $("#registerRepeatPassword").val();

            // Check if passwords match
            if (password !== repeatPassword) {
                alert('Passwords do not match. Please enter matching passwords.');
                return; // Stop form submission
            }

            $.ajax({
                type: "POST",
                url: "./api/user/register.php",
                data: formData,
                success: function (response) {
                    response = JSON.parse(response); // Parse the response to JSON
                    console.log(response); // Log the response to the console
                    if (response.success) {
                        window.location.href = '?page=profile'; // Redirect to profile page
                    } else {
                        ('Registration failed:\n' + response.error);
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).error : 'Unknown error';
                    console.log(errorMessage); // Log the error message to the console
                    alert('Registration failed:\n' + errorMessage);
                }
            });

        });

    </script>

</section>