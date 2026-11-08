<?php
session_start();
include("Database.php");

$email = $_SESSION['email'];
$message = "";

// update password
if(isset($_POST['reset'])){

    $pass = $_POST['password'];

    $sql = "UPDATE entity_asad_users 
            SET password='$pass' 
            WHERE email='$email'";

    if($conn->query($sql)){
        header("Location: index.php");
        exit();
    } else {
        $message = "Error updating password ❌";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Reset Password</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">
<div class="row h-100 align-items-center justify-content-center" style="min-height:100vh;">

<div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">

<div class="bg-secondary rounded p-4 p-sm-5">

<h3 class="text-primary text-center mb-4">Reset Password</h3>

<!-- 🔥 MESSAGE -->
<?php if($message != ""): ?>
    <div class="alert alert-danger text-center">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<!-- 🔥 FORM -->
<form method="POST">

    <div class="form-floating mb-3">
        <input type="password" name="password" class="form-control" placeholder="New Password" required>
        <label>New Password</label>
    </div>

    <button name="reset" class="btn btn-primary w-100 py-3">
        Update Password
    </button>

</form>

</div>

</div>
</div>
</div>

</body>
</html>