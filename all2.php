<?php

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
// SELECTED PHONE NUMBER
// =====================================================

$selected_phone = isset($_GET["phone_number"])
    ? trim($_GET["phone_number"])
    : "";


// =====================================================
// CUSTOMER ID
// =====================================================

$customer_id = 0;


// =====================================================
// CUSTOMER LIST
// =====================================================

$customer_sql = "
    SELECT
        customer_id,
        first_name,
        last_name,
        email,
        phone_number
    FROM entity_asad_customer
    WHERE phone_number IS NOT NULL
    AND phone_number != ''
    ORDER BY phone_number ASC
";

$customer_result = mysqli_query(
    $conn,
    $customer_sql
);

if (!$customer_result) {

    die(
        "Customer Query Error: " .
        mysqli_error($conn)
    );
}


// =====================================================
// FIND CUSTOMER BY PHONE
// =====================================================

$customer_record = null;

if ($selected_phone !== "") {

    $customer_sql_single = "
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
        $customer_sql_single
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

    $customer_result_single =
        mysqli_stmt_get_result(
            $customer_stmt
        );

    if (
        $customer_result_single &&
        mysqli_num_rows($customer_result_single) > 0
    ) {

        $customer_record =
            mysqli_fetch_assoc(
                $customer_result_single
            );

        $customer_id =
            (int)$customer_record["customer_id"];
    }

    mysqli_stmt_close($customer_stmt);
}


// =====================================================
// PURCHASE DETAILS
// =====================================================

$details_result = null;
$details_stmt = null;

if (
    $customer_id > 0 &&
    $customer_record !== null
) {

    $details_sql = "

        SELECT

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

            o.order_id,
            o.order_date,
            o.total_amount,
            o.status AS order_status,

            oi.order_item_id,
            oi.quantity,
            oi.until_price,

            p.id AS product_id,
            p.name AS product_name,
            p.discraption AS product_description,

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
}


// =====================================================
// SAVE PURCHASE ROWS
// =====================================================

$purchase_rows = [];

if ($details_result) {

    while (
        $temp_row =
        mysqli_fetch_assoc($details_result)
    ) {

        $purchase_rows[] = $temp_row;
    }
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

    <title>Customer Purchase Details</title>


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
BACK TO TOP
========================================================= */

.back-to-top {

    display: none !important;

    visibility: hidden !important;

    opacity: 0 !important;
}


/* =========================================================
CUSTOMER CONTAINER
========================================================= */

.customer-container {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    margin: 0 !important;

    padding: 18px !important;

    overflow: hidden !important;

    background: #000 !important;
}


/* =========================================================
FILTER BOX
========================================================= */

.filter-box {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    background: #191c24 !important;

    border-radius: 8px !important;

    padding: 18px !important;

    margin-bottom: 15px !important;

    overflow: hidden !important;
}


.filter-title {

    display: flex;

    align-items: center;

    color: #fff;

    font-size: 17px;

    font-weight: 600;

    margin-bottom: 12px;
}


.filter-title i {

    font-size: 18px;

    flex-shrink: 0;
}


/* =========================================================
PHONE SELECT
========================================================= */

.customer-select {

    display: block !important;

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    height: 45px !important;

    background: #fff !important;

    color: #111 !important;

    border: none !important;

    border-radius: 8px !important;

    padding: 8px 14px !important;

    font-size: 15px !important;

    cursor: pointer !important;
}


.customer-select:focus {

    outline: none !important;

    box-shadow:
        0 0 0 3px
        rgba(13,110,253,0.25) !important;
}


/* =========================================================
CUSTOMER INFORMATION
========================================================= */

.customer-info {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    background: #191c24 !important;

    border-radius: 8px !important;

    padding: 18px !important;

    margin-bottom: 15px !important;

    overflow: hidden !important;
}


.customer-info-title {

    display: flex;

    align-items: center;

    color: #fff;

    font-size: 17px;

    font-weight: 600;

    margin-bottom: 15px;
}


.customer-info-title i {

    font-size: 18px;

    flex-shrink: 0;
}


.customer-info .row {

    width: 100% !important;

    max-width: 100% !important;

    margin-left: 0 !important;

    margin-right: 0 !important;
}


.customer-info .row > [class*="col-"] {

    padding-left: 6px !important;

    padding-right: 6px !important;
}


/* =========================================================
INFO ITEM
========================================================= */

.info-item {

    width: 100% !important;

    max-width: 100% !important;

    color: #8f9bc0;

    font-size: 13px;

    margin-bottom: 10px;

    line-height: 1.5;

    overflow-wrap: anywhere;

    word-break: break-word;
}


.info-item strong {

    color: #fff;
}


/* =========================================================
DETAILS BOX
========================================================= */

.details-box {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    background: #191c24 !important;

    border-radius: 8px !important;

    padding: 18px !important;

    margin-bottom: 20px !important;

    overflow: hidden !important;
}


.details-title {

    display: flex;

    align-items: center;

    color: #fff;

    font-size: 17px;

    font-weight: 600;

    margin-bottom: 15px;
}


.details-title i {

    font-size: 20px;

    flex-shrink: 0;
}


/* =========================================================
DESKTOP TABLE WRAPPER
========================================================= */

.desktop-table-wrapper {

    display: block;

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    overflow-x: auto !important;

    overflow-y: hidden !important;

    -webkit-overflow-scrolling: touch;

    border-radius: 6px;
}


/* =========================================================
DESKTOP TABLE
========================================================= */

.desktop-table {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 1200px !important;

    margin: 0 !important;

    border-collapse: collapse !important;

    border-spacing: 0;

    table-layout: fixed !important;
}


/* =========================================================
14 COLUMN WIDTHS
========================================================= */

.desktop-table th:nth-child(1),
.desktop-table td:nth-child(1) {
    width: 9%;
}

.desktop-table th:nth-child(2),
.desktop-table td:nth-child(2) {
    width: 9%;
}

.desktop-table th:nth-child(3),
.desktop-table td:nth-child(3) {
    width: 7%;
}

.desktop-table th:nth-child(4),
.desktop-table td:nth-child(4) {
    width: 6%;
}

.desktop-table th:nth-child(5),
.desktop-table td:nth-child(5) {
    width: 8%;
}

.desktop-table th:nth-child(6),
.desktop-table td:nth-child(6) {
    width: 7%;
}

.desktop-table th:nth-child(7),
.desktop-table td:nth-child(7) {
    width: 6%;
}

.desktop-table th:nth-child(8),
.desktop-table td:nth-child(8) {
    width: 7%;
}

.desktop-table th:nth-child(9),
.desktop-table td:nth-child(9) {
    width: 7%;
}

.desktop-table th:nth-child(10),
.desktop-table td:nth-child(10) {
    width: 7%;
}

.desktop-table th:nth-child(11),
.desktop-table td:nth-child(11) {
    width: 7%;
}

.desktop-table th:nth-child(12),
.desktop-table td:nth-child(12) {
    width: 7%;
}

.desktop-table th:nth-child(13),
.desktop-table td:nth-child(13) {
    width: 7%;
}

.desktop-table th:nth-child(14),
.desktop-table td:nth-child(14) {
    width: 6%;
}


/* =========================================================
TABLE HEADER
========================================================= */

.desktop-table thead th {

    background: #08090d !important;

    color: #8f9bc0 !important;

    font-size: 10px;

    font-weight: 600;

    padding: 11px 7px !important;

    white-space: normal !important;

    overflow-wrap: anywhere;

    word-break: break-word;

    line-height: 1.35;

    vertical-align: middle;

    border-bottom:
        1px solid
        rgba(255,255,255,0.12) !important;
}


/* =========================================================
TABLE BODY
========================================================= */

.desktop-table tbody td {

    background: #08090d !important;

    color: #8f9bc0 !important;

    font-size: 10px;

    padding: 11px 7px !important;

    white-space: normal !important;

    overflow-wrap: anywhere;

    word-break: break-word;

    line-height: 1.4;

    vertical-align: middle;

    border-bottom:
        1px solid
        rgba(255,255,255,0.06) !important;
}


.desktop-table tbody tr:hover td {

    background:
        rgba(255,255,255,0.035) !important;
}


/* =========================================================
CUSTOMER NAME
========================================================= */

.customer-name {

    color: #fff !important;

    font-weight: 600;

    font-size: 11px;

    overflow-wrap: anywhere;

    word-break: break-word;
}


/* =========================================================
PHONE
========================================================= */

.customer-phone {

    color: #20c997 !important;

    font-size: 10px;

    font-weight: 600;

    white-space: normal !important;

    overflow-wrap: anywhere;

    word-break: break-word;
}


/* =========================================================
EMAIL
========================================================= */

.customer-email {

    color: #8f9bc0 !important;

    font-size: 10px;

    overflow-wrap: anywhere;

    word-break: break-all;

    white-space: normal !important;
}


/* =========================================================
PRODUCT
========================================================= */

.product-name {

    color: #fff !important;

    font-weight: 600;

    font-size: 11px;

    overflow-wrap: anywhere;

    word-break: break-word;
}


/* =========================================================
STATUS BADGE
========================================================= */

.order-badge {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    max-width: 100%;

    padding: 4px 7px;

    border-radius: 20px;

    font-size: 9px;

    font-weight: 600;

    white-space: normal;

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


/* =========================================================
MOBILE PURCHASE LIST
========================================================= */

.mobile-purchase-list {

    display: none;

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;
}


/* =========================================================
PURCHASE CARD
========================================================= */

.purchase-card {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    background: #08090d !important;

    border: 1px solid
        rgba(255,255,255,0.10) !important;

    border-radius: 8px !important;

    padding: 13px !important;

    margin-bottom: 12px !important;

    overflow: hidden !important;
}


/* =========================================================
CARD HEADER
========================================================= */

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

    color: #fff;

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


/* =========================================================
CARD ROW
========================================================= */

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


/* =========================================================
CARD LABEL
========================================================= */

.purchase-card-label {

    flex: 0 0 40%;

    max-width: 40%;

    min-width: 0;

    color: #fff;

    font-size: 10px;

    font-weight: 600;

    line-height: 1.4;

    overflow-wrap: anywhere;

    word-break: break-word;
}


/* =========================================================
CARD VALUE
========================================================= */

.purchase-card-value {

    flex: 1 1 60%;

    min-width: 0;

    max-width: 60%;

    color: #8f9bc0;

    font-size: 10px;

    text-align: right;

    line-height: 1.4;

    overflow-wrap: anywhere;

    word-break: break-word;
}


.purchase-card-value.customer-name,
.purchase-card-value.product-name {

    color: #fff !important;

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


.purchase-card-value .order-badge {

    font-size: 9px;

    padding: 4px 7px;
}


/* =========================================================
EMPTY BOX
========================================================= */

.empty-box {

    width: 100% !important;

    max-width: 100% !important;

    text-align: center;

    color: #8f9bc0;

    padding: 35px 15px;

    font-size: 14px;

    overflow-wrap: anywhere;
}


/* =========================================================
MOBILE SIDEBAR OVERLAY
========================================================= */

.sidebar-mobile-overlay {

    display: none;

    position: fixed;

    inset: 0;

    width: 100vw;

    height: 100vh;

    background: rgba(0,0,0,0.55);

    z-index: 99998;
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


    body.sidebar-open
    .main-container
    .sidebar {

        left: 0 !important;
    }


    body.sidebar-open
    .sidebar-mobile-overlay {

        display: block !important;
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


    .customer-container {

        width: 100% !important;

        max-width: 100% !important;

        padding: 15px !important;
    }


    .desktop-table-wrapper {

        display: none !important;
    }


    .mobile-purchase-list {

        display: block !important;
    }


    .filter-box,
    .customer-info,
    .details-box {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;
    }
}


/* =========================================================
TABLET 768 - 991
========================================================= */

@media (min-width: 768px) and (max-width: 991px) {

    .customer-container {

        padding: 15px !important;
    }


    .filter-box,
    .customer-info,
    .details-box {

        padding: 16px !important;
    }


    .purchase-card {

        padding: 15px !important;
    }


    .purchase-card-label {

        flex-basis: 35%;

        max-width: 35%;

        font-size: 11px;
    }


    .purchase-card-value {

        flex-basis: 65%;

        max-width: 65%;

        font-size: 11px;
    }


    .purchase-card-title {

        font-size: 14px;
    }


    .purchase-card-order {

        font-size: 12px;
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


    body.sidebar-open
    .main-container
    .sidebar {

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


    .customer-container {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        padding: 8px !important;

        margin: 0 !important;

        overflow: hidden !important;
    }


    .filter-box,
    .customer-info,
    .details-box {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        padding: 12px !important;

        margin-bottom: 10px !important;

        border-radius: 8px !important;

        overflow: hidden !important;
    }


    .filter-title,
    .customer-info-title,
    .details-title {

        font-size: 15px !important;

        margin-bottom: 12px !important;
    }


    .customer-select {

        width: 100% !important;

        max-width: 100% !important;

        height: 43px !important;

        font-size: 13px !important;
    }


    .customer-info .row {

        margin-left: 0 !important;

        margin-right: 0 !important;
    }


    .customer-info .row > [class*="col-"] {

        padding-left: 3px !important;

        padding-right: 3px !important;
    }


    .info-item {

        font-size: 12px !important;

        margin-bottom: 8px !important;
    }


    .mobile-purchase-list {

        display: block !important;

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;
    }


    .purchase-card {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        padding: 12px !important;

        margin-bottom: 12px !important;

        border-radius: 8px !important;
    }


    .purchase-card-header {

        gap: 8px;

        padding-bottom: 10px;
    }


    .purchase-card-title {

        font-size: 13px;
    }


    .purchase-card-order {

        font-size: 11px;
    }


    .purchase-card-row {

        gap: 8px;

        padding: 8px 0;
    }


    .purchase-card-label {

        flex: 0 0 42%;

        max-width: 42%;

        font-size: 10px;
    }


    .purchase-card-value {

        flex: 1 1 58%;

        max-width: 58%;

        font-size: 10px;
    }
}


/* =========================================================
SMALL MOBILE
========================================================= */

@media (max-width: 575px) {

    .customer-container {

        padding: 6px !important;
    }


    .filter-box,
    .customer-info,
    .details-box {

        padding: 10px !important;
    }


    .purchase-card {

        padding: 9px !important;

        margin-bottom: 10px !important;
    }


    .purchase-card-title {

        font-size: 12px;
    }


    .purchase-card-order {

        font-size: 10px;
    }


    .purchase-card-row {

        padding: 7px 0;
    }


    .purchase-card-label {

        flex-basis: 40%;

        max-width: 40%;

        font-size: 9px;
    }


    .purchase-card-value {

        flex-basis: 60%;

        max-width: 60%;

        font-size: 9px;
    }


    .purchase-card-value.customer-email {

        font-size: 8px;
    }
}


/* =========================================================
VERY SMALL MOBILE
========================================================= */

@media (max-width: 400px) {

    .customer-container {

        padding: 5px !important;
    }


    .filter-box,
    .customer-info,
    .details-box {

        padding: 8px !important;

        border-radius: 6px !important;
    }


    .purchase-card {

        padding: 8px !important;
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

        font-size: 9px;
    }
}


/* =========================================================
LARGE DESKTOP
========================================================= */

@media (min-width: 1400px) {

    .customer-container {

        padding: 20px !important;
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
MAIN WRAPPER
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
             CUSTOMER PAGE
        ================================================== -->

        <div class="customer-container">


            <!-- =================================================
                 SELECT CUSTOMER
            ================================================== -->



            <?php if ($customer_record !== null) { ?>


            <!-- =================================================
                 CUSTOMER INFORMATION
            ================================================== -->

            <div class="customer-info">

                <div class="customer-info-title">

                    <i class="fa fa-user-circle me-2"></i>

                    Customer Information

                </div>


                <div class="row">


                    <!-- CUSTOMER ID -->

                    <div class="col-lg-3 col-md-6 col-12">

                        <div class="info-item">

                            <strong>Customer ID:</strong>

                            <?php
                            echo (int)
                                $customer_record["customer_id"];
                            ?>

                        </div>

                    </div>


                    <!-- NAME -->

                    <div class="col-lg-3 col-md-6 col-12">

                        <div class="info-item">

                            <strong>Name:</strong>

                            <?php

                            echo e(
                                trim(
                                    $customer_record["first_name"] .
                                    " " .
                                    $customer_record["last_name"]
                                )
                            );

                            ?>

                        </div>

                    </div>


                    <!-- PHONE -->

                    <div class="col-lg-3 col-md-6 col-12">

                        <div class="info-item">

                            <strong>Phone:</strong>

                            <?php

                            echo e(
                                $customer_record["phone_number"]
                            );

                            ?>

                        </div>

                    </div>


                    <!-- EMAIL -->

                    <div class="col-lg-3 col-md-6 col-12">

                        <div class="info-item">

                            <strong>Email:</strong>

                            <?php

                            echo e(
                                $customer_record["email"]
                            );

                            ?>

                        </div>

                    </div>


                    <!-- ADDRESS -->

                    <div class="col-lg-6 col-12">

                        <div class="info-item">

                            <strong>Address:</strong>

                            <?php

                            echo e(
                                $customer_record["address"]
                            );

                            ?>

                        </div>

                    </div>


                    <!-- REGISTRATION DATE -->

                    <div class="col-lg-6 col-12">

                        <div class="info-item">

                            <strong>Registration Date:</strong>

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

            </div>


            <!-- =================================================
                 PURCHASE DETAILS
            ================================================== -->

            <div class="details-box">


                <div class="details-title">

                    <i class="fa fa-shopping-cart me-2"></i>

                    Complete Purchase Details

                </div>


                <?php

                if (count($purchase_rows) > 0) {

                ?>


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

                        <?php

                        foreach ($purchase_rows as $row) {

                            // =================================================
                            // SUBTOTAL
                            // =================================================

                            $subtotal =
                                (float)($row["quantity"] ?? 0)
                                *
                                (float)($row["until_price"] ?? 0);


                            // =================================================
                            // STATUS
                            // =================================================

                            $status = strtolower(
                                trim(
                                    $row["order_status"] ?? ""
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


                            // =================================================
                            // CUSTOMER NAME
                            // =================================================

                            $customer_name =
                                trim(
                                    ($row["first_name"] ?? "") .
                                    " " .
                                    ($row["last_name"] ?? "")
                                );

                        ?>

                            <tr>


                                <!-- CUSTOMER -->

                                <td>

                                    <span class="customer-name">

                                        <?php
                                        echo e(
                                            $customer_name
                                        );
                                        ?>

                                    </span>

                                </td>


                                <!-- EMAIL -->

                                <td>

                                    <span class="customer-email">

                                        <?php

                                        if (
                                            isset(
                                                $row["customer_email"]
                                            ) &&
                                            trim(
                                                $row["customer_email"]
                                            ) !== ""
                                        ) {

                                            echo e(
                                                $row["customer_email"]
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

                                    <span class="customer-phone">

                                        <?php

                                        echo e(
                                            $row["customer_phone"]
                                        );

                                        ?>

                                    </span>

                                </td>


                                <!-- ORDER ID -->

                                <td>

                                    #

                                    <?php

                                    echo (int)
                                        $row["order_id"];

                                    ?>

                                </td>


                                <!-- ORDER DATE -->

                                <td>

                                    <?php

                                    echo e(
                                        $row["order_date"]
                                    );

                                    ?>

                                </td>


                                <!-- PRODUCT -->

                                <td>

                                    <span class="product-name">

                                        <?php

                                        echo e(
                                            $row["product_name"]
                                        );

                                        ?>

                                    </span>

                                </td>


                                <!-- QUANTITY -->

                                <td>

                                    <?php

                                    echo (int)
                                        $row["quantity"];

                                    ?>

                                </td>


                                <!-- UNIT PRICE -->

                                <td>

                                    Rs.

                                    <?php

                                    echo number_format(
                                        (float)
                                        $row["until_price"],
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
                                        $row["total_amount"],
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
                                            $row["order_status"]
                                        );

                                        ?>

                                    </span>

                                </td>


                                <!-- PAYMENT AMOUNT -->

                                <td>

                                    <?php

                                    if (
                                        $row["payment_amount"]
                                        !== null
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
                                        $row["payement_method"]
                                        !== null &&
                                        $row["payement_method"]
                                        !== ""
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
                                        $row["payement_date"]
                                        !== null &&
                                        $row["payement_date"]
                                        !== ""
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


                        <?php

                        }

                        ?>

                        </tbody>

                    </table>

                </div>


                <!-- =================================================
                     MOBILE CARDS
                ================================================== -->

                <div class="mobile-purchase-list">

                <?php

                foreach ($purchase_rows as $row) {

                    // =================================================
                    // SUBTOTAL
                    // =================================================

                    $subtotal =
                        (float)($row["quantity"] ?? 0)
                        *
                        (float)($row["until_price"] ?? 0);


                    // =================================================
                    // STATUS
                    // =================================================

                    $status = strtolower(
                        trim(
                            $row["order_status"] ?? ""
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
                            ($row["first_name"] ?? "") .
                            " " .
                            ($row["last_name"] ?? "")
                        );

                ?>


                    <div class="purchase-card">


                        <!-- CARD HEADER -->

                        <div class="purchase-card-header">

                            <div class="purchase-card-title">

                                <i
                                    class="fa fa-shopping-bag me-1"
                                ></i>

                                <?php

                                echo e(
                                    $row["product_name"]
                                );

                                ?>

                            </div>


                            <div class="purchase-card-order">

                                Order #

                                <?php

                                echo (int)
                                    $row["order_id"];

                                ?>

                            </div>

                        </div>


                        <!-- CUSTOMER -->

                        <div class="purchase-card-row">

                            <div class="purchase-card-label">
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

                        <div class="purchase-card-row">

                            <div class="purchase-card-label">
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
                                        $row["customer_email"]
                                    ) &&
                                    trim(
                                        $row["customer_email"]
                                    ) !== ""
                                ) {

                                    echo e(
                                        $row["customer_email"]
                                    );

                                }
                                else {

                                    echo "-";
                                }

                                ?>

                            </div>

                        </div>


                        <!-- PHONE -->

                        <div class="purchase-card-row">

                            <div class="purchase-card-label">
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
                                    $row["customer_phone"]
                                );

                                ?>

                            </div>

                        </div>


                        <!-- ORDER DATE -->

                        <div class="purchase-card-row">

                            <div class="purchase-card-label">
                                Order Date
                            </div>

                            <div class="purchase-card-value">

                                <?php

                                echo e(
                                    $row["order_date"]
                                );

                                ?>

                            </div>

                        </div>


                        <!-- PRODUCT -->

                        <div class="purchase-card-row">

                            <div class="purchase-card-label">
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
                                    $row["product_name"]
                                );

                                ?>

                            </div>

                        </div>


                        <!-- QUANTITY -->

                        <div class="purchase-card-row">

                            <div class="purchase-card-label">
                                Quantity
                            </div>

                            <div class="purchase-card-value">

                                <?php

                                echo (int)
                                    $row["quantity"];

                                ?>

                            </div>

                        </div>


                        <!-- UNIT PRICE -->

                        <div class="purchase-card-row">

                            <div class="purchase-card-label">
                                Unit Price
                            </div>

                            <div class="purchase-card-value">

                                Rs.

                                <?php

                                echo number_format(
                                    (float)
                                    $row["until_price"],
                                    2
                                );

                                ?>

                            </div>

                        </div>


                        <!-- SUBTOTAL -->

                        <div class="purchase-card-row">

                            <div class="purchase-card-label">
                                Subtotal
                            </div>

                            <div class="purchase-card-value">

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

                        <div class="purchase-card-row">

                            <div class="purchase-card-label">
                                Order Total
                            </div>

                            <div class="purchase-card-value">

                                Rs.

                                <?php

                                echo number_format(
                                    (float)
                                    $row["total_amount"],
                                    2
                                );

                                ?>

                            </div>

                        </div>


                        <!-- STATUS -->

                        <div class="purchase-card-row">

                            <div class="purchase-card-label">
                                Status
                            </div>

                            <div class="purchase-card-value">

                                <span
                                    class="
                                        order-badge
                                        <?php
                                        echo e(
                                            $status_class
                                        );
                                        ?>
                                    "
                                >

                                    <?php

                                    echo e(
                                        $row["order_status"]
                                    );

                                    ?>

                                </span>

                            </div>

                        </div>


                        <!-- PAYMENT AMOUNT -->

                        <div class="purchase-card-row">

                            <div class="purchase-card-label">
                                Payment Amount
                            </div>

                            <div class="purchase-card-value">

                                <?php

                                if (
                                    $row["payment_amount"]
                                    !== null
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

                        <div class="purchase-card-row">

                            <div class="purchase-card-label">
                                Payment Method
                            </div>

                            <div class="purchase-card-value">

                                <?php

                                if (
                                    $row["payement_method"]
                                    !== null &&
                                    $row["payement_method"]
                                    !== ""
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

                        <div class="purchase-card-row">

                            <div class="purchase-card-label">
                                Payment Date
                            </div>

                            <div class="purchase-card-value">

                                <?php

                                if (
                                    $row["payement_date"]
                                    !== null &&
                                    $row["payement_date"]
                                    !== ""
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


                <?php

                }

                ?>

                </div>


                <?php

                }
                else {

                ?>


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


                <?php

                }

                ?>


            </div>


            <?php

            }
            elseif ($selected_phone !== "") {

            ?>


                <!-- =================================================
                     CUSTOMER NOT FOUND
                ================================================== -->

                <div class="details-box">

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

                        Is phone number ka customer
                        record nahi mila.

                    </div>

                </div>


            <?php

            }
            else {

            ?>


                <!-- =================================================
                     NO PHONE SELECTED
                ================================================== -->

                <div class="details-box">

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

                        Pehle customer ka phone number select karein.

                    </div>

                </div>


            <?php

            }

            ?>


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


        /*
        =====================================================
        FIND ALL SIDEBAR TOGGLERS
        =====================================================
        */

        const togglers =
            document.querySelectorAll(
                ".sidebar-toggler, .navbar-toggler"
            );


        /*
        =====================================================
        FIND SIDEBAR
        =====================================================
        */

        const sidebar =
            document.querySelector(
                ".main-container .sidebar"
            );


        /*
        =====================================================
        FIND OVERLAY
        =====================================================
        */

        const overlay =
            document.getElementById(
                "sidebarOverlay"
            );


        /*
        =====================================================
        SAFETY CHECK
        =====================================================
        */

        if (!sidebar) {

            console.log(
                "Sidebar not found."
            );

            return;
        }


        /*
        =====================================================
        SIDEBAR TOGGLE
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
        ADD TOGGLE EVENT
        =====================================================
        */

        togglers.forEach(
            function (toggler) {

                toggler.addEventListener(
                    "click",
                    toggleSidebar,
                    true
                );

            }
        );


        /*
        =====================================================
        SIDEBAR LINKS
        =====================================================
        */

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


                        /*
                        IGNORE EMPTY LINKS
                        */

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


                        /*
                        IGNORE DROPDOWN BUTTONS
                        */

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


                        /*
                        CLOSE SIDEBAR
                        */

                        document.body.classList.remove(
                            "sidebar-open"
                        );


                        /*
                        MOBILE / TABLET
                        */

                        if (
                            window.innerWidth <= 991
                        ) {

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

            }
        );


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


        /*
        =====================================================
        RESIZE FIX
        =====================================================
        */

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