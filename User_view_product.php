<?php

include("Database.php");

$db = new Database();
$conn = $db->connection();


// =====================================================
// CURRENT PAGE
// =====================================================

$current_page = basename($_SERVER["PHP_SELF"]);


// =====================================================
// MESSAGE
// =====================================================

$message = "";
$message_type = "";


// =====================================================
// DELETE USER
// =====================================================

if (isset($_GET["delete_id"])) {

    $delete_id = (int) $_GET["delete_id"];

    if ($delete_id > 0) {

        $delete_stmt = mysqli_prepare(
            $conn,
            "DELETE FROM entity_asad_users WHERE users_id = ?"
        );

        if ($delete_stmt) {

            mysqli_stmt_bind_param(
                $delete_stmt,
                "i",
                $delete_id
            );

            $delete_result = mysqli_stmt_execute($delete_stmt);

            if ($delete_result) {

                mysqli_stmt_close($delete_stmt);

                echo "<script>
                    alert('User Deleted Successfully');
                    window.location.href='" .
                    htmlspecialchars(
                        $current_page,
                        ENT_QUOTES,
                        "UTF-8"
                    ) .
                    "';
                </script>";

                exit();

            } else {

                $error = mysqli_stmt_error($delete_stmt);

                mysqli_stmt_close($delete_stmt);

                echo "<script>
                    alert('Delete Error: " .
                    addslashes($error) .
                    "');
                    window.location.href='" .
                    htmlspecialchars(
                        $current_page,
                        ENT_QUOTES,
                        "UTF-8"
                    ) .
                    "';
                </script>";

                exit();
            }

        } else {

            echo "<script>
                alert('Delete Query Prepare Error: " .
                addslashes(mysqli_error($conn)) .
                "');
                window.location.href='" .
                htmlspecialchars(
                    $current_page,
                    ENT_QUOTES,
                    "UTF-8"
                ) .
                "';
            </script>";

            exit();
        }

    } else {

        echo "<script>
            alert('Invalid User ID');
            window.location.href='" .
            htmlspecialchars(
                $current_page,
                ENT_QUOTES,
                "UTF-8"
            ) .
            "';
        </script>";

        exit();
    }
}


// =====================================================
// UPDATE USER
// =====================================================

if (isset($_POST["update_user"])) {

    $users_id = isset($_POST["users_id"])
        ? (int) $_POST["users_id"]
        : 0;

    $name = isset($_POST["name"])
        ? trim($_POST["name"])
        : "";

    $email = isset($_POST["email"])
        ? trim($_POST["email"])
        : "";

    $password = isset($_POST["password"])
        ? $_POST["password"]
        : "";

    $status = isset($_POST["status"])
        ? trim($_POST["status"])
        : "";


    // =================================================
    // VALIDATION
    // =================================================

    if ($users_id <= 0) {

        $message = "Invalid User ID.";
        $message_type = "danger";

    } elseif ($name === "") {

        $message = "Please enter User Name.";
        $message_type = "danger";

    } elseif ($email === "") {

        $message = "Please enter Email.";
        $message_type = "danger";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid Email.";
        $message_type = "danger";

    } elseif ($status === "") {

        $message = "Please select User Status.";
        $message_type = "danger";

    } else {

        // =================================================
        // UPDATE WITH PASSWORD
        // =================================================

        if ($password !== "") {

            $update_stmt = mysqli_prepare(
                $conn,
                "
                UPDATE entity_asad_users
                SET
                    name = ?,
                    email = ?,
                    password = ?,
                    status = ?
                WHERE users_id = ?
                "
            );

            if (!$update_stmt) {

                $message =
                    "Update Prepare Error: " .
                    mysqli_error($conn);

                $message_type = "danger";

            } else {

                mysqli_stmt_bind_param(
                    $update_stmt,
                    "ssssi",
                    $name,
                    $email,
                    $password,
                    $status,
                    $users_id
                );

                $update_result =
                    mysqli_stmt_execute($update_stmt);

                if ($update_result) {

                    mysqli_stmt_close($update_stmt);

                    echo "<script>
                        alert('User Updated Successfully');
                        window.location.href='" .
                        htmlspecialchars(
                            $current_page,
                            ENT_QUOTES,
                            "UTF-8"
                        ) .
                        "';
                    </script>";

                    exit();

                } else {

                    $error =
                        mysqli_stmt_error($update_stmt);

                    mysqli_stmt_close($update_stmt);

                    echo "<script>
                        alert('Password Update Error: " .
                        addslashes($error) .
                        "');
                        window.location.href='" .
                        htmlspecialchars(
                            $current_page,
                            ENT_QUOTES,
                            "UTF-8"
                        ) .
                        "';
                    </script>";

                    exit();
                }
            }

        } else {

            // =================================================
            // UPDATE WITHOUT PASSWORD
            // =================================================

            $update_stmt = mysqli_prepare(
                $conn,
                "
                UPDATE entity_asad_users
                SET
                    name = ?,
                    email = ?,
                    status = ?
                WHERE users_id = ?
                "
            );

            if (!$update_stmt) {

                $message =
                    "Update Prepare Error: " .
                    mysqli_error($conn);

                $message_type = "danger";

            } else {

                mysqli_stmt_bind_param(
                    $update_stmt,
                    "sssi",
                    $name,
                    $email,
                    $status,
                    $users_id
                );

                $update_result =
                    mysqli_stmt_execute($update_stmt);

                if ($update_result) {

                    mysqli_stmt_close($update_stmt);

                    echo "<script>
                        alert('User Updated Successfully');
                        window.location.href='" .
                        htmlspecialchars(
                            $current_page,
                            ENT_QUOTES,
                            "UTF-8"
                        ) .
                        "';
                    </script>";

                    exit();

                } else {

                    $error =
                        mysqli_stmt_error($update_stmt);

                    mysqli_stmt_close($update_stmt);

                    echo "<script>
                        alert('User Update Error: " .
                        addslashes($error) .
                        "');
                        window.location.href='" .
                        htmlspecialchars(
                            $current_page,
                            ENT_QUOTES,
                            "UTF-8"
                        ) .
                        "';
                    </script>";

                    exit();
                }
            }
        }
    }
}


// =====================================================
// GET EDIT USER
// =====================================================

$edit_record = null;

if (isset($_GET["edit_id"])) {

    $edit_id = (int) $_GET["edit_id"];

    if ($edit_id > 0) {

        $edit_stmt = mysqli_prepare(
            $conn,
            "
            SELECT
                users_id,
                name,
                email,
                password,
                status,
                created_at
            FROM entity_asad_users
            WHERE users_id = ?
            LIMIT 1
            "
        );

        if (!$edit_stmt) {

            die(
                "Edit Query Error: " .
                mysqli_error($conn)
            );
        }

        mysqli_stmt_bind_param(
            $edit_stmt,
            "i",
            $edit_id
        );

        mysqli_stmt_execute($edit_stmt);

        $edit_result =
            mysqli_stmt_get_result($edit_stmt);

        if (
            $edit_result &&
            mysqli_num_rows($edit_result) > 0
        ) {

            $edit_record =
                mysqli_fetch_assoc($edit_result);

        } else {

            mysqli_stmt_close($edit_stmt);

            echo "<script>
                alert('User Record Not Found');
                window.location.href='" .
                htmlspecialchars(
                    $current_page,
                    ENT_QUOTES,
                    "UTF-8"
                ) .
                "';
            </script>";

            exit();
        }

        mysqli_stmt_close($edit_stmt);
    }
}


// =====================================================
// GET ALL USERS
// =====================================================

$sql = "
    SELECT
        users_id,
        name,
        email,
        password,
        status,
        created_at
    FROM entity_asad_users
    ORDER BY users_id DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {

    die(
        "User View Error: " .
        mysqli_error($conn)
    );
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>User View</title>

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <!-- =====================================================
         GOOGLE FONT
    ====================================================== -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap"
        rel="stylesheet"
    >

    <!-- =====================================================
         FONT AWESOME
    ====================================================== -->

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
        rel="stylesheet"
    >

    <!-- =====================================================
         BOOTSTRAP ICONS
    ====================================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <!-- =====================================================
         BOOTSTRAP
    ====================================================== -->

    <link
        href="css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- =====================================================
         DARKPAN
    ====================================================== -->

    <link
        href="css/style.css"
        rel="stylesheet"
    >


<style>

/* =========================================================
   GLOBAL
========================================================= */

html,
body {

    width: 100% !important;
    max-width: 100% !important;
    min-height: 100% !important;

    margin: 0 !important;
    padding: 0 !important;

    overflow-x: hidden !important;
}

body {

    background: #000 !important;
}

*,
*::before,
*::after {

    box-sizing: border-box;
}


/* =========================================================
   MAIN CONTAINER
========================================================= */

.main-container {

    width: 100% !important;
    max-width: 100% !important;

    min-height: 100vh !important;

    margin: 0 !important;
    padding: 0 !important;

    position: relative !important;

    overflow-x: hidden !important;

    background: #000 !important;
}


/* =========================================================
   SIDEBAR
========================================================= */

.main-container .sidebar {

    width: 250px !important;
    min-width: 250px !important;

    height: 100vh !important;

    position: fixed !important;

    top: 0 !important;
    left: 0 !important;
    bottom: 0 !important;

    margin: 0 !important;
    padding: 0 !important;

    z-index: 99999 !important;

    overflow-y: auto !important;
    overflow-x: hidden !important;

    transition: left 0.3s ease !important;
}


/* =========================================================
   CONTENT
========================================================= */

.content {

    width: calc(100% - 250px) !important;
    max-width: calc(100% - 250px) !important;

    min-height: 100vh !important;

    margin-left: 250px !important;

    padding: 0 !important;

    position: relative !important;

    background: #000 !important;

    overflow-x: hidden !important;

    transition:
        margin-left 0.3s ease,
        width 0.3s ease !important;
}


/* =========================================================
   NAVBAR
========================================================= */

.content > .navbar {

    width: 100% !important;

    min-height: 70px !important;

    margin: 0 !important;

    padding-left: 20px !important;
    padding-right: 20px !important;

    position: relative !important;

    top: 0 !important;
    left: 0 !important;
    right: 0 !important;

    transform: none !important;

    float: none !important;

    z-index: 100000 !important;
}


/* =========================================================
   TOGGLER
========================================================= */

.sidebar-toggler,
.navbar-toggler {

    position: relative !important;

    z-index: 100001 !important;

    cursor: pointer !important;

    pointer-events: auto !important;

    border: 0 !important;

    outline: none !important;
}


/* =========================================================
   DESKTOP COLLAPSE
========================================================= */

@media (min-width: 992px) {

    body.sidebar-collapsed .main-container .sidebar {

        left: -250px !important;
    }

    body.sidebar-collapsed .content {

        width: 100% !important;
        max-width: 100% !important;

        margin-left: 0 !important;
    }

}


/* =========================================================
   USER CONTAINER
========================================================= */

.user-container {

    width: 100% !important;
    max-width: 100% !important;

    margin: 0 !important;

    padding: 18px !important;

    overflow: hidden !important;

    background: #000 !important;
}


/* =========================================================
   TABLE BOX
========================================================= */

.table-box {

    width: 100% !important;
    max-width: 100% !important;

    background: #191c24 !important;

    border-radius: 8px !important;

    padding: 18px !important;

    margin: 0 !important;

    overflow: hidden !important;
}


/* =========================================================
   TITLE
========================================================= */

.table-title {

    color: #fff !important;

    font-size: 17px !important;

    font-weight: 600 !important;

    margin: 0 0 15px 0 !important;
}


/* =========================================================
   TABLE WRAPPER
========================================================= */

.user-table-wrapper {

    width: 100% !important;
    max-width: 100% !important;

    margin: 0 !important;
    padding: 0 !important;

    overflow-x: auto !important;

    overflow-y: hidden !important;

    -webkit-overflow-scrolling: touch;
}


/* =========================================================
   TABLE
========================================================= */

.user-table {

    width: 100% !important;
    max-width: 100% !important;

    min-width: 0 !important;

    margin: 0 !important;

    table-layout: fixed !important;

    border-collapse: collapse !important;
}

.user-table th,
.user-table td {

    padding: 10px 8px !important;

    vertical-align: middle !important;

    font-size: 13px !important;
}

.user-table th {

    color: #8f9bc0 !important;

    font-weight: 600 !important;

    white-space: normal !important;
}

.user-table td {

    color: #8f9bc0 !important;

    white-space: normal !important;

    word-break: break-word !important;
}


/* =========================================================
   COLUMN WIDTH
========================================================= */

.user-table th:nth-child(1),
.user-table td:nth-child(1) {

    width: 8%;
}

.user-table th:nth-child(2),
.user-table td:nth-child(2) {

    width: 13%;
}

.user-table th:nth-child(3),
.user-table td:nth-child(3) {

    width: 22%;
}

.user-table th:nth-child(4),
.user-table td:nth-child(4) {

    width: 18%;
}

.user-table th:nth-child(5),
.user-table td:nth-child(5) {

    width: 12%;
}

.user-table th:nth-child(6),
.user-table td:nth-child(6) {

    width: 15%;
}

.user-table th:nth-child(7),
.user-table td:nth-child(7) {

    width: 12%;
}


/* =========================================================
   PASSWORD
========================================================= */

.password-text {

    font-size: 11px;

    color: #8f9bc0;

    word-break: break-all;
}


/* =========================================================
   STATUS
========================================================= */

.status-badge {

    display: inline-block;

    padding: 4px 9px;

    border-radius: 20px;

    font-size: 11px;

    font-weight: 600;

    text-transform: capitalize;
}

.status-active {

    background: rgba(25, 135, 84, 0.18);

    color: #20c997;
}

.status-inactive {

    background: rgba(220, 53, 69, 0.18);

    color: #ff6b7a;
}


/* =========================================================
   ACTION
========================================================= */

.action-column {

    white-space: nowrap !important;
}

.action-btn {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    margin-right: 3px;

    margin-bottom: 3px;

    white-space: nowrap;
}

.edit-btn,
.delete-btn {

    padding: 5px 9px !important;

    min-width: 68px;

    font-size: 12px !important;
}


/* =========================================================
   EDIT OVERLAY
========================================================= */

.edit-overlay {

    position: fixed !important;

    inset: 0 !important;

    width: 100vw !important;

    height: 100vh !important;

    height: 100dvh !important;

    background: rgba(0, 0, 0, 0.78) !important;

    display: flex !important;

    align-items: center !important;

    justify-content: center !important;

    padding: 20px !important;

    z-index: 999999 !important;

    overflow-y: auto !important;

    -webkit-overflow-scrolling: touch;
}


/* =========================================================
   EDIT BOX
========================================================= */

.edit-box {

    width: 100% !important;

    max-width: 850px !important;

    max-height: calc(100dvh - 40px);

    background: #191c24 !important;

    border-radius: 10px !important;

    padding: 25px !important;

    box-shadow:
        0 10px 40px rgba(0, 0, 0, 0.60);

    overflow-y: auto !important;

    overflow-x: hidden !important;
}


/* =========================================================
   EDIT TITLE
========================================================= */

.edit-title {

    width: 100%;

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 20px;
}

.edit-title h5 {

    margin: 0;

    color: #fff;

    font-size: 18px;

    font-weight: 600;
}


/* =========================================================
   CLOSE
========================================================= */

.close-edit {

    width: 35px;

    height: 35px;

    min-width: 35px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #dc3545;

    color: #fff;

    text-decoration: none !important;
}

.close-edit:hover {

    color: #fff;

    text-decoration: none !important;

    background: #bb2d3b;
}


/* =========================================================
   LABEL
========================================================= */

.edit-box label {

    display: block;

    color: #fff;

    font-weight: 600;

    margin-bottom: 7px;
}


/* =========================================================
   INPUT
========================================================= */

.edit-box input,
.edit-box select {

    width: 100% !important;

    height: 43px !important;

    background: #fff !important;

    color: #000 !important;

    border: none !important;

    border-radius: 5px !important;

    padding: 8px 10px !important;
}

.edit-box input:focus,
.edit-box select:focus {

    outline: none !important;

    box-shadow:
        0 0 0 2px rgba(13, 110, 253, 0.25) !important;
}

.edit-box input[readonly] {

    background: #d6d6d6 !important;

    color: #333 !important;

    cursor: not-allowed;
}


/* =========================================================
   PASSWORD NOTE
========================================================= */

.password-note {

    display: block;

    color: #8f9bc0;

    font-size: 11px;

    margin-top: 5px;
}


/* =========================================================
   EDIT BUTTONS
========================================================= */

.edit-buttons {

    display: flex;

    align-items: center;

    gap: 8px;

    margin-top: 8px;
}

.update-btn,
.cancel-btn {

    min-width: 110px;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 991px) {

    .main-container .sidebar {

        width: 250px !important;

        min-width: 250px !important;

        height: 100vh !important;

        position: fixed !important;

        top: 0 !important;

        left: -250px !important;

        bottom: 0 !important;

        z-index: 99999 !important;

        transition: left 0.3s ease !important;
    }

    body.sidebar-open .main-container .sidebar {

        left: 0 !important;
    }

    .content {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        margin-left: 0 !important;

        padding: 0 !important;
    }

    .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        padding-left: 15px !important;

        padding-right: 15px !important;
    }

    .user-container {

        width: 100% !important;

        padding: 15px !important;
    }

    .user-table {

        min-width: 800px !important;

        table-layout: fixed !important;
    }

    .user-table th,
    .user-table td {

        font-size: 12px !important;

        padding: 9px 6px !important;
    }

    .edit-box {

        max-width: 750px !important;

        padding: 20px !important;
    }
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767px) {

    .main-container .sidebar {

        width: 250px !important;

        min-width: 250px !important;

        height: 100vh !important;

        position: fixed !important;

        top: 0 !important;

        left: -250px !important;

        bottom: 0 !important;

        z-index: 99999 !important;
    }

    body.sidebar-open .main-container .sidebar {

        left: 0 !important;
    }

    .content {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        margin-left: 0 !important;

        padding: 0 !important;
    }

    .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        margin: 0 !important;

        padding-left: 10px !important;

        padding-right: 10px !important;
    }

    .user-container {

        width: 100% !important;

        max-width: 100% !important;

        padding: 10px 7px !important;
    }

    .table-box {

        width: 100% !important;

        max-width: 100% !important;

        padding: 12px !important;

        border-radius: 7px !important;
    }

    .table-title {

        font-size: 16px !important;

        margin-bottom: 12px !important;
    }

    .user-table-wrapper {

        overflow: visible !important;
    }

    .user-table {

        width: 100% !important;

        min-width: 0 !important;

        max-width: 100% !important;

        display: block !important;

        table-layout: auto !important;
    }

    .user-table thead {

        display: none !important;
    }

    .user-table tbody {

        display: block !important;

        width: 100% !important;
    }

    .user-table tr {

        display: block !important;

        width: 100% !important;

        background: #1f222b !important;

        border-radius: 7px !important;

        margin-bottom: 10px !important;

        padding: 10px !important;
    }

    .user-table td {

        display: flex !important;

        align-items: center !important;

        justify-content: space-between !important;

        width: 100% !important;

        padding: 7px 4px !important;

        border: none !important;

        font-size: 13px !important;

        text-align: right !important;

        word-break: break-word !important;
    }

    .user-table td::before {

        content: attr(data-label);

        color: #8f9bc0 !important;

        font-weight: 600 !important;

        text-align: left !important;

        margin-right: 12px !important;

        flex-shrink: 0 !important;
    }

    .user-table td.action-column {

        justify-content: flex-start !important;

        flex-wrap: wrap !important;

        gap: 5px !important;

        padding-top: 10px !important;
    }

    .user-table td.action-column::before {

        width: 100% !important;

        margin-bottom: 5px !important;
    }

    .edit-btn,
    .delete-btn {

        font-size: 12px !important;

        padding: 6px 10px !important;

        min-width: 70px !important;
    }

    .edit-overlay {

        padding: 8px !important;

        align-items: center !important;

        justify-content: center !important;
    }

    .edit-box {

        width: 100% !important;

        max-width: 100% !important;

        max-height: calc(100dvh - 16px) !important;

        padding: 15px !important;

        border-radius: 8px !important;

        overflow-y: auto !important;

        overflow-x: hidden !important;
    }

    .edit-title {

        margin-bottom: 18px !important;
    }

    .edit-title h5 {

        font-size: 16px !important;
    }

    .close-edit {

        width: 32px !important;

        height: 32px !important;

        min-width: 32px !important;
    }

    .edit-box .row {

        margin-left: 0 !important;

        margin-right: 0 !important;
    }

    .edit-box .col-md-6 {

        width: 100% !important;

        padding-left: 0 !important;

        padding-right: 0 !important;

        margin-bottom: 12px !important;
    }

    .edit-box input,
    .edit-box select {

        width: 100% !important;

        height: 42px !important;

        font-size: 14px !important;
    }

    .edit-buttons {

        width: 100% !important;

        flex-direction: column !important;

        gap: 7px !important;
    }

    .edit-buttons .btn {

        width: 100% !important;

        min-height: 42px !important;
    }
}


/* =========================================================
   VERY SMALL MOBILE
========================================================= */

@media (max-width: 400px) {

    .user-container {

        padding: 8px 5px !important;
    }

    .table-box {

        padding: 10px !important;
    }

    .user-table td {

        font-size: 12px !important;

        padding: 6px 3px !important;
    }

    .edit-box {

        padding: 13px !important;
    }
}


/* =========================================================
   MOBILE SIDEBAR OVERLAY
========================================================= */

.sidebar-mobile-overlay {

    display: none;

    position: fixed;

    inset: 0;

    background: rgba(0, 0, 0, 0.55);

    z-index: 99998;
}

@media (max-width: 991px) {

    body.sidebar-open .sidebar-mobile-overlay {

        display: block !important;
    }
}

</style>

</head>


<body>


<!-- =====================================================
     MAIN WRAPPER
====================================================== -->

<div class="main-container">


    <!-- =================================================
         SIDEBAR
    ================================================== -->

    <?php include("sidebar.php"); ?>


    <!-- =================================================
         MOBILE OVERLAY
    ================================================== -->

    <div
        class="sidebar-mobile-overlay"
        id="sidebarOverlay"
    ></div>


    <!-- =================================================
         CONTENT
    ================================================== -->

    <div class="content">


        <!-- =================================================
             NAVBAR
        ================================================== -->

        <?php include("navbar.php"); ?>


        <!-- =================================================
             USER PAGE
        ================================================== -->

        <div class="user-container">


            <!-- =================================================
                 MESSAGE
            ================================================== -->

            <?php if ($message !== "") { ?>

                <div
                    class="alert alert-<?php
                        echo htmlspecialchars(
                            $message_type,
                            ENT_QUOTES,
                            "UTF-8"
                        );
                    ?>"
                >

                    <?php
                    echo htmlspecialchars(
                        $message,
                        ENT_QUOTES,
                        "UTF-8"
                    );
                    ?>

                </div>

            <?php } ?>


            <!-- =================================================
                 TABLE BOX
            ================================================== -->

            <div class="table-box">


                <!-- TITLE -->

                <div class="table-title">

                    <i class="fa fa-users me-2"></i>

                    Users

                </div>


                <!-- TABLE -->

                <div class="user-table-wrapper">


                    <table
                        class="table table-dark table-hover user-table"
                    >

                        <thead>

                            <tr>

                                <th>User ID</th>

                                <th>Name</th>

                                <th>Email</th>

                                <th>Password</th>

                                <th>Status</th>

                                <th>Created At</th>

                                <th>Action</th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php

                        if (mysqli_num_rows($result) > 0) {

                            while ($row = mysqli_fetch_assoc($result)) {

                        ?>

                            <tr>


                                <!-- USER ID -->

                                <td data-label="User ID">

                                    <?php
                                    echo htmlspecialchars(
                                        $row["users_id"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>

                                </td>


                                <!-- NAME -->

                                <td data-label="Name">

                                    <?php
                                    echo htmlspecialchars(
                                        $row["name"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>

                                </td>


                                <!-- EMAIL -->

                                <td data-label="Email">

                                    <?php
                                    echo htmlspecialchars(
                                        $row["email"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>

                                </td>


                                <!-- PASSWORD -->

                                <td data-label="Password">

                                    <span class="password-text">

                                        <?php
                                        echo htmlspecialchars(
                                            $row["password"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>

                                    </span>

                                </td>


                                <!-- STATUS -->

                                <td data-label="Status">

                                    <?php

                                    if (
                                        strtolower(
                                            trim($row["status"])
                                        ) === "active"
                                    ) {

                                    ?>

                                        <span
                                            class="status-badge status-active"
                                        >
                                            Active
                                        </span>

                                    <?php

                                    } else {

                                    ?>

                                        <span
                                            class="status-badge status-inactive"
                                        >

                                            <?php
                                            echo htmlspecialchars(
                                                $row["status"],
                                                ENT_QUOTES,
                                                "UTF-8"
                                            );
                                            ?>

                                        </span>

                                    <?php

                                    }

                                    ?>

                                </td>


                                <!-- CREATED AT -->

                                <td data-label="Created At">

                                    <?php
                                    echo htmlspecialchars(
                                        $row["created_at"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>

                                </td>


                                <!-- ACTION -->

                                <td
                                    data-label="Action"
                                    class="action-column"
                                >

                                    <!-- EDIT -->

                                    <a
                                        href="<?php
                                            echo htmlspecialchars(
                                                $current_page,
                                                ENT_QUOTES,
                                                "UTF-8"
                                            );
                                        ?>?edit_id=<?php
                                            echo (int) $row["users_id"];
                                        ?>"
                                        class="btn btn-primary btn-sm edit-btn action-btn"
                                    >

                                        <i class="fa fa-edit me-1"></i>

                                        Edit

                                    </a>


                                    <!-- DELETE -->

                                    <a
                                        href="<?php
                                            echo htmlspecialchars(
                                                $current_page,
                                                ENT_QUOTES,
                                                "UTF-8"
                                            );
                                        ?>?delete_id=<?php
                                            echo (int) $row["users_id"];
                                        ?>"
                                        class="btn btn-danger btn-sm delete-btn action-btn"
                                        onclick="return confirm('Are you sure you want to delete this User?');"
                                    >

                                        <i class="fa fa-trash me-1"></i>

                                        Delete

                                    </a>

                                </td>

                            </tr>

                        <?php

                            }

                        } else {

                        ?>

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center"
                                >

                                    No User Record Found

                                </td>

                            </tr>

                        <?php

                        }

                        ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         EDIT USER POPUP
    ====================================================== -->

    <?php if ($edit_record !== null) { ?>

        <div class="edit-overlay">

            <div class="edit-box">


                <!-- EDIT TITLE -->

                <div class="edit-title">

                    <h5>

                        <i class="fa fa-edit me-2"></i>

                        Edit User

                    </h5>


                    <!-- CLOSE -->

                    <a
                        href="<?php
                            echo htmlspecialchars(
                                $current_page,
                                ENT_QUOTES,
                                "UTF-8"
                            );
                        ?>"
                        class="close-edit"
                        title="Close"
                        aria-label="Close"
                    >

                        <i class="fa fa-times"></i>

                    </a>

                </div>


                <!-- EDIT FORM -->

                <form
                    method="POST"
                    action="<?php
                        echo htmlspecialchars(
                            $current_page,
                            ENT_QUOTES,
                            "UTF-8"
                        );
                    ?>"
                    autocomplete="off"
                >


                    <!-- HIDDEN USER ID -->

                    <input
                        type="hidden"
                        name="users_id"
                        value="<?php
                            echo (int) $edit_record["users_id"];
                        ?>"
                    >


                    <!-- USER ID + NAME -->

                    <div class="row">


                        <!-- USER ID -->

                        <div class="col-md-6 mb-3">

                            <label>
                                User ID
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                value="<?php
                                    echo (int)
                                        $edit_record["users_id"];
                                ?>"
                                readonly
                            >

                        </div>


                        <!-- NAME -->

                        <div class="col-md-6 mb-3">

                            <label>
                                Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars(
                                        $edit_record["name"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                ?>"
                                required
                            >

                        </div>

                    </div>


                    <!-- EMAIL + STATUS -->

                    <div class="row">


                        <!-- EMAIL -->

                        <div class="col-md-6 mb-3">

                            <label>
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars(
                                        $edit_record["email"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                ?>"
                                required
                            >

                        </div>


                        <!-- STATUS -->

                        <div class="col-md-6 mb-3">

                            <label>
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-control"
                                required
                            >

                                <option
                                    value="active"
                                    <?php

                                    if (
                                        strtolower(
                                            trim(
                                                $edit_record["status"]
                                            )
                                        ) === "active"
                                    ) {

                                        echo "selected";
                                    }

                                    ?>
                                >
                                    Active
                                </option>


                                <option
                                    value="inactive"
                                    <?php

                                    if (
                                        strtolower(
                                            trim(
                                                $edit_record["status"]
                                            )
                                        ) === "inactive"
                                    ) {

                                        echo "selected";
                                    }

                                    ?>
                                >
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>


                    <!-- PASSWORD + CREATED AT -->

                    <div class="row">


                        <!-- PASSWORD -->

                        <div class="col-md-6 mb-3">

                            <label>
                                New Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Enter new password"
                                autocomplete="new-password"
                            >

                            <small class="password-note">

                                New password dena ho to enter karein.
                                Blank chhorne par old password same rahega.

                            </small>

                        </div>


                        <!-- CREATED AT -->

                        <div class="col-md-6 mb-3">

                            <label>
                                Created At
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars(
                                        $edit_record["created_at"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                ?>"
                                readonly
                            >

                        </div>

                    </div>


                    <!-- BUTTONS -->

                    <div class="edit-buttons">


                        <!-- UPDATE -->

                        <button
                            type="submit"
                            name="update_user"
                            value="1"
                            class="btn btn-success update-btn"
                        >

                            <i class="fa fa-save me-1"></i>

                            Update User

                        </button>


                        <!-- CANCEL -->

                        <a
                            href="<?php
                                echo htmlspecialchars(
                                    $current_page,
                                    ENT_QUOTES,
                                    "UTF-8"
                                );
                            ?>"
                            class="btn btn-danger cancel-btn"
                        >

                            <i class="fa fa-times me-1"></i>

                            Cancel

                        </a>

                    </div>

                </form>

            </div>

        </div>

    <?php } ?>

</div>


<!-- =====================================================
     JQUERY
====================================================== -->

<script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
></script>


<!-- =====================================================
     BOOTSTRAP
====================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
></script>


<!-- =====================================================
     DARKPAN MAIN JS
====================================================== -->

<script src="js/main.js"></script>


<!-- =====================================================
     SIDEBAR FIX
====================================================== -->

<script>

document.addEventListener("DOMContentLoaded", function () {

    /*
    =====================================================
    FIND ALL SIDEBAR TOGGLERS
    =====================================================
    */

    const togglers = document.querySelectorAll(
        ".sidebar-toggler, .navbar-toggler"
    );


    /*
    =====================================================
    FIND SIDEBAR
    =====================================================
    */

    const sidebar = document.querySelector(
        ".main-container .sidebar"
    );


    /*
    =====================================================
    FIND OVERLAY
    =====================================================
    */

    const overlay = document.getElementById(
        "sidebarOverlay"
    );


    /*
    =====================================================
    SAFETY CHECK
    =====================================================
    */

    if (!sidebar) {

        console.log("Sidebar not found.");

        return;
    }


    /*
    =====================================================
    SIDEBAR TOGGLE FUNCTION
    =====================================================
    */

    function toggleSidebar(event) {

        if (event) {

            event.preventDefault();

            event.stopPropagation();

        }


        /*
        MOBILE / TABLET
        */

        if (window.innerWidth <= 991) {

            document.body.classList.toggle(
                "sidebar-open"
            );

            document.body.classList.remove(
                "sidebar-collapsed"
            );

        }


        /*
        DESKTOP
        */

        else {

            document.body.classList.toggle(
                "sidebar-collapsed"
            );

            document.body.classList.remove(
                "sidebar-open"
            );

        }

    }


    /*
    =====================================================
    ADD TOGGLE TO EVERY BUTTON
    =====================================================
    */

    togglers.forEach(function (toggler) {

        toggler.addEventListener(
            "click",
            toggleSidebar,
            true
        );

    });


    /*
    =====================================================
    SIDEBAR LINKS
    =====================================================
    */

    const sidebarLinks =
        sidebar.querySelectorAll("a");


    sidebarLinks.forEach(function (link) {

        link.addEventListener(
            "click",
            function (event) {

                const href =
                    link.getAttribute("href");


                /*
                IGNORE EMPTY LINKS
                */

                if (
                    !href ||
                    href === "#" ||
                    href === "javascript:void(0)" ||
                    href === "javascript:void(0);"
                ) {

                    return;
                }


                /*
                IGNORE DROPDOWN BUTTONS
                */

                if (
                    link.hasAttribute("data-bs-toggle") ||
                    link.hasAttribute("data-toggle")
                ) {

                    return;
                }


                /*
                CLOSE SIDEBAR
                */

                document.body.classList.remove(
                    "sidebar-open"
                );


                /*
                MOBILE / TABLET
                */

                if (window.innerWidth <= 991) {

                    event.preventDefault();

                    event.stopPropagation();

                    /*
                    DIRECT OPEN
                    */

                    window.location.assign(
                        link.href
                    );

                }

            },
            true
        );

    });


    /*
    =====================================================
    OVERLAY CLICK
    =====================================================
    */

    if (overlay) {

        overlay.addEventListener(
            "click",
            function () {

                document.body.classList.remove(
                    "sidebar-open"
                );

            }
        );

    }


    /*
    =====================================================
    OUTSIDE CLICK
    =====================================================
    */

    document.addEventListener(
        "click",
        function (event) {

            if (window.innerWidth > 991) {

                return;
            }


            if (
                !document.body.classList.contains(
                    "sidebar-open"
                )
            ) {

                return;
            }


            const clickedInsideSidebar =
                sidebar.contains(event.target);


            let clickedToggler = false;


            togglers.forEach(function (toggler) {

                if (
                    toggler.contains(event.target)
                ) {

                    clickedToggler = true;
                }

            });


            if (
                !clickedInsideSidebar &&
                !clickedToggler
            ) {

                document.body.classList.remove(
                    "sidebar-open"
                );

            }

        },
        true
    );


    /*
    =====================================================
    RESIZE FIX
    =====================================================
    */

    window.addEventListener(
        "resize",
        function () {

            if (window.innerWidth > 991) {

                document.body.classList.remove(
                    "sidebar-open"
                );

            } else {

                document.body.classList.remove(
                    "sidebar-collapsed"
                );

            }

        }
    );

});

</script>


</body>

</html>