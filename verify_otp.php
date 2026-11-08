<?php

session_start();

include("Database.php");

$db = new Database();
$conn = $db->connection();

// OTP form se
if(isset($_POST['otp']))
{
    $otp = $_POST['otp'];

    // session se user id (important)
    $user_id = $_SESSION['temp_user'];

    // check otp
    $sql = "SELECT * FROM otp_codes 
            WHERE user_id='$user_id' 
            AND otp='$otp'";

    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0)
    {
        echo "OTP Verified Successfully ✅";

        // optional: user activate kar do
        mysqli_query($conn,
        "UPDATE users SET status='active' WHERE id='$user_id'"
        );

        // clear session
        unset($_SESSION['temp_user']);
    }
    else
    {
        echo "Invalid OTP ❌";
    }
}

?>

<!-- OTP FORM -->
<form method="POST">

    <input type="text" name="otp" placeholder="Enter OTP" required>

    <br><br>

    <button type="submit">Verify OTP</button>

</form>