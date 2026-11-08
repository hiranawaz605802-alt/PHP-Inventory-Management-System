<?php

include_once("Database.php");

$db = new Database();
$conn = $db->connection();

if (!$conn) {
    die("Database connection failed.");
}


// =====================================================
// CURRENT PAGE
// =====================================================

$current_page = basename($_SERVER["PHP_SELF"]);


// =====================================================
// HELPER FUNCTION
// =====================================================

function e($value)
{
    return htmlspecialchars(
        (string)($value ?? ""),
        ENT_QUOTES,
        "UTF-8"
    );
}


// =====================================================
// DELETE SHOPPING CART RECORD
// =====================================================

if (isset($_GET["delete_id"])) {

    $delete_id = (int) $_GET["delete_id"];

    if ($delete_id > 0) {

        $delete_sql = "
            DELETE FROM entity_asad_shooping_cart
            WHERE cart_id = ?
        ";

        $delete_stmt = mysqli_prepare(
            $conn,
            $delete_sql
        );

        if (!$delete_stmt) {

            die(
                "Delete Prepare Error: " .
                mysqli_error($conn)
            );
        }


        mysqli_stmt_bind_param(
            $delete_stmt,
            "i",
            $delete_id
        );


        if (mysqli_stmt_execute($delete_stmt)) {

            mysqli_stmt_close($delete_stmt);

            echo "<script>
                    alert('Shopping Cart Deleted Successfully');
                    window.location.href='" .
                    e($current_page) .
                    "';
                  </script>";

            exit();

        } else {

            $error =
                mysqli_stmt_error(
                    $delete_stmt
                );

            mysqli_stmt_close(
                $delete_stmt
            );

            die(
                "Delete Error: " .
                $error
            );
        }
    }
}


// =====================================================
// UPDATE SHOPPING CART RECORD
// =====================================================

if (isset($_POST["update_cart"])) {

    $cart_id =
        (int)($_POST["cart_id"] ?? 0);

    $customer_id =
        (int)($_POST["customer_id"] ?? 0);


    if ($cart_id <= 0) {

        die("Invalid Cart ID.");
    }


    if ($customer_id <= 0) {

        die("Invalid Customer ID.");
    }


    $update_sql = "
        UPDATE entity_asad_shooping_cart
        SET customer_id = ?
        WHERE cart_id = ?
    ";


    $update_stmt = mysqli_prepare(
        $conn,
        $update_sql
    );


    if (!$update_stmt) {

        die(
            "Update Prepare Error: " .
            mysqli_error($conn)
        );
    }


    mysqli_stmt_bind_param(
        $update_stmt,
        "ii",
        $customer_id,
        $cart_id
    );


    if (mysqli_stmt_execute($update_stmt)) {

        mysqli_stmt_close(
            $update_stmt
        );

        echo "<script>
                alert('Shopping Cart Updated Successfully');
                window.location.href='" .
                e($current_page) .
                "';
              </script>";

        exit();

    } else {

        $error =
            mysqli_stmt_error(
                $update_stmt
            );

        mysqli_stmt_close(
            $update_stmt
        );

        die(
            "Update Error: " .
            $error
        );
    }
}


// =====================================================
// GET EDIT RECORD
// =====================================================

$edit_record = null;


if (isset($_GET["edit_id"])) {

    $edit_id =
        (int)$_GET["edit_id"];


    if ($edit_id > 0) {

        $edit_sql = "
            SELECT
                cart_id,
                customer_id
            FROM entity_asad_shooping_cart
            WHERE cart_id = ?
            LIMIT 1
        ";


        $edit_stmt = mysqli_prepare(
            $conn,
            $edit_sql
        );


        if (!$edit_stmt) {

            die(
                "Edit Prepare Error: " .
                mysqli_error($conn)
            );
        }


        mysqli_stmt_bind_param(
            $edit_stmt,
            "i",
            $edit_id
        );


        if (!mysqli_stmt_execute($edit_stmt)) {

            die(
                "Edit Execute Error: " .
                mysqli_stmt_error($edit_stmt)
            );
        }


        $edit_result =
            mysqli_stmt_get_result(
                $edit_stmt
            );


        if (
            $edit_result &&
            mysqli_num_rows($edit_result) > 0
        ) {

            $edit_record =
                mysqli_fetch_assoc(
                    $edit_result
                );
        }


        mysqli_stmt_close(
            $edit_stmt
        );
    }
}


// =====================================================
// GET SHOPPING CART RECORDS
// =====================================================

$sql = "
    SELECT
        cart_id,
        customer_id
    FROM entity_asad_shooping_cart
    ORDER BY cart_id DESC
";


$result = mysqli_query(
    $conn,
    $sql
);


if (!$result) {

    die(
        "Cart View Error: " .
        mysqli_error($conn)
    );
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="utf-8">

<title>DarkPan - Shopping Cart View</title>

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>


<!-- =====================================================
     GOOGLE FONT
===================================================== -->

<link
    rel="preconnect"
    href="https://fonts.googleapis.com"
>

<link
    rel="preconnect"
    href="https://fonts.googleapis.com"
    crossorigin
>

<link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap"
    rel="stylesheet"
>


<!-- =====================================================
     FONT AWESOME
===================================================== -->

<link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
    rel="stylesheet"
>


<!-- =====================================================
     BOOTSTRAP ICONS
===================================================== -->

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
    rel="stylesheet"
>


<!-- =====================================================
     BOOTSTRAP
===================================================== -->

<link
    href="css/bootstrap.min.css"
    rel="stylesheet"
>


<!-- =====================================================
     DARKPAN CSS
===================================================== -->

<link
    href="css/style.css"
    rel="stylesheet"
>


<style>

/* =========================================================
   GLOBAL RESET
========================================================= */

html,
body {

    width: 100% !important;
    max-width: 100% !important;
    min-height: 100% !important;

    margin: 0 !important;
    padding: 0 !important;

    overflow-x: hidden !important;

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

    transition:
        left 0.3s ease !important;
}


/* =========================================================
   CONTENT
========================================================= */

.main-container > .content {

    width: calc(100% - 250px) !important;
    max-width: calc(100% - 250px) !important;

    min-width: 0 !important;

    min-height: 100vh !important;

    margin-left: 250px !important;

    padding: 0 !important;

    position: relative !important;

    overflow-x: hidden !important;

    background: #000 !important;

    transition:
        width 0.3s ease,
        max-width 0.3s ease,
        margin-left 0.3s ease !important;
}


/* =========================================================
   DESKTOP SIDEBAR COLLAPSED
========================================================= */

@media (min-width: 992px) {

    body.sidebar-collapsed
    .main-container
    .sidebar {

        left: -250px !important;
    }


    body.sidebar-collapsed
    .main-container > .content {

        width: 100% !important;
        max-width: 100% !important;

        margin-left: 0 !important;
    }
}


/* =========================================================
   NAVBAR
========================================================= */

.main-container > .content > .navbar {

    width: 100% !important;
    max-width: 100% !important;

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
   SIDEBAR TOGGLER
========================================================= */

.sidebar-toggler,
.navbar-toggler,
.sidebar-toggle,
[data-sidebar-toggle] {

    position: relative !important;

    z-index: 100001 !important;

    cursor: pointer !important;

    pointer-events: auto !important;

    border: 0 !important;

    outline: none !important;
}


/* =========================================================
   MOBILE SIDEBAR OVERLAY
========================================================= */

.sidebar-overlay {

    display: none;

    position: fixed !important;

    inset: 0 !important;

    width: 100vw !important;
    height: 100vh !important;

    background:
        rgba(0, 0, 0, 0.55) !important;

    z-index: 99998 !important;

    cursor: pointer !important;
}


body.sidebar-open
.sidebar-overlay {

    display: block !important;
}


/* =========================================================
   MOBILE / TABLET SIDEBAR
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

        display: block !important;

        visibility: visible !important;
        opacity: 1 !important;

        overflow-y: auto !important;
        overflow-x: hidden !important;

        transition:
            left 0.3s ease !important;
    }


    body.sidebar-open
    .main-container
    .sidebar {

        left: 0 !important;
    }


    .main-container > .content {

        width: 100% !important;
        max-width: 100% !important;

        min-width: 0 !important;

        margin-left: 0 !important;

        padding: 0 !important;
    }


    body.sidebar-collapsed
    .main-container > .content {

        width: 100% !important;
        max-width: 100% !important;

        margin-left: 0 !important;
    }


    body.sidebar-open {

        overflow: hidden !important;
    }


    .main-container > .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        padding-left: 15px !important;
        padding-right: 15px !important;
    }
}


/* =========================================================
   CART PAGE
========================================================= */

.cart-page {

    width: 100% !important;
    max-width: 100% !important;

    min-width: 0 !important;

    margin: 0 !important;

    padding: 24px !important;

    overflow: hidden !important;
}


/* =========================================================
   CART BOX
========================================================= */

.cart-box {

    width: 100% !important;
    max-width: 1200px !important;

    margin: 0 auto !important;

    padding: 20px !important;

    background: #191c24 !important;

    border-radius: 8px !important;

    overflow: hidden !important;
}


/* =========================================================
   CART TITLE
========================================================= */

.cart-box h6 {

    color: #fff !important;

    font-size: 18px !important;

    font-weight: 600 !important;

    margin: 0 0 20px 0 !important;
}


/* =========================================================
   TABLE WRAPPER
========================================================= */

.cart-table-wrapper {

    width: 100% !important;
    max-width: 100% !important;

    min-width: 0 !important;

    overflow-x: auto !important;
    overflow-y: hidden !important;

    -webkit-overflow-scrolling: touch;

    border-radius: 6px !important;
}


/* =========================================================
   TABLE
========================================================= */

.cart-table {

    width: 100% !important;

    min-width: 560px !important;

    margin: 0 !important;

    border-collapse: collapse !important;
}


/* =========================================================
   TABLE HEADER
========================================================= */

.cart-table th {

    white-space: nowrap !important;

    color: #8f9bc0 !important;

    font-size: 13px !important;

    font-weight: 600 !important;

    padding: 13px 10px !important;

    vertical-align: middle !important;
}


/* =========================================================
   TABLE BODY
========================================================= */

.cart-table td {

    vertical-align: middle !important;

    padding: 13px 10px !important;

    white-space: nowrap !important;

    color: #8f9bc0 !important;

    font-size: 13px !important;
}


/* =========================================================
   BUTTONS
========================================================= */

.edit-btn,
.delete-btn {

    white-space: nowrap !important;

    min-width: 75px !important;
}


/* =========================================================
   EDIT OVERLAY
========================================================= */

.edit-overlay {

    position: fixed !important;

    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;

    width: 100vw !important;
    height: 100vh !important;

    background:
        rgba(0, 0, 0, 0.78) !important;

    display: flex !important;

    align-items: center !important;
    justify-content: center !important;

    padding: 20px !important;

    z-index: 200000 !important;

    overflow-y: auto !important;
}


/* =========================================================
   EDIT BOX
========================================================= */

.edit-box {

    width: 100% !important;

    max-width: 700px !important;

    background: #191c24 !important;

    border-radius: 10px !important;

    padding: 25px !important;

    box-shadow:
        0 10px 40px
        rgba(0, 0, 0, 0.6);

    overflow: hidden !important;
}


/* =========================================================
   EDIT TITLE
========================================================= */

.edit-title {

    width: 100% !important;

    display: flex !important;

    justify-content: space-between !important;

    align-items: center !important;

    gap: 15px !important;

    margin-bottom: 25px !important;
}


.edit-title h5 {

    margin: 0 !important;
    padding: 0 !important;

    color: #fff !important;

    font-size: 18px !important;

    font-weight: 600 !important;
}


/* =========================================================
   CLOSE EDIT
========================================================= */

.close-edit {

    width: 36px !important;
    height: 36px !important;

    min-width: 36px !important;

    display: flex !important;

    align-items: center !important;
    justify-content: center !important;

    border-radius: 50% !important;

    background: #dc3545 !important;

    color: #fff !important;

    text-decoration: none !important;

    transition: 0.2s !important;
}


.close-edit:hover {

    background: #bb2d3b !important;

    color: #fff !important;
}


/* =========================================================
   EDIT LABEL
========================================================= */

.edit-box label {

    display: block !important;

    color: #fff !important;

    font-weight: 600 !important;

    margin-bottom: 8px !important;
}


/* =========================================================
   EDIT INPUT
========================================================= */

.edit-box .form-control {

    width: 100% !important;

    height: 45px !important;

    background: #fff !important;

    color: #000 !important;

    border: none !important;

    border-radius: 5px !important;

    box-sizing: border-box !important;
}


.edit-box .form-control:focus {

    background: #fff !important;

    color: #000 !important;

    border: none !important;

    outline: none !important;

    box-shadow:
        0 0 0 2px
        rgba(13, 110, 253, 0.35) !important;
}


/* =========================================================
   EDIT BUTTONS
========================================================= */

.edit-buttons {

    display: flex !important;

    align-items: center !important;

    gap: 10px !important;

    margin-top: 10px !important;
}


/* =========================================================
   BACK TO TOP HIDE
========================================================= */

.back-to-top {

    display: none !important;

    visibility: hidden !important;

    opacity: 0 !important;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767px) {

    .main-container > .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        padding-left: 10px !important;
        padding-right: 10px !important;
    }


    .cart-page {

        width: 100% !important;
        max-width: 100% !important;

        padding: 12px 8px !important;
    }


    .cart-box {

        width: 100% !important;

        padding: 14px !important;

        border-radius: 7px !important;
    }


    .cart-box h6 {

        font-size: 16px !important;

        margin-bottom: 15px !important;
    }


    .cart-table-wrapper {

        width: 100% !important;
        max-width: 100% !important;

        overflow-x: auto !important;
    }


    .cart-table {

        min-width: 560px !important;
    }


    .cart-table th,
    .cart-table td {

        padding: 10px 8px !important;

        font-size: 13px !important;
    }


    /* =================================================
       EDIT OVERLAY MOBILE
    ================================================= */

    .edit-overlay {

        padding: 10px !important;

        align-items: center !important;
        justify-content: center !important;
    }


    .edit-box {

        width: 100% !important;

        max-width: 100% !important;

        padding: 16px !important;

        border-radius: 8px !important;
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


    /* =================================================
       EDIT FORM MOBILE
    ================================================= */

    .edit-box .row {

        margin-left: 0 !important;
        margin-right: 0 !important;
    }


    .edit-box .col-md-6 {

        width: 100% !important;

        padding-left: 0 !important;
        padding-right: 0 !important;

        margin-bottom: 15px !important;
    }


    /* =================================================
       EDIT BUTTONS MOBILE
    ================================================= */

    .edit-buttons {

        width: 100% !important;

        display: flex !important;

        flex-direction: column !important;

        gap: 8px !important;
    }


    .edit-buttons .btn {

        width: 100% !important;

        min-height: 43px !important;

        margin: 0 !important;
    }
}


/* =========================================================
   VERY SMALL MOBILE
========================================================= */

@media (max-width: 400px) {

    .cart-page {

        padding: 10px 5px !important;
    }


    .cart-box {

        padding: 12px !important;
    }


    .cart-table {

        min-width: 540px !important;
    }


    .cart-table th,
    .cart-table td {

        padding: 9px 7px !important;

        font-size: 12px !important;
    }


    .edit-overlay {

        padding: 7px !important;
    }


    .edit-box {

        padding: 14px !important;
    }
}

</style>

</head>


<body>


<!-- =====================================================
     MAIN CONTAINER
====================================================== -->

<div class="main-container">


    <!-- =================================================
         SIDEBAR
    ================================================= -->

    <?php include("sidebar.php"); ?>


    <!-- =================================================
         MOBILE SIDEBAR OVERLAY
    ================================================= -->

    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
    ></div>


    <!-- =================================================
         CONTENT
    ================================================= -->

    <div class="content">


        <!-- =================================================
             NAVBAR
        ================================================= -->

        <?php include("navbar.php"); ?>


        <!-- =================================================
             CART PAGE
        ================================================= -->

        <div class="cart-page">


            <div class="cart-box">


                <!-- =================================================
                     TITLE
                ================================================= -->

                <h6>

                    <i class="fa fa-shopping-cart me-2"></i>

                    Shopping Cart

                </h6>


                <!-- =================================================
                     TABLE
                ================================================= -->

                <div class="cart-table-wrapper">


                    <table
                        class="table table-dark table-hover cart-table"
                    >


                        <!-- =============================================
                             HEADER
                        ============================================= -->

                        <thead>

                            <tr>

                                <th>
                                    Cart ID
                                </th>

                                <th>
                                    Customer ID
                                </th>

                                <th>
                                    Edit
                                </th>

                                <th>
                                    Delete
                                </th>

                            </tr>

                        </thead>


                        <!-- =============================================
                             BODY
                        ============================================= -->

                        <tbody>


                        <?php

                        if (
                            mysqli_num_rows($result) > 0
                        ) {

                            while (
                                $row =
                                mysqli_fetch_assoc($result)
                            ) {

                        ?>

                            <tr>


                                <!-- CART ID -->

                                <td>

                                    <?php

                                    echo e(
                                        $row["cart_id"]
                                    );

                                    ?>

                                </td>


                                <!-- CUSTOMER ID -->

                                <td>

                                    <?php

                                    echo e(
                                        $row["customer_id"]
                                    );

                                    ?>

                                </td>


                                <!-- EDIT -->

                                <td>

                                    <a
                                        href="<?php
                                            echo e(
                                                $current_page
                                            );
                                        ?>?edit_id=<?php
                                            echo (int)
                                                $row["cart_id"];
                                        ?>"
                                        class="btn btn-primary btn-sm edit-btn"
                                    >

                                        <i class="fa fa-edit"></i>

                                        Edit

                                    </a>

                                </td>


                                <!-- DELETE -->

                                <td>

                                    <a
                                        href="<?php
                                            echo e(
                                                $current_page
                                            );
                                        ?>?delete_id=<?php
                                            echo (int)
                                                $row["cart_id"];
                                        ?>"
                                        class="btn btn-danger btn-sm delete-btn"
                                        onclick="return confirm('Are you sure you want to delete this shopping cart record?');"
                                    >

                                        <i class="fa fa-trash"></i>

                                        Delete

                                    </a>

                                </td>


                            </tr>

                        <?php

                            }

                        }
                        else {

                        ?>

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center"
                                >

                                    No Shopping Cart Record Found

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


    <!-- =================================================
         EDIT FORM
    ================================================= -->

    <?php if ($edit_record !== null) { ?>


        <div class="edit-overlay">


            <div class="edit-box">


                <!-- =============================================
                     EDIT HEADER
                ============================================= -->

                <div class="edit-title">


                    <h5>

                        <i class="fa fa-edit me-2"></i>

                        Edit Shopping Cart

                    </h5>


                    <a
                        href="<?php
                            echo e($current_page);
                        ?>"
                        class="close-edit"
                        title="Close"
                    >

                        <i class="fa fa-times"></i>

                    </a>


                </div>


                <!-- =============================================
                     EDIT FORM
                ============================================= -->

                <form
                    method="POST"
                    action="<?php
                        echo e($current_page);
                    ?>"
                >


                    <!-- CART ID -->

                    <input
                        type="hidden"
                        name="cart_id"
                        value="<?php
                            echo e(
                                $edit_record["cart_id"]
                            );
                        ?>"
                    >


                    <div class="row">


                        <!-- CART ID -->

                        <div
                            class="col-md-6 mb-3"
                        >


                            <label class="form-label">

                                Cart ID

                            </label>


                            <input
                                type="number"
                                class="form-control"
                                value="<?php
                                    echo e(
                                        $edit_record[
                                            "cart_id"
                                        ]
                                    );
                                ?>"
                                readonly
                            >


                        </div>


                        <!-- CUSTOMER ID -->

                        <div
                            class="col-md-6 mb-3"
                        >


                            <label class="form-label">

                                Customer ID

                            </label>


                            <input
                                type="number"
                                name="customer_id"
                                class="form-control"
                                value="<?php
                                    echo e(
                                        $edit_record[
                                            "customer_id"
                                        ]
                                    );
                                ?>"
                                min="1"
                                required
                            >


                        </div>


                    </div>


                    <!-- BUTTONS -->

                    <div class="edit-buttons">


                        <button
                            type="submit"
                            name="update_cart"
                            value="1"
                            class="btn btn-success"
                        >

                            <i
                                class="fa fa-save me-1"
                            ></i>

                            Update Cart

                        </button>


                        <a
                            href="<?php
                                echo e($current_page);
                            ?>"
                            class="btn btn-danger"
                        >

                            <i
                                class="fa fa-times me-1"
                            ></i>

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
===================================================== -->

<script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
></script>


<!-- =====================================================
     BOOTSTRAP
===================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
></script>


<!-- =====================================================
     DARKPAN MAIN JS
===================================================== -->

<script src="js/main.js"></script>


<!-- =====================================================
     CUSTOM SIDEBAR JS
===================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        /* =================================================
           SIDEBAR
        ================================================= */

        const sidebar =
            document.querySelector(
                ".main-container .sidebar"
            );


        /* =================================================
           OVERLAY
        ================================================= */

        const overlay =
            document.getElementById(
                "sidebarOverlay"
            );


        /* =================================================
           TOGGLE BUTTONS
        ================================================= */

        const toggleButtons =
            document.querySelectorAll(
                ".sidebar-toggler, .navbar-toggler, .sidebar-toggle, [data-sidebar-toggle]"
            );


        /* =================================================
           SAFETY CHECK
        ================================================= */

        if (!sidebar) {

            console.log(
                "Sidebar not found."
            );

            return;
        }


        /* =================================================
           MOBILE CHECK
        ================================================= */

        function isMobile() {

            return window.innerWidth <= 991;
        }


        /* =================================================
           OPEN SIDEBAR
        ================================================= */

        function openSidebar() {

            document.body.classList.add(
                "sidebar-open"
            );

            document.body.classList.remove(
                "sidebar-collapsed"
            );
        }


        /* =================================================
           CLOSE SIDEBAR
        ================================================= */

        function closeSidebar() {

            document.body.classList.remove(
                "sidebar-open"
            );
        }


        /* =================================================
           TOGGLE SIDEBAR
        ================================================= */

        function toggleSidebar(event) {

            if (event) {

                event.preventDefault();

                event.stopImmediatePropagation();
            }


            /* =============================================
               MOBILE / TABLET
            ============================================= */

            if (isMobile()) {

                if (
                    document.body.classList.contains(
                        "sidebar-open"
                    )
                ) {

                    closeSidebar();

                }
                else {

                    openSidebar();
                }


                document.body.classList.remove(
                    "sidebar-collapsed"
                );

            }


            /* =============================================
               DESKTOP
            ============================================= */

            else {

                document.body.classList.toggle(
                    "sidebar-collapsed"
                );

                document.body.classList.remove(
                    "sidebar-open"
                );
            }
        }


        /* =================================================
           TOGGLE EVENT
           
           Capture phase + stopImmediatePropagation
           prevents DarkPan main.js double toggle.
        ================================================= */

        toggleButtons.forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    toggleSidebar,
                    true
                );

            }
        );


        /* =================================================
           OVERLAY CLICK
        ================================================= */

        if (overlay) {

            overlay.addEventListener(
                "click",
                function (event) {

                    event.preventDefault();

                    event.stopPropagation();

                    closeSidebar();

                },
                true
            );
        }


        /* =================================================
           OUTSIDE CLICK
        ================================================= */

        document.addEventListener(
            "click",
            function (event) {

                if (!isMobile()) {

                    return;
                }


                if (
                    !document.body.classList.contains(
                        "sidebar-open"
                    )
                ) {

                    return;
                }


                /* =========================================
                   SIDEBAR KE ANDAR CLICK
                ========================================= */

                if (
                    sidebar.contains(
                        event.target
                    )
                ) {

                    return;
                }


                /* =========================================
                   TOGGLE BUTTON
                ========================================= */

                const clickedToggle =
                    event.target.closest(
                        ".sidebar-toggler, .navbar-toggler, .sidebar-toggle, [data-sidebar-toggle]"
                    );


                if (clickedToggle) {

                    return;
                }


                /* =========================================
                   CLOSE SIDEBAR
                ========================================= */

                closeSidebar();

            },
            true
        );


        /* =================================================
           SIDEBAR LINKS
        ================================================= */

        sidebar.addEventListener(
            "click",
            function (event) {

                const link =
                    event.target.closest(
                        "a"
                    );


                if (!link) {

                    return;
                }


                /* =========================================
                   IGNORE EMPTY LINKS
                ========================================= */

                const href =
                    link.getAttribute(
                        "href"
                    );


                if (
                    !href ||
                    href === "#" ||
                    href ===
                        "javascript:void(0)" ||
                    href ===
                        "javascript:void(0);"
                ) {

                    return;
                }


                /* =========================================
                   IGNORE DROPDOWN BUTTONS
                ========================================= */

                if (
                    link.hasAttribute(
                        "data-bs-toggle"
                    ) ||
                    link.hasAttribute(
                        "data-toggle"
                    )
                ) {

                    return;
                }


                /* =========================================
                   MOBILE SIDEBAR CLOSE
                ========================================= */

                if (isMobile()) {

                    closeSidebar();
                }

            }
        );


        /* =================================================
           ESCAPE KEY
        ================================================= */

        document.addEventListener(
            "keydown",
            function (event) {

                if (
                    event.key ===
                    "Escape"
                ) {

                    closeSidebar();
                }

            }
        );


        /* =================================================
           RESIZE
        ================================================= */

        window.addEventListener(
            "resize",
            function () {

                if (
                    window.innerWidth > 991
                ) {

                    closeSidebar();

                }
                else {

                    document.body.classList.remove(
                        "sidebar-collapsed"
                    );
                }

            }
        );


    }
);

</script>


</body>

</html>