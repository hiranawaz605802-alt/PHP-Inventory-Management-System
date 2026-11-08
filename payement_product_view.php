<?php

include("Database.php");

$db = new Database();
$conn = $db->connection();


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
// CURRENT PAGE
// =====================================================

$current_page = basename($_SERVER["PHP_SELF"]);


// =====================================================
// MESSAGE
// =====================================================

$message = "";
$message_type = "";


// =====================================================
// DELETE PAYMENT
// =====================================================

if (isset($_GET["delete_id"])) {

    $delete_id = (int) $_GET["delete_id"];

    if ($delete_id > 0) {

        $delete_sql = "
            DELETE FROM entity_asad_payements
            WHERE payement_id = $delete_id
        ";

        if (mysqli_query($conn, $delete_sql)) {

            echo "<script>
                    alert('Payment Deleted Successfully');
                    window.location.href='" . e($current_page) . "';
                  </script>";

            exit();

        } else {

            $message = "Delete Error: " . mysqli_error($conn);
            $message_type = "danger";
        }

    } else {

        $message = "Invalid Payment ID.";
        $message_type = "danger";
    }
}


// =====================================================
// UPDATE PAYMENT
// =====================================================

if (isset($_POST["update_payment"])) {

    $payement_id = isset($_POST["payement_id"])
        ? (int) $_POST["payement_id"]
        : 0;

    $order_id = isset($_POST["order_id"])
        ? (int) $_POST["order_id"]
        : 0;

    $amount = isset($_POST["amount"])
        ? (float) $_POST["amount"]
        : 0;

    $payement_date = isset($_POST["payement_date"])
        ? mysqli_real_escape_string(
            $conn,
            $_POST["payement_date"]
        )
        : "";

    $payement_method = isset($_POST["payement_method"])
        ? mysqli_real_escape_string(
            $conn,
            $_POST["payement_method"]
        )
        : "";


    // =================================================
    // VALIDATION
    // =================================================

    if ($payement_id <= 0) {

        $message = "Invalid Payment ID.";
        $message_type = "danger";

    } elseif ($order_id <= 0) {

        $message = "Please enter Order ID.";
        $message_type = "danger";

    } elseif ($amount <= 0) {

        $message = "Please enter valid Amount.";
        $message_type = "danger";

    } elseif ($payement_date == "") {

        $message = "Please enter Payment Date.";
        $message_type = "danger";

    } elseif ($payement_method == "") {

        $message = "Please select Payment Method.";
        $message_type = "danger";

    } else {

        // =================================================
        // CHECK ORDER EXISTS
        // =================================================

        $check_order_sql = "
            SELECT order_id
            FROM entity_asad_order
            WHERE order_id = $order_id
            LIMIT 1
        ";

        $check_order_result = mysqli_query(
            $conn,
            $check_order_sql
        );


        if (!$check_order_result) {

            $message = "Order Check Error: " . mysqli_error($conn);
            $message_type = "danger";

        } elseif (mysqli_num_rows($check_order_result) == 0) {

            $message = "Selected Order ID does not exist.";
            $message_type = "danger";

        } else {

            // =================================================
            // UPDATE PAYMENT
            // =================================================

            $update_sql = "
                UPDATE entity_asad_payements

                SET
                    order_id = $order_id,
                    amount = $amount,
                    payement_date = '$payement_date',
                    payement_method = '$payement_method'

                WHERE payement_id = $payement_id
            ";


            $update_result = mysqli_query(
                $conn,
                $update_sql
            );


            if ($update_result) {

                echo "<script>
                        alert('Payment Updated Successfully');
                        window.location.href='" . e($current_page) . "';
                      </script>";

                exit();

            } else {

                $message =
                    "Update Error: " .
                    mysqli_error($conn);

                $message_type = "danger";
            }
        }
    }
}


// =====================================================
// GET EDIT RECORD
// =====================================================

$edit_record = null;


if (isset($_GET["edit_id"])) {

    $edit_id = (int) $_GET["edit_id"];


    if ($edit_id > 0) {

        $edit_sql = "
            SELECT
                payement_id,
                order_id,
                amount,
                payement_date,
                payement_method

            FROM entity_asad_payements

            WHERE payement_id = $edit_id

            LIMIT 1
        ";


        $edit_result = mysqli_query(
            $conn,
            $edit_sql
        );


        if (!$edit_result) {

            die(
                "Edit Query Error: " .
                mysqli_error($conn)
            );
        }


        if (mysqli_num_rows($edit_result) > 0) {

            $edit_record =
                mysqli_fetch_assoc(
                    $edit_result
                );
        }
    }
}


// =====================================================
// GET ALL PAYMENTS
// =====================================================

$sql = "
    SELECT
        payement_id,
        order_id,
        amount,
        payement_date,
        payement_method

    FROM entity_asad_payements

    ORDER BY payement_id DESC
";


$result = mysqli_query(
    $conn,
    $sql
);


if (!$result) {

    die(
        "Payment View Error: " .
        mysqli_error($conn)
    );
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Payment View</title>


    <!-- =================================================
         GOOGLE FONT
    ================================================== -->

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


    <!-- =================================================
         FONT AWESOME
    ================================================== -->

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
        rel="stylesheet"
    >


    <!-- =================================================
         BOOTSTRAP ICONS
    ================================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
        rel="stylesheet"
    >


    <!-- =================================================
         BOOTSTRAP
    ================================================== -->

    <link
        href="css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- =================================================
         DARKPAN
    ================================================== -->

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

    background: #000 !important;
}


/* =========================================================
   SIDEBAR
   SAME SYSTEM AS CUSTOMER PURCHASE PAGE
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

    min-width: 0 !important;

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
   DESKTOP SIDEBAR COLLAPSE
========================================================= */

@media (min-width: 992px) {

    body.sidebar-collapsed
    .main-container
    .sidebar {

        left: -250px !important;
    }


    body.sidebar-collapsed .content {

        width: 100% !important;

        max-width: 100% !important;

        margin-left: 0 !important;
    }
}


/* =========================================================
   MOBILE SIDEBAR OVERLAY
========================================================= */

.sidebar-mobile-overlay {

    display: none;

    position: fixed !important;

    inset: 0 !important;

    width: 100vw !important;
    height: 100vh !important;

    background: rgba(0, 0, 0, 0.55) !important;

    z-index: 99998 !important;

    cursor: pointer !important;
}


/* =========================================================
   BACK TO TOP
========================================================= */

.back-to-top {

    display: none !important;

    visibility: hidden !important;

    opacity: 0 !important;
}


/* =========================================================
   PAYMENT PAGE
========================================================= */

.payment-page {

    width: 100% !important;
    max-width: 100% !important;

    min-width: 0 !important;

    padding: 18px !important;

    margin: 0 !important;

    overflow-x: hidden !important;

    background: #000 !important;
}


/* =========================================================
   PAYMENT BOX
========================================================= */

.payment-box {

    width: 100% !important;
    max-width: 100% !important;

    min-width: 0 !important;

    background: #191c24 !important;

    border-radius: 8px !important;

    padding: 18px !important;

    margin: 0 !important;

    overflow: hidden !important;
}


/* =========================================================
   TITLE
========================================================= */

.payment-title {

    display: flex;

    align-items: center;

    color: #ffffff;

    font-size: 17px;

    font-weight: 600;

    margin: 0 0 15px 0;
}


/* =========================================================
   TABLE WRAPPER
========================================================= */

.payment-table-wrapper {

    width: 100% !important;
    max-width: 100% !important;

    min-width: 0 !important;

    overflow-x: auto !important;

    overflow-y: hidden !important;

    -webkit-overflow-scrolling: touch;
}


/* =========================================================
   TABLE
========================================================= */

.payment-table {

    width: 100% !important;

    min-width: 850px !important;

    margin: 0 !important;

    border-collapse: collapse !important;

    border-spacing: 0;

    table-layout: fixed !important;
}


/* =========================================================
   TABLE CELLS
========================================================= */

.payment-table th,
.payment-table td {

    padding: 10px 8px !important;

    vertical-align: middle !important;

    font-size: 13px;

    overflow-wrap: anywhere;

    word-break: break-word;
}


/* =========================================================
   HEADER
========================================================= */

.payment-table thead th {

    background: #08090d !important;

    color: #8f9bc0 !important;

    font-weight: 600;

    white-space: normal !important;

    line-height: 1.4;

    border-bottom:
        1px solid
        rgba(255,255,255,0.12) !important;
}


/* =========================================================
   BODY
========================================================= */

.payment-table tbody td {

    background: #08090d !important;

    color: #8f9bc0 !important;

    white-space: normal !important;

    line-height: 1.4;

    border-bottom:
        1px solid
        rgba(255,255,255,0.06) !important;
}


.payment-table tbody tr:hover td {

    background:
        rgba(255,255,255,0.035) !important;
}


/* =========================================================
   COLUMN WIDTH
========================================================= */

.payment-table th:nth-child(1),
.payment-table td:nth-child(1) {

    width: 12%;
}


.payment-table th:nth-child(2),
.payment-table td:nth-child(2) {

    width: 12%;
}


.payment-table th:nth-child(3),
.payment-table td:nth-child(3) {

    width: 15%;
}


.payment-table th:nth-child(4),
.payment-table td:nth-child(4) {

    width: 19%;
}


.payment-table th:nth-child(5),
.payment-table td:nth-child(5) {

    width: 20%;
}


.payment-table th:nth-child(6),
.payment-table td:nth-child(6) {

    width: 22%;
}


/* =========================================================
   ACTION
========================================================= */

.action-column {

    white-space: nowrap !important;

    text-align: left !important;
}


.action-btn {

    display: inline-flex !important;

    align-items: center !important;

    justify-content: center !important;

    margin-right: 3px !important;

    margin-bottom: 3px !important;

    white-space: nowrap !important;
}


.edit-btn,
.delete-btn {

    padding: 5px 9px !important;

    min-width: 68px;

    font-size: 12px !important;
}


/* =========================================================
   ALERT
========================================================= */

.alert {

    border-radius: 6px !important;

    margin-bottom: 15px !important;
}


/* =========================================================
   EDIT OVERLAY
========================================================= */

.edit-overlay {

    position: fixed !important;

    inset: 0 !important;

    width: 100% !important;
    height: 100% !important;

    background: rgba(0, 0, 0, 0.78) !important;

    display: flex !important;

    align-items: center !important;

    justify-content: center !important;

    padding: 20px !important;

    z-index: 100000 !important;

    overflow-y: auto !important;
}


/* =========================================================
   EDIT BOX
========================================================= */

.edit-box {

    width: 100% !important;

    max-width: 850px !important;

    background: #191c24 !important;

    border-radius: 10px !important;

    padding: 25px !important;

    box-shadow:
        0 10px 40px
        rgba(0, 0, 0, 0.6);
}


/* =========================================================
   EDIT HEADER
========================================================= */

.edit-title {

    width: 100% !important;

    display: flex !important;

    align-items: center !important;

    justify-content: space-between !important;

    margin-bottom: 20px !important;
}


.edit-title h5 {

    margin: 0 !important;

    color: #ffffff !important;

    font-size: 18px !important;

    font-weight: 600 !important;
}


/* =========================================================
   CLOSE
========================================================= */

.close-edit {

    width: 35px !important;

    height: 35px !important;

    min-width: 35px !important;

    display: flex !important;

    align-items: center !important;

    justify-content: center !important;

    border-radius: 50% !important;

    background: #dc3545 !important;

    color: #ffffff !important;

    text-decoration: none !important;
}


.close-edit:hover {

    background: #bb2d3b !important;

    color: #ffffff !important;

    text-decoration: none !important;
}


/* =========================================================
   LABEL
========================================================= */

.edit-box label {

    display: block !important;

    color: #ffffff !important;

    font-weight: 600 !important;

    margin-bottom: 7px !important;
}


/* =========================================================
   INPUTS
========================================================= */

.edit-box input,
.edit-box select {

    width: 100% !important;

    height: 43px !important;

    background: #ffffff !important;

    color: #000000 !important;

    border: none !important;

    border-radius: 5px !important;

    padding: 8px 10px !important;
}


.edit-box input[readonly] {

    background: #d6d6d6 !important;

    color: #333333 !important;

    cursor: not-allowed !important;
}


.edit-box input:focus,
.edit-box select:focus {

    outline: none !important;

    box-shadow:
        0 0 0 2px
        rgba(13, 110, 253, 0.25) !important;
}


/* =========================================================
   BUTTONS
========================================================= */

.edit-buttons {

    display: flex !important;

    align-items: center !important;

    gap: 8px !important;

    margin-top: 8px !important;
}


.update-btn,
.cancel-btn {

    min-width: 110px !important;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 991px) {


    /* =====================================================
       SIDEBAR MOBILE/TABLET
    ===================================================== */

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


    body.sidebar-open
    .main-container
    .sidebar {

        left: 0 !important;
    }


    body.sidebar-open
    .sidebar-mobile-overlay {

        display: block !important;
    }


    /* =====================================================
       CONTENT
    ===================================================== */

    .content {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        margin-left: 0 !important;

        padding: 0 !important;
    }


    /* =====================================================
       NAVBAR
    ===================================================== */

    .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        padding-left: 15px !important;

        padding-right: 15px !important;
    }


    /* =====================================================
       PAYMENT
    ===================================================== */

    .payment-page {

        width: 100% !important;

        max-width: 100% !important;

        padding: 15px !important;
    }


    .payment-box {

        width: 100% !important;

        max-width: 100% !important;

        padding: 16px !important;
    }


    .payment-table {

        min-width: 850px !important;
    }


    .payment-table th,
    .payment-table td {

        font-size: 12px;

        padding: 9px 6px !important;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767px) {


    /* =====================================================
       SIDEBAR
    ===================================================== */

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


    body.sidebar-open
    .main-container
    .sidebar {

        left: 0 !important;
    }


    body.sidebar-open
    .sidebar-mobile-overlay {

        display: block !important;
    }


    /* =====================================================
       CONTENT
    ===================================================== */

    .content {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        margin-left: 0 !important;

        padding: 0 !important;
    }


    /* =====================================================
       NAVBAR
    ===================================================== */

    .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        margin: 0 !important;

        padding-left: 10px !important;

        padding-right: 10px !important;
    }


    /* =====================================================
       PAYMENT PAGE
    ===================================================== */

    .payment-page {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        padding: 10px 7px !important;

        margin: 0 !important;

        overflow: hidden !important;
    }


    .payment-box {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        padding: 12px !important;

        border-radius: 7px !important;
    }


    .payment-title {

        font-size: 16px !important;

        margin-bottom: 12px !important;
    }


    /* =====================================================
       MOBILE CARD TABLE
    ===================================================== */

    .payment-table-wrapper {

        width: 100% !important;

        overflow: visible !important;
    }


    .payment-table {

        display: block !important;

        width: 100% !important;

        min-width: 0 !important;

        table-layout: auto !important;
    }


    .payment-table thead {

        display: none !important;
    }


    .payment-table tbody {

        display: block !important;

        width: 100% !important;
    }


    .payment-table tr {

        display: block !important;

        width: 100% !important;

        background: #08090d !important;

        border:
            1px solid
            rgba(255,255,255,0.10) !important;

        border-radius: 8px !important;

        margin-bottom: 10px !important;

        padding: 10px !important;

        overflow: hidden !important;
    }


    .payment-table td {

        display: flex !important;

        align-items: flex-start !important;

        justify-content: space-between !important;

        width: 100% !important;

        padding: 8px 4px !important;

        border: none !important;

        border-bottom:
            1px solid
            rgba(255,255,255,0.06) !important;

        background: transparent !important;

        font-size: 13px !important;

        text-align: right !important;

        line-height: 1.4 !important;

        gap: 10px !important;
    }


    .payment-table td:last-child {

        border-bottom: none !important;
    }


    .payment-table td::before {

        content: attr(data-label);

        flex: 0 0 40%;

        max-width: 40%;

        color: #ffffff !important;

        font-weight: 600 !important;

        text-align: left !important;

        margin-right: 8px !important;

        overflow-wrap: anywhere;

        word-break: break-word;
    }


    /* =====================================================
       ACTION BUTTONS
    ===================================================== */

    .action-column {

        display: flex !important;

        justify-content: flex-start !important;

        align-items: center !important;

        flex-wrap: wrap !important;

        gap: 5px !important;

        padding-top: 10px !important;

        text-align: left !important;
    }


    .action-column::before {

        flex: 0 0 100% !important;

        max-width: 100% !important;

        width: 100% !important;

        margin-bottom: 4px !important;
    }


    .edit-btn,
    .delete-btn {

        min-width: 70px !important;

        font-size: 12px !important;

        padding: 6px 10px !important;
    }


    /* =====================================================
       EDIT MODAL
    ===================================================== */

    .edit-overlay {

        padding: 8px !important;

        align-items: center !important;
    }


    .edit-box {

        width: 100% !important;

        max-width: 100% !important;

        padding: 15px !important;

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


    /* =====================================================
       FORM
    ===================================================== */

    .edit-box .row {

        margin-left: 0 !important;

        margin-right: 0 !important;
    }


    .edit-box .col-md-6,
    .edit-box .col-md-4 {

        width: 100% !important;

        padding-left: 0 !important;

        padding-right: 0 !important;

        margin-bottom: 12px !important;
    }


    /* =====================================================
       EDIT BUTTONS
    ===================================================== */

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
   SMALL MOBILE
========================================================= */

@media (max-width: 575px) {

    .payment-page {

        padding: 8px 6px !important;
    }


    .payment-box {

        padding: 10px !important;
    }


    .payment-table tr {

        padding: 9px !important;
    }


    .payment-table td {

        font-size: 12px !important;

        padding: 7px 3px !important;
    }


    .payment-table td::before {

        flex-basis: 40%;

        max-width: 40%;

        font-size: 11px;
    }


    .edit-box {

        padding: 13px !important;
    }

}


/* =========================================================
   VERY SMALL MOBILE
========================================================= */

@media (max-width: 400px) {

    .payment-page {

        padding: 6px 5px !important;
    }


    .payment-box {

        padding: 8px !important;

        border-radius: 6px !important;
    }


    .payment-table tr {

        padding: 8px !important;

        border-radius: 7px !important;
    }


    .payment-table td {

        font-size: 11px !important;

        padding: 6px 3px !important;
    }


    .payment-table td::before {

        font-size: 10px !important;
    }


    .edit-box {

        padding: 12px !important;
    }

}


/* =========================================================
   LARGE DESKTOP
========================================================= */

@media (min-width: 1400px) {

    .payment-page {

        padding: 20px !important;
    }


    .payment-table th,
    .payment-table td {

        font-size: 13px;

        padding: 12px 8px !important;
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
    ================================================== -->

    <?php include("sidebar.php"); ?>


    <!-- =================================================
         MOBILE SIDEBAR OVERLAY
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
             PAYMENT PAGE
        ================================================== -->

        <div class="payment-page">


            <!-- =================================================
                 MESSAGE
            ================================================== -->

            <?php if ($message != "") { ?>

                <div class="alert alert-<?php echo e($message_type); ?>">

                    <?php echo e($message); ?>

                </div>

            <?php } ?>


            <!-- =================================================
                 PAYMENT BOX
            ================================================== -->

            <div class="payment-box">


                <!-- =================================================
                     TITLE
                ================================================== -->

                <div class="payment-title">

                    <i class="fa fa-money-bill me-2"></i>

                    Payments

                </div>


                <!-- =================================================
                     TABLE
                ================================================== -->

                <div class="payment-table-wrapper">


                    <table
                        class="table table-dark table-hover payment-table"
                    >


                        <!-- =================================================
                             HEADER
                        ================================================== -->

                        <thead>

                            <tr>

                                <th>
                                    Payment ID
                                </th>

                                <th>
                                    Order ID
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Payment Date
                                </th>

                                <th>
                                    Payment Method
                                </th>

                                <th class="action-column">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <!-- =================================================
                             BODY
                        ================================================== -->

                        <tbody>


                        <?php

                        if (mysqli_num_rows($result) > 0) {

                            while (
                                $row =
                                mysqli_fetch_assoc($result)
                            ) {

                        ?>

                            <tr>


                                <!-- PAYMENT ID -->

                                <td data-label="Payment ID">

                                    <?php
                                    echo e(
                                        $row["payement_id"]
                                    );
                                    ?>

                                </td>


                                <!-- ORDER ID -->

                                <td data-label="Order ID">

                                    <?php
                                    echo e(
                                        $row["order_id"]
                                    );
                                    ?>

                                </td>


                                <!-- AMOUNT -->

                                <td data-label="Amount">

                                    Rs.

                                    <?php
                                    echo number_format(
                                        (float)$row["amount"],
                                        2
                                    );
                                    ?>

                                </td>


                                <!-- PAYMENT DATE -->

                                <td data-label="Payment Date">

                                    <?php
                                    echo e(
                                        $row["payement_date"]
                                    );
                                    ?>

                                </td>


                                <!-- PAYMENT METHOD -->

                                <td data-label="Payment Method">

                                    <?php
                                    echo e(
                                        $row["payement_method"]
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
                                            echo e($current_page);
                                        ?>?edit_id=<?php
                                            echo (int)
                                                $row["payement_id"];
                                        ?>"
                                        class="btn btn-primary btn-sm edit-btn action-btn"
                                    >

                                        <i class="fa fa-edit me-1"></i>

                                        Edit

                                    </a>


                                    <!-- DELETE -->

                                    <a
                                        href="<?php
                                            echo e($current_page);
                                        ?>?delete_id=<?php
                                            echo (int)
                                                $row["payement_id"];
                                        ?>"
                                        class="btn btn-danger btn-sm delete-btn action-btn"
                                        onclick="return confirm('Are you sure you want to delete this Payment?');"
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
                                    colspan="6"
                                    class="text-center"
                                >

                                    No Payment Record Found

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
         EDIT OVERLAY
    ================================================= -->

    <?php if ($edit_record != null) { ?>


        <div class="edit-overlay">


            <div class="edit-box">


                <!-- =================================================
                     EDIT TITLE
                ================================================= -->

                <div class="edit-title">


                    <h5>

                        <i class="fa fa-edit me-2"></i>

                        Edit Payment

                    </h5>


                    <a
                        href="<?php echo e($current_page); ?>"
                        class="close-edit"
                        title="Close"
                    >

                        <i class="fa fa-times"></i>

                    </a>


                </div>


                <!-- =================================================
                     EDIT FORM
                ================================================= -->

                <form
                    method="POST"
                    action="<?php echo e($current_page); ?>"
                >


                    <!-- PAYMENT ID -->

                    <input
                        type="hidden"
                        name="payement_id"
                        value="<?php
                            echo e(
                                $edit_record["payement_id"]
                            );
                        ?>"
                    >


                    <!-- =================================================
                         FIRST ROW
                    ================================================= -->

                    <div class="row">


                        <!-- PAYMENT ID -->

                        <div class="col-md-6 mb-3">

                            <label>
                                Payment ID
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                value="<?php
                                    echo e(
                                        $edit_record["payement_id"]
                                    );
                                ?>"
                                readonly
                            >

                        </div>


                        <!-- ORDER ID -->

                        <div class="col-md-6 mb-3">

                            <label>
                                Order ID
                            </label>

                            <input
                                type="number"
                                name="order_id"
                                class="form-control"
                                value="<?php
                                    echo e(
                                        $edit_record["order_id"]
                                    );
                                ?>"
                                min="1"
                                required
                            >

                        </div>


                    </div>


                    <!-- =================================================
                         SECOND ROW
                    ================================================= -->

                    <div class="row">


                        <!-- AMOUNT -->

                        <div class="col-md-4 mb-3">

                            <label>
                                Amount
                            </label>

                            <input
                                type="number"
                                name="amount"
                                class="form-control"
                                value="<?php
                                    echo e(
                                        $edit_record["amount"]
                                    );
                                ?>"
                                step="0.01"
                                min="0.01"
                                required
                            >

                        </div>


                        <!-- PAYMENT DATE -->

                        <div class="col-md-4 mb-3">

                            <label>
                                Payment Date
                            </label>

                            <input
                                type="date"
                                name="payement_date"
                                class="form-control"
                                value="<?php
                                    echo e(
                                        $edit_record["payement_date"]
                                    );
                                ?>"
                                required
                            >

                        </div>


                        <!-- PAYMENT METHOD -->

                        <div class="col-md-4 mb-3">

                            <label>
                                Payment Method
                            </label>

                            <select
                                name="payement_method"
                                class="form-control"
                                required
                            >

                                <option value="">
                                    -- Select Method --
                                </option>


                                <option
                                    value="Cash"
                                    <?php
                                    if (
                                        $edit_record[
                                            "payement_method"
                                        ] == "Cash"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    Cash
                                </option>


                                <option
                                    value="Card"
                                    <?php
                                    if (
                                        $edit_record[
                                            "payement_method"
                                        ] == "Card"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    Card
                                </option>


                                <option
                                    value="Bank"
                                    <?php
                                    if (
                                        $edit_record[
                                            "payement_method"
                                        ] == "Bank"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    Bank
                                </option>


                                <option
                                    value="Online"
                                    <?php
                                    if (
                                        $edit_record[
                                            "payement_method"
                                        ] == "Online"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    Online
                                </option>


                            </select>

                        </div>


                    </div>


                    <!-- =================================================
                         BUTTONS
                    ================================================= -->

                    <div class="edit-buttons">


                        <button
                            type="submit"
                            name="update_payment"
                            class="btn btn-success update-btn"
                        >

                            <i class="fa fa-save me-1"></i>

                            Update Payment

                        </button>


                        <a
                            href="<?php echo e($current_page); ?>"
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

document.addEventListener(
    "DOMContentLoaded",
    function () {


        // =================================================
        // FIND ALL SIDEBAR TOGGLERS
        // =================================================

        const togglers =
            document.querySelectorAll(
                ".sidebar-toggler, .navbar-toggler"
            );


        // =================================================
        // FIND SIDEBAR
        // =================================================

        const sidebar =
            document.querySelector(
                ".main-container .sidebar"
            );


        // =================================================
        // FIND OVERLAY
        // =================================================

        const overlay =
            document.getElementById(
                "sidebarOverlay"
            );


        // =================================================
        // SAFETY CHECK
        // =================================================

        if (!sidebar) {

            console.log(
                "Sidebar not found."
            );

            return;
        }


        // =================================================
        // CLOSE SIDEBAR
        // =================================================

        function closeSidebar() {

            document.body.classList.remove(
                "sidebar-open"
            );

            document.body.classList.remove(
                "sidebar-collapsed"
            );
        }


        // =================================================
        // OPEN / CLOSE SIDEBAR
        // =================================================

        function toggleSidebar(event) {

            if (event) {

                event.preventDefault();

                event.stopPropagation();
            }


            // =================================================
            // MOBILE / TABLET
            // =================================================

            if (window.innerWidth <= 991) {

                document.body.classList.toggle(
                    "sidebar-open"
                );

                document.body.classList.remove(
                    "sidebar-collapsed"
                );

            }


            // =================================================
            // DESKTOP
            // =================================================

            else {

                document.body.classList.toggle(
                    "sidebar-collapsed"
                );

                document.body.classList.remove(
                    "sidebar-open"
                );
            }
        }


        // =================================================
        // ADD TOGGLE EVENT
        // =================================================

        togglers.forEach(
            function (toggler) {

                toggler.addEventListener(
                    "click",
                    toggleSidebar,
                    true
                );

            }
        );


        // =================================================
        // SIDEBAR LINKS
        // =================================================

        const sidebarLinks =
            sidebar.querySelectorAll("a");


        sidebarLinks.forEach(
            function (link) {

                link.addEventListener(
                    "click",
                    function (event) {

                        const href =
                            link.getAttribute(
                                "href"
                            );


                        // =================================================
                        // IGNORE EMPTY LINKS
                        // =================================================

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


                        // =================================================
                        // IGNORE BOOTSTRAP DROPDOWN BUTTONS
                        // =================================================

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


                        // =================================================
                        // MOBILE / TABLET
                        // =================================================

                        if (
                            window.innerWidth <= 991
                        ) {

                            event.preventDefault();

                            event.stopPropagation();


                            // Close first
                            document.body.classList.remove(
                                "sidebar-open"
                            );


                            // Then open clicked page
                            window.location.assign(
                                link.href
                            );

                        }

                    },
                    true
                );

            }
        );


        // =================================================
        // OVERLAY CLICK
        // =================================================

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


        // =================================================
        // OUTSIDE CLICK
        // =================================================

        document.addEventListener(
            "click",
            function (event) {

                // Only mobile/tablet
                if (
                    window.innerWidth > 991
                ) {

                    return;
                }


                // Sidebar not open
                if (
                    !document.body.classList.contains(
                        "sidebar-open"
                    )
                ) {

                    return;
                }


                // Click inside sidebar
                const clickedInsideSidebar =
                    sidebar.contains(
                        event.target
                    );


                // Check toggler
                let clickedToggler = false;


                togglers.forEach(
                    function (toggler) {

                        if (
                            toggler.contains(
                                event.target
                            )
                        ) {

                            clickedToggler = true;
                        }

                    }
                );


                // Close when outside
                if (
                    !clickedInsideSidebar &&
                    !clickedToggler
                ) {

                    closeSidebar();
                }

            },
            true
        );


        // =================================================
        // ESC KEY
        // =================================================

        document.addEventListener(
            "keydown",
            function (event) {

                if (
                    event.key === "Escape" ||
                    event.key === "Esc"
                ) {

                    closeSidebar();
                }

            }
        );


        // =================================================
        // RESIZE FIX
        // =================================================

        window.addEventListener(
            "resize",
            function () {

                if (
                    window.innerWidth > 991
                ) {

                    document.body.classList.remove(
                        "sidebar-open"
                    );

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