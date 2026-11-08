<?php

// =====================================================
// DATABASE
// =====================================================

include_once("Database.php");

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
// SELECTED PHONE
// =====================================================

$selected_phone = isset($_GET["phone_number"])
    ? trim($_GET["phone_number"])
    : "";


// =====================================================
// CUSTOMER VARIABLES
// =====================================================

$customer_id = 0;
$customer_record = null;

$details_result = null;
$details_stmt = null;

$purchase_rows = [];


// =====================================================
// FIND CUSTOMER BY PHONE NUMBER
// =====================================================

if ($selected_phone !== "") {

    $customer_sql = "
        SELECT
            customer_id,
            first_name,
            last_name,
            email,
            phone_number,
            address,
            registration_date
        FROM entity_asad_customer
        WHERE phone_number = ?
        LIMIT 1
    ";

    $customer_stmt = mysqli_prepare(
        $conn,
        $customer_sql
    );

    if (!$customer_stmt) {

        die(
            "Customer Prepare Error: " .
            mysqli_error($conn)
        );
    }

    mysqli_stmt_bind_param(
        $customer_stmt,
        "s",
        $selected_phone
    );

    if (!mysqli_stmt_execute($customer_stmt)) {

        die(
            "Customer Execute Error: " .
            mysqli_stmt_error($customer_stmt)
        );
    }

    $customer_result =
        mysqli_stmt_get_result(
            $customer_stmt
        );

    if (
        $customer_result &&
        mysqli_num_rows($customer_result) > 0
    ) {

        $customer_record =
            mysqli_fetch_assoc(
                $customer_result
            );

        $customer_id =
            (int)$customer_record["customer_id"];
    }

    mysqli_stmt_close($customer_stmt);
}


// =====================================================
// GET COMPLETE PURCHASE DETAILS
// =====================================================

if (
    $customer_id > 0 &&
    $customer_record !== null
) {

    $details_sql = "

        SELECT

            /* CUSTOMER */

            c.customer_id,

            CONCAT(
                c.first_name,
                ' ',
                c.last_name
            ) AS customer_name,

            c.first_name,

            c.last_name,

            c.email AS customer_email,

            c.phone_number AS customer_phone,

            c.address,


            /* ORDER */

            o.order_id,

            o.order_date,

            o.total_amount,

            o.status AS order_status,


            /* ORDER ITEM */

            oi.order_item_id,

            oi.quantity,

            oi.until_price,


            /* PRODUCT */

            p.id AS product_id,

            p.name AS product_name,

            p.discraption AS product_description,


            /* PAYMENT */

            py.payement_id,

            py.amount AS payment_amount,

            py.payement_date,

            py.payement_method


        FROM entity_asad_customer c


        INNER JOIN entity_asad_order o
            ON c.customer_id = o.customer_id


        INNER JOIN entity_asad_order_item oi
            ON o.order_id = oi.order_id


        INNER JOIN entity_asad_product p
            ON oi.product_id = p.id


        LEFT JOIN entity_asad_payements py
            ON o.order_id = py.order_id


        WHERE c.customer_id = ?


        ORDER BY
            o.order_id DESC,
            oi.order_item_id ASC

    ";


    $details_stmt = mysqli_prepare(
        $conn,
        $details_sql
    );


    if (!$details_stmt) {

        die(
            "Details Prepare Error: " .
            mysqli_error($conn)
        );
    }


    mysqli_stmt_bind_param(
        $details_stmt,
        "i",
        $customer_id
    );


    if (!mysqli_stmt_execute($details_stmt)) {

        die(
            "Details Execute Error: " .
            mysqli_stmt_error($details_stmt)
        );
    }


    $details_result =
        mysqli_stmt_get_result(
            $details_stmt
        );


    if ($details_result) {

        while (
            $row =
            mysqli_fetch_assoc(
                $details_result
            )
        ) {

            $purchase_rows[] = $row;
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <title>
        DarkPan - Customer Purchase Details
    </title>

    <meta
        content="width=device-width, initial-scale=1.0"
        name="viewport"
    >

    <meta
        content=""
        name="keywords"
    >

    <meta
        content=""
        name="description"
    >


    <!-- =====================================================
         FAVICON
    ====================================================== -->

    <link
        href="img/favicon.ico"
        rel="icon"
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
        href="https://fonts.googleapis.com"
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
         LIBRARIES
    ====================================================== -->

    <link
        href="lib/owlcarousel/assets/owl.carousel.min.css"
        rel="stylesheet"
    >

    <link
        href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css"
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
         DARKPAN CSS
    ====================================================== -->

    <link
        href="css/style.css"
        rel="stylesheet"
    >


    <!-- =====================================================
         PAGE + SIDEBAR CSS
    ====================================================== -->

    <style>

        /* =====================================================
           GLOBAL
        ===================================================== */

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


        /* =====================================================
           MAIN PAGE
        ===================================================== */

        .main-page-container {

            width: 100% !important;

            max-width: 100% !important;

            min-height: 100vh !important;

            position: relative !important;

            margin: 0 !important;

            padding: 0 !important;

            overflow-x: hidden !important;

            background: #000 !important;

        }


        /* =====================================================
           SIDEBAR
           EXACT WORKING SYSTEM
        ===================================================== */

        .main-page-container .sidebar {

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


        /* =====================================================
           CONTENT
        ===================================================== */

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


        /* =====================================================
           NAVBAR
        ===================================================== */

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


        /* =====================================================
           TOGGLER
        ===================================================== */

        .sidebar-toggler,
        .navbar-toggler {

            position: relative !important;

            z-index: 100001 !important;

            cursor: pointer !important;

            pointer-events: auto !important;

            border: 0 !important;

            outline: none !important;

        }


        /* =====================================================
           DESKTOP COLLAPSE
        ===================================================== */

        @media (min-width: 992px) {

            body.sidebar-collapsed
            .main-page-container
            .sidebar {

                left: -250px !important;

            }


            body.sidebar-collapsed .content {

                width: 100% !important;

                max-width: 100% !important;

                margin-left: 0 !important;

            }

        }


        /* =====================================================
           MOBILE OVERLAY
        ===================================================== */

        .sidebar-mobile-overlay {

            display: none;

            position: fixed;

            inset: 0;

            width: 100vw;

            height: 100vh;

            background:
                rgba(0, 0, 0, 0.55);

            z-index: 99998;

        }


        /* =====================================================
           BACK TO TOP
        ===================================================== */

        .back-to-top {

            display: none !important;

            visibility: hidden !important;

            opacity: 0 !important;

        }


        /* =====================================================
           CUSTOMER PAGE WRAPPER
        ===================================================== */

        .customer-page-wrapper {

            width: 100%;

            max-width: 100%;

            min-width: 0;

            padding: 18px;

            overflow: hidden;

        }


        /* =====================================================
           COMMON BOX
        ===================================================== */

        .customer-box {

            width: 100%;

            max-width: 100%;

            min-width: 0;

            background: #191c24;

            border-radius: 8px;

            padding: 18px;

            margin-bottom: 15px;

            overflow: hidden;

        }


        /* =====================================================
           BOX TITLE
        ===================================================== */

        .customer-box-title {

            display: flex;

            align-items: center;

            color: #ffffff;

            font-size: 17px;

            font-weight: 600;

            margin-bottom: 15px;

            line-height: 1.4;

        }


        .customer-box-title i {

            flex-shrink: 0;

            font-size: 18px;

        }


        /* =====================================================
           SEARCH ROW
        ===================================================== */

        .phone-search-row {

            display: flex;

            width: 100%;

            gap: 10px;

            align-items: stretch;

        }


        /* =====================================================
           PHONE INPUT
        ===================================================== */

        .customer-input {

            flex: 1 1 auto;

            width: 100%;

            min-width: 0;

            height: 45px;

            background: #ffffff !important;

            color: #111111 !important;

            border:
                1px solid
                #ced4da !important;

            border-radius: 6px !important;

            padding: 8px 14px;

            font-size: 15px;

            outline: none !important;

        }


        .customer-input::placeholder {

            color: #777777;

        }


        .customer-input:focus {

            border-color:
                #86b7fe !important;

            box-shadow:
                0 0 0 0.15rem
                rgba(13,110,253,0.15) !important;

        }


        /* =====================================================
           SEARCH BUTTON
        ===================================================== */

        .phone-search-btn {

            flex: 0 0 auto;

            height: 45px;

            border: none;

            border-radius: 6px;

            padding: 0 22px;

            background: #0d6efd;

            color: #ffffff;

            font-size: 14px;

            font-weight: 600;

            cursor: pointer;

            transition: 0.2s ease;

        }


        .phone-search-btn:hover {

            background: #0b5ed7;

        }


        .phone-search-btn i {

            margin-right: 6px;

        }


        /* =====================================================
           CUSTOMER INFORMATION
        ===================================================== */

        .customer-info-grid {

            width: 100%;

            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 12px;

        }


        .info-item {

            width: 100%;

            min-width: 0;

            color: #8f9bc0;

            font-size: 13px;

            line-height: 1.5;

            overflow-wrap: anywhere;

            word-break: break-word;

        }


        .info-item strong {

            color: #ffffff;

        }


        .info-item.full-width {

            grid-column: span 2;

        }


        /* =====================================================
           DETAILS TITLE
        ===================================================== */

        .details-title {

            display: flex;

            align-items: center;

            color: #ffffff;

            font-size: 17px;

            font-weight: 600;

            margin-bottom: 15px;

        }


        /* =====================================================
           DESKTOP TABLE
        ===================================================== */

        .desktop-table-wrapper {

            width: 100%;

            max-width: 100%;

            min-width: 0;

            overflow-x: auto;

            overflow-y: hidden;

            -webkit-overflow-scrolling: touch;

            border-radius: 6px;

        }


        .desktop-table {

            width: 100%;

            min-width: 1100px;

            margin: 0;

            border-collapse: collapse;

            table-layout: fixed;

        }


        /* =====================================================
           TABLE HEADER
        ===================================================== */

        .desktop-table thead th {

            background: #08090d !important;

            color: #8f9bc0 !important;

            font-size: 10px;

            font-weight: 600;

            padding: 11px 7px !important;

            line-height: 1.35;

            vertical-align: middle;

            overflow-wrap: anywhere;

            word-break: break-word;

            border-bottom:
                1px solid
                rgba(255,255,255,0.12) !important;

        }


        /* =====================================================
           TABLE BODY
        ===================================================== */

        .desktop-table tbody td {

            background: #08090d !important;

            color: #8f9bc0 !important;

            font-size: 10px;

            padding: 11px 7px !important;

            line-height: 1.4;

            vertical-align: middle;

            overflow-wrap: anywhere;

            word-break: break-word;

            border-bottom:
                1px solid
                rgba(255,255,255,0.06) !important;

        }


        .desktop-table tbody tr:hover td {

            background:
                rgba(255,255,255,0.035) !important;

        }


        /* =====================================================
           TEXT COLORS
        ===================================================== */

        .customer-name {

            color: #ffffff !important;

            font-weight: 600;

            font-size: 11px;

            overflow-wrap: anywhere;

            word-break: break-word;

        }


        .customer-phone {

            color: #20c997 !important;

            font-size: 10px;

            font-weight: 600;

            overflow-wrap: anywhere;

            word-break: break-word;

        }


        .customer-email {

            color: #8f9bc0 !important;

            font-size: 10px;

            overflow-wrap: anywhere;

            word-break: break-all;

        }


        .product-name {

            color: #ffffff !important;

            font-weight: 600;

            font-size: 11px;

            overflow-wrap: anywhere;

            word-break: break-word;

        }


        /* =====================================================
           ORDER STATUS
        ===================================================== */

        .order-badge {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            max-width: 100%;

            padding: 4px 8px;

            border-radius: 20px;

            font-size: 9px;

            font-weight: 600;

            text-align: center;

            overflow-wrap: anywhere;

        }


        .status-completed {

            background:
                rgba(25,135,84,0.18);

            color: #20c997 !important;

        }


        .status-pending {

            background:
                rgba(255,193,7,0.18);

            color: #ffc107 !important;

        }


        .status-cancelled {

            background:
                rgba(220,53,69,0.18);

            color: #ff6b7a !important;

        }


        .status-default {

            background:
                rgba(108,117,125,0.18);

            color: #adb5bd !important;

        }


        /* =====================================================
           MOBILE PURCHASE LIST
        ===================================================== */

        .mobile-purchase-list {

            display: none;

            width: 100%;

            max-width: 100%;

            min-width: 0;

        }


        /* =====================================================
           PURCHASE CARD
        ===================================================== */

        .purchase-card {

            width: 100%;

            max-width: 100%;

            min-width: 0;

            background: #08090d;

            border:
                1px solid
                rgba(255,255,255,0.10);

            border-radius: 8px;

            padding: 13px;

            margin-bottom: 12px;

            overflow: hidden;

        }


        /* =====================================================
           CARD HEADER
        ===================================================== */

        .purchase-card-header {

            display: flex;

            justify-content: space-between;

            align-items: flex-start;

            gap: 10px;

            width: 100%;

            min-width: 0;

            padding-bottom: 10px;

            margin-bottom: 5px;

            border-bottom:
                1px solid
                rgba(255,255,255,0.08);

        }


        .purchase-card-title {

            flex: 1 1 auto;

            min-width: 0;

            color: #ffffff;

            font-size: 13px;

            font-weight: 600;

            line-height: 1.4;

            overflow-wrap: anywhere;

            word-break: break-word;

        }


        .purchase-card-order {

            flex: 0 0 auto;

            color: #8f9bc0;

            font-size: 11px;

            white-space: nowrap;

        }


        /* =====================================================
           CARD ROW
        ===================================================== */

        .purchase-card-row {

            display: flex;

            align-items: flex-start;

            justify-content: space-between;

            gap: 10px;

            width: 100%;

            min-width: 0;

            padding: 8px 0;

            border-bottom:
                1px solid
                rgba(255,255,255,0.06);

        }


        .purchase-card-row:last-child {

            border-bottom: none;

        }


        /* =====================================================
           CARD LABEL
        ===================================================== */

        .purchase-card-label {

            flex:
                0 0 40%;

            max-width: 40%;

            min-width: 0;

            color: #ffffff;

            font-size: 10px;

            font-weight: 600;

            line-height: 1.4;

            overflow-wrap: anywhere;

            word-break: break-word;

        }


        /* =====================================================
           CARD VALUE
        ===================================================== */

        .purchase-card-value {

            flex:
                1 1 60%;

            max-width: 60%;

            min-width: 0;

            color: #8f9bc0;

            font-size: 10px;

            text-align: right;

            line-height: 1.4;

            overflow-wrap: anywhere;

            word-break: break-word;

        }


        .purchase-card-value.customer-name,
        .purchase-card-value.product-name {

            color: #ffffff !important;

            font-size: 11px;

            font-weight: 600;

        }


        .purchase-card-value.customer-phone {

            color: #20c997 !important;

            font-size: 10px;

            font-weight: 600;

        }


        .purchase-card-value.customer-email {

            color: #8f9bc0 !important;

            font-size: 9px;

            word-break: break-all;

            overflow-wrap: anywhere;

        }


        /* =====================================================
           EMPTY BOX
        ===================================================== */

        .empty-box {

            width: 100%;

            max-width: 100%;

            text-align: center;

            color: #8f9bc0;

            padding: 35px 15px;

            font-size: 14px;

            line-height: 1.6;

            overflow-wrap: anywhere;

        }


        .empty-box i {

            color: #8f9bc0;

        }


        /* =====================================================
           TABLET / MOBILE SIDEBAR
        ===================================================== */

        @media (max-width: 991px) {

            .main-page-container .sidebar {

                width: 250px !important;

                min-width: 250px !important;

                height: 100vh !important;

                position: fixed !important;

                top: 0 !important;

                left: -250px !important;

                bottom: 0 !important;

                margin: 0 !important;

                padding: 0 !important;

                z-index: 99999 !important;

                transition:
                    left 0.3s ease !important;

                overflow-y: auto !important;

                overflow-x: hidden !important;

            }


            /* OPEN */

            body.sidebar-open
            .main-page-container
            .sidebar {

                left: 0 !important;

            }


            /* OVERLAY */

            body.sidebar-open
            .sidebar-mobile-overlay {

                display: block !important;

            }


            /* CONTENT */

            .content {

                width: 100% !important;

                max-width: 100% !important;

                min-width: 0 !important;

                margin-left: 0 !important;

                padding: 0 !important;

            }


            /* NAVBAR */

            .content > .navbar {

                width: 100% !important;

                min-height: 70px !important;

                padding-left: 15px !important;

                padding-right: 15px !important;

            }


            /* OLD COLLAPSED STATE */

            body.sidebar-collapsed .content {

                width: 100% !important;

                max-width: 100% !important;

                margin-left: 0 !important;

            }

        }


        /* =====================================================
           DESKTOP TABLE
        ===================================================== */

        @media (min-width: 1200px) {

            .desktop-table-wrapper {

                display: block !important;

            }


            .mobile-purchase-list {

                display: none !important;

            }

        }


        /* =====================================================
           LAPTOP / TABLET CONTENT
        ===================================================== */

        @media (max-width: 1199px) {

            .customer-page-wrapper {

                padding: 15px;

            }


            .desktop-table-wrapper {

                display: none !important;

            }


            .mobile-purchase-list {

                display: block !important;

            }


            .customer-info-grid {

                grid-template-columns:
                    repeat(2, minmax(0, 1fr));

            }


            .info-item.full-width {

                grid-column: span 2;

            }

        }


        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 767px) {

            .customer-page-wrapper {

                padding: 8px;

            }


            .customer-box {

                padding: 12px;

                margin-bottom: 10px;

                border-radius: 8px;

            }


            .customer-box-title,
            .details-title {

                font-size: 15px;

                margin-bottom: 12px;

            }


            /* PHONE SEARCH */

            .phone-search-row {

                flex-direction: column;

                gap: 8px;

            }


            .customer-input {

                width: 100%;

                height: 43px;

                font-size: 13px;

            }


            .phone-search-btn {

                width: 100%;

                height: 43px;

                font-size: 13px;

            }


            /* CUSTOMER INFO */

            .customer-info-grid {

                grid-template-columns:
                    1fr;

                gap: 8px;

            }


            .info-item.full-width {

                grid-column: span 1;

            }


            .info-item {

                font-size: 12px;

            }


            /* PURCHASE CARD */

            .purchase-card {

                padding: 12px;

                border-radius: 8px;

            }


            .purchase-card-title {

                font-size: 13px;

            }


            .purchase-card-order {

                font-size: 10px;

            }


            .purchase-card-row {

                gap: 8px;

                padding: 8px 0;

            }


            .purchase-card-label {

                flex:
                    0 0 42%;

                max-width: 42%;

                font-size: 10px;

            }


            .purchase-card-value {

                flex:
                    1 1 58%;

                max-width: 58%;

                font-size: 10px;

            }

        }


        /* =====================================================
           SMALL MOBILE
        ===================================================== */

        @media (max-width: 575px) {

            .customer-page-wrapper {

                padding: 6px;

            }


            .customer-box {

                padding: 10px;

            }


            .purchase-card {

                padding: 9px;

            }


            .purchase-card-title {

                font-size: 12px;

            }


            .purchase-card-order {

                font-size: 9px;

            }


            .purchase-card-row {

                padding: 7px 0;

            }


            .purchase-card-label {

                flex:
                    0 0 40%;

                max-width: 40%;

                font-size: 9px;

            }


            .purchase-card-value {

                flex:
                    1 1 60%;

                max-width: 60%;

                font-size: 9px;

            }


            .purchase-card-value.customer-email {

                font-size: 8px;

            }


            .empty-box {

                padding: 25px 10px;

                font-size: 12px;

            }

        }


        /* =====================================================
           VERY SMALL MOBILE
        ===================================================== */

        @media (max-width: 400px) {

            .customer-page-wrapper {

                padding: 5px;

            }


            .customer-box {

                padding: 8px;

                border-radius: 6px;

            }


            .purchase-card {

                padding: 8px;

            }


            .purchase-card-label {

                font-size: 8px;

            }


            .purchase-card-value {

                font-size: 8px;

            }


            .purchase-card-value.customer-email {

                font-size: 7px;

            }


            .purchase-card-title {

                font-size: 11px;

            }


            .purchase-card-order {

                font-size: 8px;

            }

        }


        /* =====================================================
           LARGE DESKTOP
        ===================================================== */

        @media (min-width: 1400px) {

            .customer-page-wrapper {

                padding: 20px;

            }


            .desktop-table thead th {

                font-size: 11px;

                padding: 12px 8px !important;

            }


            .desktop-table tbody td {

                font-size: 11px;

                padding: 12px 8px !important;

            }


            .customer-name,
            .product-name {

                font-size: 12px;

            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     MAIN PAGE
====================================================== -->

<div class="main-page-container">


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
             CUSTOMER PAGE
        ================================================== -->

        <div class="customer-page-wrapper">


            <!-- =================================================
                 SEARCH BOX
            ================================================== -->

            <div class="customer-box">


                <div class="customer-box-title">

                    <i class="fa fa-phone me-2"></i>

                    Search Customer By Phone Number

                </div>


                <form
                    method="GET"
                    action="<?php echo e($current_page); ?>"
                >

                    <div class="phone-search-row">


                        <input
                            type="text"
                            name="phone_number"
                            class="customer-input"
                            placeholder="Enter phone number e.g. 03492508474"
                            value="<?php
                                echo e($selected_phone);
                            ?>"
                            autocomplete="off"
                            inputmode="numeric"
                        >


                        <button
                            type="submit"
                            class="phone-search-btn"
                        >

                            <i class="fa fa-search"></i>

                            Search

                        </button>


                    </div>

                </form>


            </div>


            <?php if ($customer_record !== null) { ?>


            <!-- =================================================
                 CUSTOMER INFORMATION
            ================================================== -->

            <div class="customer-box">


                <div class="customer-box-title">

                    <i class="fa fa-user-circle me-2"></i>

                    Customer Information

                </div>


                <div class="customer-info-grid">


                    <!-- CUSTOMER ID -->

                    <div class="info-item">

                        <strong>
                            Customer ID:
                        </strong>

                        <?php

                        echo (int)
                            $customer_record[
                                "customer_id"
                            ];

                        ?>

                    </div>


                    <!-- NAME -->

                    <div class="info-item">

                        <strong>
                            Name:
                        </strong>

                        <?php

                        echo e(
                            trim(
                                $customer_record[
                                    "first_name"
                                ] .
                                " " .
                                $customer_record[
                                    "last_name"
                                ]
                            )
                        );

                        ?>

                    </div>


                    <!-- PHONE -->

                    <div class="info-item">

                        <strong>
                            Phone:
                        </strong>

                        <?php

                        echo e(
                            $customer_record[
                                "phone_number"
                            ]
                        );

                        ?>

                    </div>


                    <!-- EMAIL -->

                    <div class="info-item">

                        <strong>
                            Email:
                        </strong>

                        <?php

                        echo e(
                            $customer_record[
                                "email"
                            ]
                        );

                        ?>

                    </div>


                    <!-- ADDRESS -->

                    <div class="info-item full-width">

                        <strong>
                            Address:
                        </strong>

                        <?php

                        echo e(
                            $customer_record[
                                "address"
                            ]
                        );

                        ?>

                    </div>


                    <!-- REGISTRATION DATE -->

                    <div class="info-item full-width">

                        <strong>
                            Registration Date:
                        </strong>

                        <?php

                        echo e(
                            $customer_record[
                                "registration_date"
                            ]
                        );

                        ?>

                    </div>


                </div>


            </div>


            <!-- =================================================
                 PURCHASE DETAILS
            ================================================== -->

            <div class="customer-box">


                <div class="details-title">

                    <i class="fa fa-shopping-cart me-2"></i>

                    Complete Purchase Details

                </div>


                <?php if (count($purchase_rows) > 0) { ?>


                <!-- =================================================
                     DESKTOP TABLE
                ================================================== -->

                <div class="desktop-table-wrapper">


                    <table class="desktop-table">


                        <thead>

                            <tr>

                                <th>Customer</th>

                                <th>Email</th>

                                <th>Phone</th>

                                <th>Order ID</th>

                                <th>Order Date</th>

                                <th>Product</th>

                                <th>Quantity</th>

                                <th>Unit Price</th>

                                <th>Subtotal</th>

                                <th>Order Total</th>

                                <th>Status</th>

                                <th>Payment Amount</th>

                                <th>Payment Method</th>

                                <th>Payment Date</th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php foreach (
                            $purchase_rows as $row
                        ) {


                            $subtotal =
                                (float)(
                                    $row["quantity"] ?? 0
                                )
                                *
                                (float)(
                                    $row["until_price"] ?? 0
                                );


                            $status = strtolower(
                                trim(
                                    $row[
                                        "order_status"
                                    ] ?? ""
                                )
                            );


                            $status_class =
                                "status-default";


                            if (
                                $status === "completed" ||
                                $status === "complete"
                            ) {

                                $status_class =
                                    "status-completed";

                            }
                            elseif (
                                $status === "pending"
                            ) {

                                $status_class =
                                    "status-pending";

                            }
                            elseif (
                                $status === "cancelled" ||
                                $status === "canceled"
                            ) {

                                $status_class =
                                    "status-cancelled";

                            }


                            $customer_name =
                                trim(
                                    ($row[
                                        "first_name"
                                    ] ?? "") .
                                    " " .
                                    ($row[
                                        "last_name"
                                    ] ?? "")
                                );

                        ?>


                            <tr>


                                <!-- CUSTOMER -->

                                <td>

                                    <span
                                        class="customer-name"
                                    >

                                        <?php

                                        echo e(
                                            $customer_name
                                        );

                                        ?>

                                    </span>

                                </td>


                                <!-- EMAIL -->

                                <td>

                                    <span
                                        class="customer-email"
                                    >

                                        <?php

                                        if (
                                            isset(
                                                $row[
                                                    "customer_email"
                                                ]
                                            ) &&
                                            trim(
                                                $row[
                                                    "customer_email"
                                                ]
                                            ) !== ""
                                        ) {

                                            echo e(
                                                $row[
                                                    "customer_email"
                                                ]
                                            );

                                        }
                                        else {

                                            echo "-";

                                        }

                                        ?>

                                    </span>

                                </td>


                                <!-- PHONE -->

                                <td>

                                    <span
                                        class="customer-phone"
                                    >

                                        <?php

                                        echo e(
                                            $row[
                                                "customer_phone"
                                            ]
                                        );

                                        ?>

                                    </span>

                                </td>


                                <!-- ORDER ID -->

                                <td>

                                    #

                                    <?php

                                    echo (int)
                                        $row[
                                            "order_id"
                                        ];

                                    ?>

                                </td>


                                <!-- ORDER DATE -->

                                <td>

                                    <?php

                                    echo e(
                                        $row[
                                            "order_date"
                                        ]
                                    );

                                    ?>

                                </td>


                                <!-- PRODUCT -->

                                <td>

                                    <span
                                        class="product-name"
                                    >

                                        <?php

                                        echo e(
                                            $row[
                                                "product_name"
                                            ]
                                        );

                                        ?>

                                    </span>

                                </td>


                                <!-- QUANTITY -->

                                <td>

                                    <?php

                                    echo (int)
                                        $row[
                                            "quantity"
                                        ];

                                    ?>

                                </td>


                                <!-- UNIT PRICE -->

                                <td>

                                    Rs.

                                    <?php

                                    echo number_format(
                                        (float)
                                        $row[
                                            "until_price"
                                        ],
                                        2
                                    );

                                    ?>

                                </td>


                                <!-- SUBTOTAL -->

                                <td>

                                    Rs.

                                    <?php

                                    echo number_format(
                                        $subtotal,
                                        2
                                    );

                                    ?>

                                </td>


                                <!-- ORDER TOTAL -->

                                <td>

                                    Rs.

                                    <?php

                                    echo number_format(
                                        (float)
                                        $row[
                                            "total_amount"
                                        ],
                                        2
                                    );

                                    ?>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span
                                        class="order-badge
                                        <?php
                                            echo e(
                                                $status_class
                                            );
                                        ?>"
                                    >

                                        <?php

                                        echo e(
                                            $row[
                                                "order_status"
                                            ]
                                        );

                                        ?>

                                    </span>

                                </td>


                                <!-- PAYMENT AMOUNT -->

                                <td>

                                    <?php

                                    if (
                                        $row[
                                            "payment_amount"
                                        ] !== null
                                    ) {

                                        echo "Rs. " .
                                            number_format(
                                                (float)
                                                $row[
                                                    "payment_amount"
                                                ],
                                                2
                                            );

                                    }
                                    else {

                                        echo "-";

                                    }

                                    ?>

                                </td>


                                <!-- PAYMENT METHOD -->

                                <td>

                                    <?php

                                    if (
                                        $row[
                                            "payement_method"
                                        ] !== null &&
                                        $row[
                                            "payement_method"
                                        ] !== ""
                                    ) {

                                        echo e(
                                            $row[
                                                "payement_method"
                                            ]
                                        );

                                    }
                                    else {

                                        echo "-";

                                    }

                                    ?>

                                </td>


                                <!-- PAYMENT DATE -->

                                <td>

                                    <?php

                                    if (
                                        $row[
                                            "payement_date"
                                        ] !== null &&
                                        $row[
                                            "payement_date"
                                        ] !== ""
                                    ) {

                                        echo e(
                                            $row[
                                                "payement_date"
                                            ]
                                        );

                                    }
                                    else {

                                        echo "-";

                                    }

                                    ?>

                                </td>


                            </tr>


                        <?php } ?>


                        </tbody>


                    </table>


                </div>


                <!-- =================================================
                     MOBILE CARDS
                ================================================== -->

                <div class="mobile-purchase-list">


                <?php foreach (
                    $purchase_rows as $row
                ) {


                    $subtotal =
                        (float)(
                            $row["quantity"] ?? 0
                        )
                        *
                        (float)(
                            $row["until_price"] ?? 0
                        );


                    $status = strtolower(
                        trim(
                            $row[
                                "order_status"
                            ] ?? ""
                        )
                    );


                    $status_class =
                        "status-default";


                    if (
                        $status === "completed" ||
                        $status === "complete"
                    ) {

                        $status_class =
                            "status-completed";

                    }
                    elseif (
                        $status === "pending"
                    ) {

                        $status_class =
                            "status-pending";

                    }
                    elseif (
                        $status === "cancelled" ||
                        $status === "canceled"
                    ) {

                        $status_class =
                            "status-cancelled";

                    }


                    $customer_name =
                        trim(
                            ($row[
                                "first_name"
                            ] ?? "") .
                            " " .
                            ($row[
                                "last_name"
                            ] ?? "")
                        );

                ?>


                    <div class="purchase-card">


                        <!-- CARD HEADER -->

                        <div
                            class="purchase-card-header"
                        >

                            <div
                                class="purchase-card-title"
                            >

                                <i
                                    class="fa fa-shopping-bag me-1"
                                ></i>

                                <?php

                                echo e(
                                    $row[
                                        "product_name"
                                    ]
                                );

                                ?>

                            </div>


                            <div
                                class="purchase-card-order"
                            >

                                Order #

                                <?php

                                echo (int)
                                    $row[
                                        "order_id"
                                    ];

                                ?>

                            </div>


                        </div>


                        <!-- CUSTOMER -->

                        <div
                            class="purchase-card-row"
                        >

                            <div
                                class="purchase-card-label"
                            >
                                Customer
                            </div>

                            <div
                                class="
                                purchase-card-value
                                customer-name
                                "
                            >

                                <?php

                                echo e(
                                    $customer_name
                                );

                                ?>

                            </div>

                        </div>


                        <!-- EMAIL -->

                        <div
                            class="purchase-card-row"
                        >

                            <div
                                class="purchase-card-label"
                            >
                                Email
                            </div>

                            <div
                                class="
                                purchase-card-value
                                customer-email
                                "
                            >

                                <?php

                                if (
                                    isset(
                                        $row[
                                            "customer_email"
                                        ]
                                    ) &&
                                    trim(
                                        $row[
                                            "customer_email"
                                        ]
                                    ) !== ""
                                ) {

                                    echo e(
                                        $row[
                                            "customer_email"
                                        ]
                                    );

                                }
                                else {

                                    echo "-";

                                }

                                ?>

                            </div>

                        </div>


                        <!-- PHONE -->

                        <div
                            class="purchase-card-row"
                        >

                            <div
                                class="purchase-card-label"
                            >
                                Phone
                            </div>

                            <div
                                class="
                                purchase-card-value
                                customer-phone
                                "
                            >

                                <?php

                                echo e(
                                    $row[
                                        "customer_phone"
                                    ]
                                );

                                ?>

                            </div>

                        </div>


                        <!-- ORDER DATE -->

                        <div
                            class="purchase-card-row"
                        >

                            <div
                                class="purchase-card-label"
                            >
                                Order Date
                            </div>

                            <div
                                class="purchase-card-value"
                            >

                                <?php

                                echo e(
                                    $row[
                                        "order_date"
                                    ]
                                );

                                ?>

                            </div>

                        </div>


                        <!-- PRODUCT -->

                        <div
                            class="purchase-card-row"
                        >

                            <div
                                class="purchase-card-label"
                            >
                                Product
                            </div>

                            <div
                                class="
                                purchase-card-value
                                product-name
                                "
                            >

                                <?php

                                echo e(
                                    $row[
                                        "product_name"
                                    ]
                                );

                                ?>

                            </div>

                        </div>


                        <!-- QUANTITY -->

                        <div
                            class="purchase-card-row"
                        >

                            <div
                                class="purchase-card-label"
                            >
                                Quantity
                            </div>

                            <div
                                class="purchase-card-value"
                            >

                                <?php

                                echo (int)
                                    $row[
                                        "quantity"
                                    ];

                                ?>

                            </div>

                        </div>


                        <!-- UNIT PRICE -->

                        <div
                            class="purchase-card-row"
                        >

                            <div
                                class="purchase-card-label"
                            >
                                Unit Price
                            </div>

                            <div
                                class="purchase-card-value"
                            >

                                Rs.

                                <?php

                                echo number_format(
                                    (float)
                                    $row[
                                        "until_price"
                                    ],
                                    2
                                );

                                ?>

                            </div>

                        </div>


                        <!-- SUBTOTAL -->

                        <div
                            class="purchase-card-row"
                        >

                            <div
                                class="purchase-card-label"
                            >
                                Subtotal
                            </div>

                            <div
                                class="purchase-card-value"
                            >

                                Rs.

                                <?php

                                echo number_format(
                                    $subtotal,
                                    2
                                );

                                ?>

                            </div>

                        </div>


                        <!-- ORDER TOTAL -->

                        <div
                            class="purchase-card-row"
                        >

                            <div
                                class="purchase-card-label"
                            >
                                Order Total
                            </div>

                            <div
                                class="purchase-card-value"
                            >

                                Rs.

                                <?php

                                echo number_format(
                                    (float)
                                    $row[
                                        "total_amount"
                                    ],
                                    2
                                );

                                ?>

                            </div>

                        </div>


                        <!-- STATUS -->

                        <div
                            class="purchase-card-row"
                        >

                            <div
                                class="purchase-card-label"
                            >
                                Status
                            </div>

                            <div
                                class="purchase-card-value"
                            >

                                <span
                                    class="
                                    order-badge
                                    <?php
                                        echo e(
                                            $status_class
                                        );
                                    ?>"
                                >

                                    <?php

                                    echo e(
                                        $row[
                                            "order_status"
                                        ]
                                    );

                                    ?>

                                </span>

                            </div>

                        </div>


                        <!-- PAYMENT AMOUNT -->

                        <div
                            class="purchase-card-row"
                        >

                            <div
                                class="purchase-card-label"
                            >
                                Payment Amount
                            </div>

                            <div
                                class="purchase-card-value"
                            >

                                <?php

                                if (
                                    $row[
                                        "payment_amount"
                                    ] !== null
                                ) {

                                    echo "Rs. " .
                                        number_format(
                                            (float)
                                            $row[
                                                "payment_amount"
                                            ],
                                            2
                                        );

                                }
                                else {

                                    echo "-";

                                }

                                ?>

                            </div>

                        </div>


                        <!-- PAYMENT METHOD -->

                        <div
                            class="purchase-card-row"
                        >

                            <div
                                class="purchase-card-label"
                            >
                                Payment Method
                            </div>

                            <div
                                class="purchase-card-value"
                            >

                                <?php

                                if (
                                    $row[
                                        "payement_method"
                                    ] !== null &&
                                    $row[
                                        "payement_method"
                                    ] !== ""
                                ) {

                                    echo e(
                                        $row[
                                            "payement_method"
                                        ]
                                    );

                                }
                                else {

                                    echo "-";

                                }

                                ?>

                            </div>

                        </div>


                        <!-- PAYMENT DATE -->

                        <div
                            class="purchase-card-row"
                        >

                            <div
                                class="purchase-card-label"
                            >
                                Payment Date
                            </div>

                            <div
                                class="purchase-card-value"
                            >

                                <?php

                                if (
                                    $row[
                                        "payement_date"
                                    ] !== null &&
                                    $row[
                                        "payement_date"
                                    ] !== ""
                                ) {

                                    echo e(
                                        $row[
                                            "payement_date"
                                        ]
                                    );

                                }
                                else {

                                    echo "-";

                                }

                                ?>

                            </div>

                        </div>


                    </div>


                <?php } ?>


                </div>


                <?php } else { ?>


                <!-- =================================================
                     NO PURCHASE
                ================================================== -->

                <div class="empty-box">

                    <i
                        class="
                        fa
                        fa-shopping-basket
                        fa-2x
                        mb-3
                        "
                    ></i>

                    <br>

                    Is phone number ke customer ne
                    abhi koi product purchase nahi kiya.

                </div>


                <?php } ?>


            </div>


            <?php
            }
            elseif ($selected_phone !== "")
            {
            ?>


            <!-- =================================================
                 CUSTOMER NOT FOUND
            ================================================== -->

            <div class="customer-box">

                <div class="empty-box">

                    <i
                        class="
                        fa
                        fa-phone
                        fa-2x
                        mb-3
                        "
                    ></i>

                    <br>

                    Is phone number ka customer record
                    nahi mila.

                </div>

            </div>


            <?php
            }
            else
            {
            ?>


            <!-- =================================================
                 INITIAL MESSAGE
            ================================================== -->

            <div class="customer-box">

                <div class="empty-box">

                    <i
                        class="
                        fa
                        fa-phone
                        fa-2x
                        mb-3
                        "
                    ></i>

                    <br>

                    Pehle phone number type karein aur
                    Search button press karein.

                </div>

            </div>


            <?php } ?>


        </div>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <div
            class="
            container-fluid
            pt-4
            px-4
            "
        >

            <div
                class="
                bg-secondary
                rounded-top
                p-4
                "
            >

                <div class="row">


                    <div
                        class="
                        col-12
                        col-sm-6
                        text-center
                        text-sm-start
                        "
                    >

                        &copy;

                        <a href="#">
                            Your Site Name
                        </a>

                        , All Right Reserved.

                    </div>


                    <div
                        class="
                        col-12
                        col-sm-6
                        text-center
                        text-sm-end
                        "
                    >

                        Designed By

                        <a
                            href="https://htmlcodex.com"
                            target="_blank"
                        >
                            HTML Codex
                        </a>

                        <br>

                        Distributed By:

                        <a
                            href="https://themewagon.com"
                            target="_blank"
                        >
                            ThemeWagon
                        </a>

                    </div>


                </div>

            </div>

        </div>


    </div>


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
     DARKPAN LIBRARIES
====================================================== -->

<script
    src="lib/chart/chart.min.js"
></script>

<script
    src="lib/easing/easing.min.js"
></script>

<script
    src="lib/waypoints/waypoints.min.js"
></script>

<script
    src="lib/owlcarousel/owl.carousel.min.js"
></script>

<script
    src="lib/tempusdominus/js/moment.min.js"
></script>

<script
    src="lib/tempusdominus/js/moment-timezone.min.js"
></script>

<script
    src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"
></script>


<!-- =====================================================
     DARKPAN MAIN JS
====================================================== -->

<script
    src="js/main.js"
></script>


<!-- =====================================================
     EXACT WORKING SIDEBAR JAVASCRIPT
====================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        /* =================================================
           TOGGLERS
        ================================================= */

        const togglers =
            document.querySelectorAll(
                ".sidebar-toggler, .navbar-toggler"
            );


        /* =================================================
           SIDEBAR
        ================================================= */

        const sidebar =
            document.querySelector(
                ".main-page-container .sidebar"
            );


        /* =================================================
           OVERLAY
        ================================================= */

        const overlay =
            document.getElementById(
                "sidebarOverlay"
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
           TOGGLE SIDEBAR
        ================================================= */

        function toggleSidebar(event) {

            if (event) {

                event.preventDefault();

                event.stopPropagation();

            }


            /* =============================================
               MOBILE / TABLET
            ============================================= */

            if (window.innerWidth <= 991) {

                document.body.classList.toggle(
                    "sidebar-open"
                );


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
           TOGGLER CLICK
        ================================================= */

        togglers.forEach(
            function (toggler) {

                toggler.addEventListener(
                    "click",
                    toggleSidebar,
                    true
                );

            }
        );


        /* =================================================
           SIDEBAR LINKS
        ================================================= */

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


                        /* =================================
                           IGNORE EMPTY LINKS
                        ================================= */

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


                        /* =================================
                           IGNORE DROPDOWN LINKS
                        ================================= */

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


                        /* =================================
                           CLOSE MOBILE SIDEBAR
                        ================================= */

                        document.body.classList.remove(
                            "sidebar-open"
                        );


                        /* =================================
                           MOBILE NAVIGATION
                        ================================= */

                        if (
                            window.innerWidth <= 991
                        ) {

                            event.preventDefault();

                            event.stopPropagation();


                            window.location.assign(
                                link.href
                            );

                        }

                    },
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
                function () {

                    document.body.classList.remove(
                        "sidebar-open"
                    );

                }
            );

        }


        /* =================================================
           OUTSIDE CLICK
        ================================================= */

        document.addEventListener(
            "click",
            function (event) {

                if (
                    window.innerWidth > 991
                ) {

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
                    sidebar.contains(
                        event.target
                    );


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


        /* =================================================
           RESIZE
        ================================================= */

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


<?php

// =====================================================
// CLOSE DETAILS STATEMENT
// =====================================================

if ($details_stmt) {

    mysqli_stmt_close(
        $details_stmt
    );

}

?>


</body>

</html>