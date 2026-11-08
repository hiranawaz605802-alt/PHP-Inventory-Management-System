
<?php

// =====================================================
// DATABASE
// =====================================================

include_once("Database.php");

$db = new Database();
$conn = $db->connection();


// =====================================================
// DATABASE CHECK
// =====================================================

if (!$conn) {

    die("Database connection failed.");

}


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
// DELETE PRODUCT
// =====================================================

if (isset($_GET["delete_id"])) {

    $delete_id = (int) $_GET["delete_id"];


    if ($delete_id > 0) {

        $delete_sql = "
            DELETE FROM entity_asad_product
            WHERE id = ?
        ";

        $delete_stmt = mysqli_prepare(
            $conn,
            $delete_sql
        );


        if ($delete_stmt) {

            mysqli_stmt_bind_param(
                $delete_stmt,
                "i",
                $delete_id
            );

            mysqli_stmt_execute(
                $delete_stmt
            );

            mysqli_stmt_close(
                $delete_stmt
            );

        }

    }


    header(
        "Location: product_view.php"
    );

    exit;

}


// =====================================================
// FETCH PRODUCTS
// =====================================================

$product_sql = "
    SELECT
        id,
        name,
        price,
        discraption,
        stock_quantity,
        category_id
    FROM entity_asad_product
    ORDER BY id DESC
";


$product_result = mysqli_query(
    $conn,
    $product_sql
);


if (!$product_result) {

    die(
        "Product Fetch Error: " .
        mysqli_error($conn)
    );

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <title>DarkPan - Product View</title>

    <meta
        content="width=device-width, initial-scale=1.0"
        name="viewport"
    >

    <meta content="" name="keywords">

    <meta content="" name="description">


    <!-- =====================================================
         FAVICON
    ====================================================== -->

    <link
        href="img/favicon.ico"
        rel="icon"
    >


    <!-- =====================================================
         GOOGLE WEB FONTS
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
         DARKPAN LIBRARIES
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

}


*,
*::before,
*::after {

    box-sizing: border-box;

}


body {

    background: #000 !important;

}


/* =========================================================
   MAIN WRAPPER
========================================================= */

.product-main-wrapper {

    width: 100% !important;

    max-width: 100% !important;

    min-height: 100vh !important;

    margin: 0 !important;

    padding: 0 !important;

    position: relative !important;

    background: #000 !important;

    overflow-x: hidden !important;

}


/* =========================================================
   SIDEBAR
========================================================= */

.product-main-wrapper .sidebar {

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
   CONTENT AREA
========================================================= */

.product-content-area {

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

.product-content-area > .navbar {

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
   DESKTOP COLLAPSE
========================================================= */

@media (min-width: 992px) {

    body.sidebar-collapsed
    .product-main-wrapper .sidebar {

        left: -250px !important;

    }


    body.sidebar-collapsed
    .product-content-area {

        width: 100% !important;

        max-width: 100% !important;

        margin-left: 0 !important;

    }

}


/* =========================================================
   MOBILE OVERLAY
========================================================= */

.sidebar-mobile-overlay {

    display: none;

    position: fixed;

    inset: 0;

    width: 100vw;

    height: 100vh;

    background: rgba(0, 0, 0, 0.55);

    z-index: 99998;

}


/* =========================================================
   PRODUCT PAGE
========================================================= */

.product-page {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    min-height: 100vh !important;

    margin: 0 !important;

    padding: 20px !important;

    position: relative !important;

    background: #000 !important;

    overflow: hidden !important;

}


/* =========================================================
   PAGE HEADER
========================================================= */

.product-page-header {

    width: 100% !important;

    max-width: 1200px !important;

    margin: 0 auto 20px auto !important;

    display: flex !important;

    align-items: center !important;

    justify-content: space-between !important;

    gap: 15px !important;

}


.product-page-title {

    color: #fff !important;

    font-size: 20px !important;

    font-weight: 600 !important;

    margin: 0 !important;

}


.product-page-title i {

    margin-right: 8px !important;

}


/* =========================================================
   ADD BUTTON
========================================================= */

.add-product-btn {

    min-height: 42px !important;

    padding: 8px 16px !important;

    white-space: nowrap !important;

}


/* =========================================================
   TABLE BOX
========================================================= */

.product-table-box {

    width: 100% !important;

    max-width: 1200px !important;

    margin: 0 auto !important;

    padding: 20px !important;

    background: #191c24 !important;

    border-radius: 8px !important;

    overflow: hidden !important;

}


/* =========================================================
   DESKTOP TABLE WRAPPER
========================================================= */

.product-table-wrapper {

    width: 100% !important;

    overflow-x: auto !important;

}


/* =========================================================
   PRODUCT TABLE
========================================================= */

.product-table {

    width: 100% !important;

    min-width: 900px !important;

    margin: 0 !important;

    color: #fff !important;

    vertical-align: middle !important;

}


.product-table thead th {

    background: #11141a !important;

    color: #fff !important;

    font-weight: 600 !important;

    padding: 13px 12px !important;

    white-space: nowrap !important;

    border: 0 !important;

}


.product-table tbody td {

    background: #191c24 !important;

    color: #fff !important;

    padding: 13px 12px !important;

    vertical-align: middle !important;

    border-color: #30333b !important;

}


.product-table tbody tr:hover td {

    background: #222631 !important;

}


/* =========================================================
   DESCRIPTION
========================================================= */

.product-description {

    max-width: 250px !important;

    white-space: normal !important;

    word-break: break-word !important;

}


/* =========================================================
   PRICE
========================================================= */

.product-price {

    white-space: nowrap !important;

    font-weight: 600 !important;

}


/* =========================================================
   STOCK
========================================================= */

.stock-badge {

    display: inline-block !important;

    min-width: 45px !important;

    text-align: center !important;

    padding: 5px 8px !important;

    border-radius: 5px !important;

    background: #0d6efd !important;

    color: #fff !important;

}


/* =========================================================
   ACTION BUTTONS
========================================================= */

.product-actions {

    display: flex !important;

    align-items: center !important;

    gap: 6px !important;

    white-space: nowrap !important;

}


.product-actions .btn {

    min-width: 38px !important;

    min-height: 36px !important;

}


/* =========================================================
   EMPTY DATA
========================================================= */

.no-product {

    text-align: center !important;

    color: #aaa !important;

    padding: 30px !important;

}


/* =========================================================
   MOBILE CARDS
========================================================= */

.product-mobile-list {

    display: none !important;

}


.product-card {

    width: 100% !important;

    background: #191c24 !important;

    border-radius: 8px !important;

    padding: 15px !important;

    margin-bottom: 12px !important;

    border: 1px solid #30333b !important;

}


.product-card-row {

    display: flex !important;

    justify-content: space-between !important;

    align-items: flex-start !important;

    gap: 12px !important;

    padding: 7px 0 !important;

    border-bottom: 1px solid #30333b !important;

}


.product-card-row:last-child {

    border-bottom: 0 !important;

}


.product-card-label {

    color: #aaa !important;

    font-size: 13px !important;

    font-weight: 600 !important;

    min-width: 110px !important;

}


.product-card-value {

    color: #fff !important;

    font-size: 14px !important;

    text-align: right !important;

    word-break: break-word !important;

}


.product-card-actions {

    display: flex !important;

    gap: 8px !important;

    margin-top: 12px !important;

}


.product-card-actions .btn {

    flex: 1 !important;

}


/* =========================================================
   FOOTER
========================================================= */

.product-footer {

    width: 100% !important;

    max-width: 100% !important;

    padding: 15px 20px !important;

    margin: 0 !important;

    overflow: hidden !important;

}


.product-footer .bg-secondary {

    width: 100% !important;

    max-width: 100% !important;

    border-radius: 8px !important;

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
   TABLET
========================================================= */

@media (max-width: 991px) {

    /* SIDEBAR */

    .product-main-wrapper .sidebar {

        width: 250px !important;

        min-width: 250px !important;

        height: 100vh !important;

        position: fixed !important;

        top: 0 !important;

        left: -250px !important;

        bottom: 0 !important;

        margin: 0 !important;

        z-index: 99999 !important;

        transition: left 0.3s ease !important;

    }


    body.sidebar-open
    .product-main-wrapper .sidebar {

        left: 0 !important;

    }


    body.sidebar-open
    .sidebar-mobile-overlay {

        display: block !important;

    }


    /* CONTENT */

    .product-content-area {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        margin-left: 0 !important;

        padding: 0 !important;

    }


    /* NAVBAR */

    .product-content-area > .navbar {

        width: 100% !important;

        max-width: 100% !important;

        min-height: 70px !important;

        padding-left: 15px !important;

        padding-right: 15px !important;

    }


    /* PAGE */

    .product-page {

        width: 100% !important;

        max-width: 100% !important;

        padding: 15px !important;

    }


    /* HEADER */

    .product-page-header {

        width: 100% !important;

        margin-bottom: 15px !important;

    }


    /* TABLE */

    .product-table-box {

        width: 100% !important;

        padding: 15px !important;

    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767px) {

    .product-main-wrapper .sidebar {

        width: 250px !important;

        min-width: 250px !important;

        left: -250px !important;

    }


    body.sidebar-open
    .product-main-wrapper .sidebar {

        left: 0 !important;

    }


    .product-content-area {

        width: 100% !important;

        max-width: 100% !important;

        margin-left: 0 !important;

    }


    .product-content-area > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        margin: 0 !important;

        padding-left: 10px !important;

        padding-right: 10px !important;

    }


    .product-page {

        width: 100% !important;

        max-width: 100% !important;

        padding: 10px !important;

    }


    /* HEADER */

    .product-page-header {

        width: 100% !important;

        display: flex !important;

        flex-direction: column !important;

        align-items: stretch !important;

        gap: 10px !important;

    }


    .product-page-title {

        font-size: 17px !important;

    }


    .add-product-btn {

        width: 100% !important;

    }


    /* TABLE BOX */

    .product-table-box {

        width: 100% !important;

        padding: 10px !important;

        margin: 0 !important;

    }


    /* HIDE DESKTOP TABLE */

    .product-table-wrapper {

        display: none !important;

    }


    /* SHOW MOBILE CARDS */

    .product-mobile-list {

        display: block !important;

    }


    /* FOOTER */

    .product-footer {

        width: 100% !important;

        padding: 10px 8px !important;

    }


    .product-footer .bg-secondary {

        width: 100% !important;

        padding: 12px !important;

    }


    .product-footer .row > div {

        text-align: center !important;

        margin-bottom: 4px;

    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 400px) {

    .product-page {

        padding: 5px !important;

    }


    .product-table-box {

        padding: 7px !important;

    }


    .product-card {

        padding: 12px !important;

    }


    .product-card-label {

        min-width: 90px !important;

        font-size: 12px !important;

    }


    .product-card-value {

        font-size: 13px !important;

    }


    .product-page-title {

        font-size: 16px !important;

    }

}

</style>

</head>


<body>


<!-- =====================================================
     MAIN WRAPPER
====================================================== -->

<div class="product-main-wrapper">


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
         CONTENT AREA
    ================================================== -->

    <div class="product-content-area">


        <!-- =================================================
             NAVBAR
        ================================================== -->

        <?php include("navbar.php"); ?>


        <!-- =================================================
             PRODUCT PAGE
        ================================================== -->

        <div class="product-page">


            <!-- =================================================
                 PAGE HEADER
            ================================================== -->

            <div class="product-page-header">


                <h5 class="product-page-title">

                    <i class="fa fa-box"></i>

                    Product View

                </h5>


                <a
                    href="product_add.php"
                    class="btn btn-primary add-product-btn"
                >

                    <i class="fa fa-plus me-1"></i>

                    Add Product

                </a>


            </div>


            <!-- =================================================
                 TABLE BOX
            ================================================== -->

            <div class="product-table-box">


                <!-- =================================================
                     DESKTOP TABLE
                ================================================== -->

                <div class="product-table-wrapper">

                    <table
                        class="table product-table"
                    >

                        <thead>

                            <tr>

                                <th>
                                    ID
                                </th>

                                <th>
                                    Name
                                </th>

                                <th>
                                    Price
                                </th>

                                <th>
                                    Description
                                </th>

                                <th>
                                    Stock Quantity
                                </th>

                                <th>
                                    Category ID
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php

                        if (
                            mysqli_num_rows(
                                $product_result
                            ) > 0
                        ) {


                            while (
                                $product =
                                mysqli_fetch_assoc(
                                    $product_result
                                )
                            ) {

                        ?>


                            <tr>


                                <!-- ID -->

                                <td>

                                    <?= e(
                                        $product["id"]
                                    ); ?>

                                </td>


                                <!-- NAME -->

                                <td>

                                    <?= e(
                                        $product["name"]
                                    ); ?>

                                </td>


                                <!-- PRICE -->

                                <td class="product-price">

                                    <?= number_format(
                                        (float)$product["price"],
                                        2
                                    ); ?>

                                </td>


                                <!-- DESCRIPTION -->

                                <td class="product-description">

                                    <?= e(
                                        $product["discraption"]
                                    ); ?>

                                </td>


                                <!-- STOCK -->

                                <td>

                                    <span
                                        class="stock-badge"
                                    >

                                        <?= e(
                                            $product["stock_quantity"]
                                        ); ?>

                                    </span>

                                </td>


                                <!-- CATEGORY -->

                                <td>

                                    <?= e(
                                        $product["category_id"]
                                    ); ?>

                                </td>


                                <!-- ACTION -->

                                <td>

                                    <div
                                        class="product-actions"
                                    >


                                        <!-- EDIT -->

                                        <a
                                            href="product_edit.php?id=<?= (int)$product["id"]; ?>"
                                            class="btn btn-sm btn-primary"
                                            title="Edit"
                                        >

                                            <i class="fa fa-edit"></i>

                                        </a>


                                        <!-- DELETE -->

                                        <a
                                            href="product_view.php?delete_id=<?= (int)$product["id"]; ?>"
                                            class="btn btn-sm btn-danger"
                                            title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this product?');"
                                        >

                                            <i class="fa fa-trash"></i>

                                        </a>


                                    </div>

                                </td>


                            </tr>


                        <?php

                            }

                        }
                        else {

                        ?>

                            <tr>

                                <td
                                    colspan="7"
                                    class="no-product"
                                >

                                    No Product Found.

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

                <div class="product-mobile-list">


                <?php

                // Re-fetch products for mobile cards

                mysqli_data_seek(
                    $product_result,
                    0
                );


                if (
                    mysqli_num_rows(
                        $product_result
                    ) > 0
                ) {


                    while (
                        $product =
                        mysqli_fetch_assoc(
                            $product_result
                        )
                    ) {

                ?>


                    <div class="product-card">


                        <!-- ID -->

                        <div class="product-card-row">

                            <div class="product-card-label">
                                ID
                            </div>

                            <div class="product-card-value">

                                <?= e(
                                    $product["id"]
                                ); ?>

                            </div>

                        </div>


                        <!-- NAME -->

                        <div class="product-card-row">

                            <div class="product-card-label">
                                Name
                            </div>

                            <div class="product-card-value">

                                <?= e(
                                    $product["name"]
                                ); ?>

                            </div>

                        </div>


                        <!-- PRICE -->

                        <div class="product-card-row">

                            <div class="product-card-label">
                                Price
                            </div>

                            <div class="product-card-value">

                                <?= number_format(
                                    (float)$product["price"],
                                    2
                                ); ?>

                            </div>

                        </div>


                        <!-- DESCRIPTION -->

                        <div class="product-card-row">

                            <div class="product-card-label">
                                Description
                            </div>

                            <div class="product-card-value">

                                <?= e(
                                    $product["discraption"]
                                ); ?>

                            </div>

                        </div>


                        <!-- STOCK -->

                        <div class="product-card-row">

                            <div class="product-card-label">
                                Stock Quantity
                            </div>

                            <div class="product-card-value">

                                <?= e(
                                    $product["stock_quantity"]
                                ); ?>

                            </div>

                        </div>


                        <!-- CATEGORY -->

                        <div class="product-card-row">

                            <div class="product-card-label">
                                Category ID
                            </div>

                            <div class="product-card-value">

                                <?= e(
                                    $product["category_id"]
                                ); ?>

                            </div>

                        </div>


                        <!-- ACTIONS -->

                        <div class="product-card-actions">


                            <!-- EDIT -->

                            <a
                                href="product_edit.php?id=<?= (int)$product["id"]; ?>"
                                class="btn btn-primary"
                            >

                                <i class="fa fa-edit me-1"></i>

                                Edit

                            </a>


                            <!-- DELETE -->

                            <a
                                href="product_view.php?delete_id=<?= (int)$product["id"]; ?>"
                                class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this product?');"
                            >

                                <i class="fa fa-trash me-1"></i>

                                Delete

                            </a>


                        </div>


                    </div>


                <?php

                    }

                }
                else {

                ?>

                    <div class="no-product">

                        No Product Found.

                    </div>

                <?php

                }

                ?>


                </div>


            </div>


        </div>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <div class="product-footer">

            <div class="bg-secondary rounded-top p-4">

                <div class="row">


                    <!-- LEFT -->

                    <div
                        class="col-12 col-sm-6
                               text-center text-sm-start"
                    >

                        &copy;

                        <a href="#">
                            Your Site Name
                        </a>

                        , All Right Reserved.

                    </div>


                    <!-- RIGHT -->

                    <div
                        class="col-12 col-sm-6
                               text-center text-sm-end"
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

<script src="lib/chart/chart.min.js"></script>

<script src="lib/easing/easing.min.js"></script>

<script src="lib/waypoints/waypoints.min.js"></script>

<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<script src="lib/tempusdominus/js/moment.min.js"></script>

<script src="lib/tempusdominus/js/moment-timezone.min.js"></script>

<script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>


<!-- =====================================================
     DARKPAN MAIN JS
====================================================== -->

<script src="js/main.js"></script>


<!-- =====================================================
     FIXED SIDEBAR JS
====================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        // =================================================
        // SIDEBAR
        // =================================================

        const sidebar =
            document.querySelector(
                ".product-main-wrapper .sidebar"
            );


        // =================================================
        // TOGGLE BUTTONS
        // =================================================

        const togglers =
            document.querySelectorAll(
                ".sidebar-toggler, .navbar-toggler, .sidebar-toggle, [data-sidebar-toggle]"
            );


        // =================================================
        // OVERLAY
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
        // OPEN MOBILE SIDEBAR
        // =================================================

        function openMobileSidebar() {

            document.body.classList.add(
                "sidebar-open"
            );

            document.body.classList.remove(
                "sidebar-collapsed"
            );

        }


        // =================================================
        // CLOSE MOBILE SIDEBAR
        // =================================================

        function closeMobileSidebar() {

            document.body.classList.remove(
                "sidebar-open"
            );

        }


        // =================================================
        // DESKTOP SIDEBAR
        // =================================================

        function toggleDesktopSidebar() {

            document.body.classList.toggle(
                "sidebar-collapsed"
            );

            document.body.classList.remove(
                "sidebar-open"
            );

        }


        // =================================================
        // TOGGLE BUTTON
        // =================================================

        togglers.forEach(
            function (toggler) {

                toggler.addEventListener(
                    "click",
                    function (event) {

                        event.preventDefault();

                        event.stopPropagation();


                        if (
                            window.innerWidth <= 991
                        ) {


                            if (
                                document.body.classList.contains(
                                    "sidebar-open"
                                )
                            ) {

                                closeMobileSidebar();

                            }
                            else {

                                openMobileSidebar();

                            }


                        }
                        else {

                            toggleDesktopSidebar();

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
                function () {

                    closeMobileSidebar();

                }
            );

        }


        // =================================================
        // SIDEBAR LINKS
        // =================================================

        const sidebarLinks =
            sidebar.querySelectorAll("a");


        sidebarLinks.forEach(
            function (link) {

                link.addEventListener(
                    "click",
                    function () {

                        const href =
                            link.getAttribute(
                                "href"
                            );


                        if (
                            !href ||
                            href === "#" ||
                            href === "javascript:void(0)" ||
                            href === "javascript:void(0);"
                        ) {

                            return;

                        }


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


                        if (
                            window.innerWidth <= 991
                        ) {

                            closeMobileSidebar();

                        }

                    },
                    true
                );

            }
        );


        // =================================================
        // OUTSIDE CLICK
        // =================================================

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

                    closeMobileSidebar();

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
                    event.key === "Escape"
                ) {

                    closeMobileSidebar();

                }

            }
        );


        // =================================================
        // RESIZE
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
