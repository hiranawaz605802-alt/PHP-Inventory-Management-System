
<?php

include_once("Database.php");


// =====================================================
// DATABASE CONNECTION
// =====================================================

$db = new Database();
$conn = $db->connection();


// =====================================================
// CHECK FORM SUBMISSION
// =====================================================

if (isset($_POST["save_user"])) {


    // =================================================
    // GET FORM DATA
    // =================================================

    $name = trim($_POST["name"] ?? "");

    $email = trim($_POST["email"] ?? "");

    $password = $_POST["password"] ?? "";

    $status = trim($_POST["status"] ?? "Active");


    // =================================================
    // VALIDATION
    // =================================================

    if ($name === "" || $email === "" || $password === "") {

        header(
            "Location: users_view.php?error=" .
            urlencode("All fields are required.")
        );

        exit;
    }


    // =================================================
    // EMAIL VALIDATION
    // =================================================

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        header(
            "Location: users_add.php?error=" .
            urlencode("Please enter a valid email address.")
        );

        exit;
    }


    // =================================================
    // PASSWORD VALIDATION
    // =================================================

    if (strlen($password) < 6) {

        header(
            "Location: user_add.php?error=" .
            urlencode("Password must be at least 6 characters.")
        );

        exit;
    }


    // =================================================
    // STATUS VALIDATION
    // =================================================

    if ($status !== "Active" && $status !== "Inactive") {

        $status = "Active";
    }


    // =================================================
    // CHECK EMAIL
    // =================================================

    echo$check_sql = "
        SELECT users_id
        FROM entity_asad_users
        WHERE email = ?
        LIMIT 1
    ";


    $check_stmt = mysqli_prepare(
        $conn,
        $check_sql
    );


    if (!$check_stmt) {

        header(
            "Location: user_add.php?error=" .
            urlencode(mysqli_error($conn))
        );

        exit;
    }


    mysqli_stmt_bind_param(
        $check_stmt,
        "s",
        $email
    );


    mysqli_stmt_execute(
        $check_stmt
    );


    $check_result = mysqli_stmt_get_result(
        $check_stmt
    );


    // =================================================
    // EMAIL ALREADY EXISTS
    // =================================================

    if (mysqli_num_rows($check_result) > 0) {

        mysqli_stmt_close($check_stmt);

        header(
            "Location: user_add.php?error=" .
            urlencode("This email already exists.")
        );

        exit;
    }


    mysqli_stmt_close($check_stmt);


    // =================================================
    // HASH PASSWORD
    // =================================================

    $hashed_password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );


    // =================================================
    // INSERT USER
    // =================================================

    $sql = "
        INSERT INTO entity_asad_users
        (
            name,
            email,
            password,
            status,
            created_at
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            NOW()
        )
    ";


    $stmt = mysqli_prepare(
        $conn,
        $sql
    );


    // =================================================
    // CHECK PREPARE ERROR
    // =================================================

    if (!$stmt) {

        header(
            "Location: user_add.php?error=" .
            urlencode(mysqli_error($conn))
        );

        exit;
    }


    // =================================================
    // BIND VALUES
    // =================================================

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $name,
        $email,
        $hashed_password,
        $status
    );


    // =================================================
    // INSERT
    // =================================================

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        header(
            "Location: User_view_product.php"
        );

        exit;

    } else {

        $error = mysqli_stmt_error($stmt);

        mysqli_stmt_close($stmt);

        header(
            "Location: user_add.php?error=" .
            urlencode($error)
        );

        exit;
    }


} else {


    // =================================================
    // DIRECT ACCESS
    // =================================================

    header(
        "Location: user_add.php"
    );

    exit;
}
