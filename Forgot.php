<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Forgot Password</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- CSS (same as your template) -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">

        <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">

            <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">

                <h3 class="text-primary text-center mb-4">
                    Forgot Password
                </h3>

                <!-- 🔥 YOUR OTP FORM START -->
                <form method="POST" action="send_otp.php">

                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="email"
                            placeholder="Enter Email" required>
                        <label for="email">Enter Email</label>
                    </div>

                    <button type="submit" name="send"
                        class="btn btn-primary py-3 w-100 mb-3">
                        Send OTP
                    </button>

                </form>
                <!-- 🔥 FORM END -->

                <div class="text-center">
                    <a href="login.php">Back to Login</a>
                </div>

            </div>

        </div>

    </div>
</div>

</body>
</html>