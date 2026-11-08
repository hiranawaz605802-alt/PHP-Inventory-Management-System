<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Login - Admin Panel</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Style -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
<div class="container-fluid bg-dark min-vh-100 d-flex align-items-center justify-content-center">

    <div class="col-12 col-sm-8 col-md-6 col-lg-4">

        <div class="bg-secondary rounded p-4 p-sm-5 shadow">

            <!-- Logo -->
            <div class="text-center mb-4">
                <h3 class="text-primary">
                    <i class="fa fa-user-edit me-2"></i>Admin Panel
                </h3>
                <h5 class="text-white">Sign In</h5>
            </div>

            <!-- Error Message -->
            <?php
            if(isset($_SESSION['msg'])){
                echo "<div class='alert alert-danger'>".$_SESSION['msg']."</div>";
                unset($_SESSION['msg']);
            }
            ?>

            <!-- Login Form -->
            <form method="POST" action="login.php">

                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                    <label for="email">Email Address</label>
                </div>

                <div class="form-floating mb-4">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>

                <div class="d-flex justify-content-between mb-3 text-white">
                    <div>
                        <input type="checkbox"> Remember me
                    </div>
                    <a href="#" class="text-primary">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    Login
                </button>

                <p class="text-center text-white mt-3 mb-0">
                    Don't have account? <a href="#" class="text-primary">Sign Up</a>
                </p>

            </form>

        </div>

    </div>

</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>