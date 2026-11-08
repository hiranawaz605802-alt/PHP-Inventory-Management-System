<?php

session_start();

include("Database.php");

$db = new Database();

$conn = $db->connection();

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $email = $_POST['email'];

    $password = md5($_POST['password']);

    // check user
    $sql = "SELECT * FROM entity_asad_users 
            WHERE email='$email' 
            AND password='$password'";

    $res = mysqli_query($conn,$sql);

    if(mysqli_num_rows($res) > 0)
    {
        $entity_asad_users = mysqli_fetch_assoc($res);

        $user_id = $entity_asad_users['id'];

        // generate otp
        $otp = rand(100000,999999);

        // save otp
        $insert = "INSERT INTO otp_codes(user_id,otp)
                   VALUES('$user_id','$otp')";

        mysqli_query($conn,$insert);

        // session
        $_SESSION['temp_user'] = $user_id;

        // send email
        mail(
            $email,
            "OTP Verification",
            "Your OTP is: ".$otp
        );

        // redirect
        header("Location: verify_otp.php");
        exit();
    }
    else
    {
        echo "Invalid Email or Password";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login Form</h2>

<form method="POST">

    <input type="email"
           name="email"
           placeholder="Enter Email"
           required>

    <br><br>

    <input type="password"
           name="password"
           placeholder="Enter Password"
           required>

    <br><br>

    <button type="submit">
        Login & Send OTP
    </button>

</form>

</body>
</html>