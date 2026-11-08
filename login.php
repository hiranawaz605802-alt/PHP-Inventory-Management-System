<?php
session_start();
include("Database.php");

$db = new Database();

$conn = $db->connection();
$email = $_POST["email"] ?? '';
$password = $_POST["password"] ?? '';

// user check
echo$sql = "SELECT * FROM entity_asad_users WHERE email='$email'";
$res = $conn->query($sql);

if($res && $res->num_rows > 0){

    $row = $res->fetch_assoc();

    // correct password
    if($row['password'] == $password){

        $_SESSION["user_email"] = $email;
        header("Location: index1.php");
        exit();

    } else {

        // ❌ WRONG PASSWORD → EMAIL SEND

        $subject = "Login Alert";
        $message = "Someone tried to login with wrong password on your account.";

   //     mail($email, $subject, $message);

       
        echo "Wrong password ❌ (Email sent to you)". header("Location: email_rong.php");
    }

} else {
    echo "Email not found ❌";
}
?>