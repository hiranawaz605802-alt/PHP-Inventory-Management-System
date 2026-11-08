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
// MESSAGE
// =====================================================

$message = "";
$message_type = "";


// =====================================================
// DELETE CART ITEM
// =====================================================

if (isset($_GET["delete_id"])) {

    $delete_id = (int) $_GET["delete_id"];

    if ($delete_id > 0) {

        $delete_sql = "
            DELETE FROM entity_asad_cart_items
            WHERE cart_item_id = $delete_id
        ";

        if (mysqli_query($conn, $delete_sql)) {

            header("Location: " . $current_page);
            exit();

        } else {

            $message = "Delete Error: " . mysqli_error($conn);
            $message_type = "danger";
        }
    }
}


// =====================================================
// UPDATE CART ITEM
// =====================================================

if (isset($_POST["update_cart_item"])) {

    $cart_item_id = isset($_POST["cart_item_id"])
        ? (int) $_POST["cart_item_id"]
        : 0;

    $cart_id = isset($_POST["cart_id"])
        ? (int) $_POST["cart_id"]
        : 0;

    $quantity = isset($_POST["quantity"])
        ? (int) $_POST["quantity"]
        : 0;

    $product_id = isset($_POST["product_id"])
        ? (int) $_POST["product_id"]
        : 0;

    $unit_price = isset($_POST["unit_price"])
        ? (float) $_POST["unit_price"]
        : 0;


    // =================================================
    // VALIDATION
    // =================================================

    if ($cart_item_id <= 0) {

        $message = "Invalid Cart Item ID.";
        $message_type = "danger";

    } elseif ($cart_id <= 0) {

        $message = "Please enter Cart ID.";
        $message_type = "danger";

    } elseif ($quantity <= 0) {

        $message = "Please enter valid Quantity.";
        $message_type = "danger";

    } elseif ($product_id <= 0) {

        $message = "Please enter Product ID.";
        $message_type = "danger";

    } elseif ($unit_price <= 0) {

        $message = "Please enter valid Unit Price.";
        $message_type = "danger";

    } else {

        $update_sql = "
            UPDATE entity_asad_cart_items
            SET
                cart_id = $cart_id,
                quantity = $quantity,
                product_id = $product_id,
                unit_price = $unit_price
            WHERE cart_item_id = $cart_item_id
        ";

        if (mysqli_query($conn, $update_sql)) {

            header("Location: " . $current_page);
            exit();

        } else {

            $message = "Update Error: " . mysqli_error($conn);
            $message_type = "danger";
        }
    }
}


// =====================================================
// EDIT RECORD
// =====================================================

$edit_record = null;

$edit_id = 0;

if (isset($_GET["edit_id"])) {

    $edit_id = (int) $_GET["edit_id"];

} elseif (isset($_POST["cart_item_id"])) {

    $edit_id = (int) $_POST["cart_item_id"];
}


if ($edit_id > 0) {

    $edit_sql = "
        SELECT
            cart_item_id,
            cart_id,
            quantity,
            product_id,
            unit_price
        FROM entity_asad_cart_items
        WHERE cart_item_id = $edit_id
        LIMIT 1
    ";

    $edit_result = mysqli_query($conn, $edit_sql);

    if (!$edit_result) {

        die("Edit Query Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($edit_result) > 0) {

        $edit_record = mysqli_fetch_assoc($edit_result);
    }
}


// =====================================================
// FETCH CART ITEMS
// =====================================================

$sql = "
    SELECT
        cart_item_id,
        cart_id,
        quantity,
        product_id,
        unit_price
    FROM entity_asad_cart_items
    ORDER BY cart_item_id DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {

    die("Cart Item View Error: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="utf-8">

<title>Cart Items</title>

<meta
    content="width=device-width, initial-scale=1.0"
    name="viewport"
>

<meta content="" name="keywords">
<meta content="" name="description">


<!-- =====================================================
     FAVICON
===================================================== -->

<link href="img/favicon.ico" rel="icon">


<!-- =====================================================
     GOOGLE WEB FONTS
===================================================== -->

<link rel="preconnect" href="https://fonts.googleapis.com">

<link
    rel="preconnect"
    href="https://fonts.googleapis.com"
    crossorigin
>

<link
    href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Nunito:wght@600;700;800&display=swap"
    rel="stylesheet"
>


<!-- =====================================================
     ICON FONT
===================================================== -->

<link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
    rel="stylesheet"
>

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
     DARKPAN STYLE
===================================================== -->

<link
    href="css/style.css"
    rel="stylesheet"
>


<style>

/* =====================================================
   CONTENT
===================================================== */

.content {

    width: calc(100% - 250px) !important;

    max-width: calc(100% - 250px) !important;

    min-height: 100vh;

    margin-left: 250px !important;

    padding: 0 !important;

    min-width: 0;

    overflow-x: hidden !important;

    transition:
        width .3s ease,
        margin-left .3s ease;
}


/* =====================================================
   DESKTOP SIDEBAR COLLAPSED
===================================================== */

body.sidebar-collapsed .content {

    width: 100% !important;

    max-width: 100% !important;

    margin-left: 0 !important;
}


/* =====================================================
   DESKTOP SIDEBAR
===================================================== */

.sidebar {

    width: 250px !important;

    min-width: 250px !important;

    position: fixed !important;

    top: 0 !important;

    left: 0 !important;

    height: 100vh !important;

    z-index: 1100 !important;

    transition:
        left .3s ease,
        margin-left .3s ease !important;

    overflow-y: auto;

    overflow-x: hidden;
}


/* =====================================================
   DESKTOP SIDEBAR COLLAPSED
===================================================== */

body.sidebar-collapsed .sidebar {

    left: -250px !important;

    margin-left: 0 !important;
}


/* =====================================================
   SIDEBAR OVERLAY
===================================================== */

.sidebar-mobile-overlay {

    position: fixed;

    top: 0;

    left: 0;

    width: 100%;

    height: 100%;

    background: rgba(0, 0, 0, .55);

    z-index: 1090;

    display: none;

    cursor: pointer;
}


body.sidebar-open .sidebar-mobile-overlay {

    display: block;
}


/* =====================================================
   OLD OVERLAY SUPPORT
===================================================== */

.sidebar-overlay {

    position: fixed;

    top: 0;

    left: 0;

    width: 100%;

    height: 100%;

    background: rgba(0, 0, 0, .55);

    z-index: 1090;

    display: none;

    cursor: pointer;
}


body.sidebar-open .sidebar-overlay {

    display: block;
}


/* =====================================================
   MAIN CART AREA
===================================================== */

.cart-page {

    width: 100%;
}


.cart-box {

    width: 100%;

    background: #191c24;

    border-radius: 6px;

    overflow: hidden;
}


.cart-title {

    font-size: 22px;

    font-weight: 700;

    color: #ffffff;

    margin: 0;
}


/* =====================================================
   TABLE WRAPPER
===================================================== */

.cart-table-wrapper {

    width: 100%;

    overflow-x: auto;
}


/* =====================================================
   DESKTOP TABLE
===================================================== */

.cart-table {

    width: 100%;

    min-width: 850px;

    margin: 0;

    border-collapse: collapse;
}


.cart-table thead th {

    background: #08090d;

    color: #ffffff;

    font-size: 14px;

    font-weight: 600;

    padding: 14px 12px;

    border-bottom: 1px solid #2d3038;

    white-space: nowrap;
}


.cart-table tbody td {

    background: #191c24;

    color: #ffffff;

    font-size: 14px;

    padding: 13px 12px;

    border-bottom: 1px solid #2d3038;

    vertical-align: middle;
}


.cart-table tbody tr:hover td {

    background: #20232c;
}


/* =====================================================
   ACTION BUTTONS
===================================================== */

.action-buttons {

    display: flex;

    align-items: center;

    gap: 7px;

    white-space: nowrap;
}


.btn-cart-edit,
.btn-cart-delete {

    border: none;

    width: 34px;

    height: 34px;

    border-radius: 4px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    color: #ffffff;

    text-decoration: none;

    transition: .2s;
}


.btn-cart-edit {

    background: #0d6efd;
}


.btn-cart-delete {

    background: #dc3545;
}


.btn-cart-edit:hover,
.btn-cart-delete:hover {

    color: #ffffff;

    transform: translateY(-1px);
}


/* =====================================================
   MOBILE CARDS
===================================================== */

.cart-mobile-list {

    display: none;
}


.cart-mobile-card {

    background: #191c24;

    border: 1px solid #2d3038;

    border-radius: 6px;

    padding: 16px;

    margin-bottom: 14px;
}


.cart-mobile-row {

    display: flex;

    justify-content: space-between;

    align-items: flex-start;

    gap: 15px;

    padding: 10px 0;

    border-bottom: 1px solid #2d3038;
}


.cart-mobile-row:last-child {

    border-bottom: none;

    padding-bottom: 0;
}


.cart-mobile-label {

    color: #9ca3af;

    font-size: 13px;

    font-weight: 600;

    min-width: 110px;
}


.cart-mobile-value {

    color: #ffffff;

    font-size: 14px;

    font-weight: 500;

    text-align: right;

    word-break: break-word;
}


.cart-mobile-actions {

    justify-content: flex-end;
}


/* =====================================================
   EDIT OVERLAY
===================================================== */

.cart-edit-overlay {

    position: fixed;

    inset: 0;

    background: rgba(0, 0, 0, .70);

    z-index: 2000;

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 20px;
}


.cart-edit-modal {

    width: 100%;

    max-width: 550px;

    max-height: 90vh;

    overflow-y: auto;

    background: #191c24;

    border-radius: 7px;

    box-shadow:
        0 10px 35px rgba(0, 0, 0, .5);
}


.cart-edit-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 18px 20px;

    border-bottom: 1px solid #2d3038;
}


.cart-edit-header h5 {

    margin: 0;

    color: #ffffff;

    font-size: 19px;

    font-weight: 700;
}


.cart-edit-close {

    color: #ffffff;

    text-decoration: none;

    font-size: 22px;

    line-height: 1;
}


.cart-edit-body {

    padding: 20px;
}


.cart-edit-label {

    color: #ffffff;

    font-size: 14px;

    font-weight: 600;

    margin-bottom: 7px;
}


.cart-edit-input {

    width: 100%;

    height: 42px;

    border: 1px solid #ced4da;

    background: #ffffff !important;

    color: #212529 !important;

    border-radius: 4px;

    padding: 8px 12px;

    outline: none;
}


.cart-edit-input:focus {

    border-color: #86b7fe;

    box-shadow:
        0 0 0 .15rem rgba(13, 110, 253, .15);
}


.cart-edit-actions {

    display: flex;

    gap: 10px;

    margin-top: 20px;
}


.cart-edit-actions .btn {

    min-height: 40px;
}


/* =====================================================
   EMPTY DATA
===================================================== */

.cart-empty {

    text-align: center;

    color: #adb5bd;

    padding: 35px 15px !important;
}


/* =====================================================
   ALERT
===================================================== */

.cart-alert {

    border-radius: 5px;

    margin-bottom: 20px;
}


/* =====================================================
   BACK TO TOP
===================================================== */

.back-to-top {

    display: none !important;
}


/* =====================================================
   MOBILE / TABLET SIDEBAR
===================================================== */

@media (max-width: 991px) {

    /* -----------------------------------------------
       CONTENT
    ----------------------------------------------- */

    .content {

        width: 100% !important;

        max-width: 100% !important;

        margin-left: 0 !important;

        padding: 0 !important;
    }


    /* -----------------------------------------------
       SIDEBAR
    ----------------------------------------------- */

    .sidebar {

        position: fixed !important;

        top: 0 !important;

        left: -250px !important;

        width: 250px !important;

        min-width: 250px !important;

        height: 100vh !important;

        margin-left: 0 !important;

        z-index: 1100 !important;

        visibility: visible !important;

        opacity: 1 !important;

        transition:
            left .3s ease !important;

        overflow-y: auto !important;

        overflow-x: hidden !important;
    }


    /* -----------------------------------------------
       OPEN SIDEBAR
    ----------------------------------------------- */

    body.sidebar-open .sidebar {

        left: 0 !important;
    }


    /* -----------------------------------------------
       REMOVE DESKTOP COLLAPSE
    ----------------------------------------------- */

    body.sidebar-collapsed .sidebar {

        left: -250px !important;
    }


    body.sidebar-collapsed .content {

        width: 100% !important;

        max-width: 100% !important;

        margin-left: 0 !important;
    }


    /* -----------------------------------------------
       OVERLAY
    ----------------------------------------------- */

    body.sidebar-open .sidebar-mobile-overlay {

        display: block !important;
    }


    body.sidebar-open .sidebar-overlay {

        display: block !important;
    }


    /* -----------------------------------------------
       BODY LOCK
    ----------------------------------------------- */

    body.sidebar-open {

        overflow: hidden !important;
    }

}


/* =====================================================
   TABLE TO MOBILE CARDS
===================================================== */

@media (max-width: 1199px) {

    .cart-table-wrapper {

        display: none;
    }


    .cart-mobile-list {

        display: block;
    }

}


/* =====================================================
   LARGE SCREEN
===================================================== */

@media (min-width: 1200px) {

    .cart-table-wrapper {

        display: block;
    }


    .cart-mobile-list {

        display: none;
    }

}


/* =====================================================
   TABLET / MOBILE
===================================================== */

@media (max-width: 767px) {

    .cart-title {

        font-size: 19px;
    }


    .cart-box {

        border-radius: 5px;
    }


    .cart-mobile-card {

        padding: 13px;
    }


    .cart-mobile-row {

        gap: 10px;
    }


    .cart-mobile-label {

        min-width: 95px;

        font-size: 12px;
    }


    .cart-mobile-value {

        font-size: 13px;
    }


    .cart-edit-overlay {

        padding: 12px;
    }


    .cart-edit-modal {

        max-height: 94vh;
    }


    .cart-edit-header {

        padding: 15px;
    }


    .cart-edit-body {

        padding: 15px;
    }


    .cart-edit-actions {

        flex-direction: column;
    }


    .cart-edit-actions .btn {

        width: 100%;
    }

}


/* =====================================================
   VERY SMALL MOBILE
===================================================== */

@media (max-width: 480px) {

    .cart-mobile-row {

        flex-direction: column;

        gap: 4px;
    }


    .cart-mobile-value {

        text-align: left;
    }


    .cart-mobile-actions {

        flex-direction: row !important;

        align-items: center;
    }


    .action-buttons {

        width: 100%;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     MAIN PAGE CONTAINER
===================================================== -->

<div class="main-page-container">


    <!-- =================================================
         SIDEBAR MOBILE OVERLAY
    ================================================= -->

    <div
        class="sidebar-mobile-overlay"
        id="sidebarMobileOverlay">
    </div>


    <!-- =================================================
         OLD SIDEBAR OVERLAY SUPPORT
    ================================================= -->

    <div
        class="sidebar-overlay"
        id="sidebarOverlay">
    </div>


    <!-- =================================================
         SIDEBAR
    ================================================= -->

    <?php include("sidebar.php"); ?>


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

        <div class="container-fluid pt-4 px-4">

            <div class="cart-page">


                <!-- =================================================
                     MESSAGE
                ================================================= -->

                <?php if ($message != ""): ?>

                    <div class="alert alert-<?php echo e($message_type); ?> cart-alert">

                        <?php echo e($message); ?>

                    </div>

                <?php endif; ?>


                <!-- =================================================
                     CART BOX
                ================================================= -->

                <div class="cart-box">


                    <!-- =================================================
                         TITLE
                    ================================================= -->

                    <div class="d-flex align-items-center justify-content-between p-4">

                        <h5 class="cart-title">
                            Cart Items
                        </h5>

                    </div>


                    <!-- =================================================
                         DESKTOP TABLE
                    ================================================= -->

                    <div class="cart-table-wrapper">

                        <table class="cart-table">

                            <thead>

                                <tr>

                                    <th>
                                        Cart Item ID
                                    </th>

                                    <th>
                                        Cart ID
                                    </th>

                                    <th>
                                        Quantity
                                    </th>

                                    <th>
                                        Product ID
                                    </th>

                                    <th>
                                        Unit Price
                                    </th>

                                    <th>
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php if (mysqli_num_rows($result) > 0): ?>

                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                                        <tr>

                                            <td>
                                                <?php echo e($row["cart_item_id"]); ?>
                                            </td>

                                            <td>
                                                <?php echo e($row["cart_id"]); ?>
                                            </td>

                                            <td>
                                                <?php echo e($row["quantity"]); ?>
                                            </td>

                                            <td>
                                                <?php echo e($row["product_id"]); ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo number_format(
                                                    (float)$row["unit_price"],
                                                    2
                                                );
                                                ?>
                                            </td>

                                            <td>

                                                <div class="action-buttons">

                                                    <a
                                                        href="<?php echo e($current_page); ?>?edit_id=<?php echo (int)$row["cart_item_id"]; ?>"
                                                        class="btn-cart-edit"
                                                        title="Edit"
                                                    >

                                                        <i class="fas fa-edit"></i>

                                                    </a>


                                                    <a
                                                        href="<?php echo e($current_page); ?>?delete_id=<?php echo (int)$row["cart_item_id"]; ?>"
                                                        class="btn-cart-delete"
                                                        title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this cart item?');"
                                                    >

                                                        <i class="fas fa-trash"></i>

                                                    </a>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                <?php else: ?>

                                    <tr>

                                        <td
                                            colspan="6"
                                            class="cart-empty"
                                        >

                                            No Cart Items Found.

                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>


                    <!-- =================================================
                         MOBILE CARDS
                    ================================================= -->

                    <div class="cart-mobile-list p-3">

                        <?php

                        mysqli_data_seek($result, 0);

                        ?>

                        <?php if (mysqli_num_rows($result) > 0): ?>

                            <?php while ($row = mysqli_fetch_assoc($result)): ?>

                                <div class="cart-mobile-card">


                                    <!-- Cart Item ID -->

                                    <div class="cart-mobile-row">

                                        <div class="cart-mobile-label">
                                            Cart Item ID
                                        </div>

                                        <div class="cart-mobile-value">

                                            <?php
                                            echo e(
                                                $row["cart_item_id"]
                                            );
                                            ?>

                                        </div>

                                    </div>


                                    <!-- Cart ID -->

                                    <div class="cart-mobile-row">

                                        <div class="cart-mobile-label">
                                            Cart ID
                                        </div>

                                        <div class="cart-mobile-value">

                                            <?php
                                            echo e(
                                                $row["cart_id"]
                                            );
                                            ?>

                                        </div>

                                    </div>


                                    <!-- Quantity -->

                                    <div class="cart-mobile-row">

                                        <div class="cart-mobile-label">
                                            Quantity
                                        </div>

                                        <div class="cart-mobile-value">

                                            <?php
                                            echo e(
                                                $row["quantity"]
                                            );
                                            ?>

                                        </div>

                                    </div>


                                    <!-- Product ID -->

                                    <div class="cart-mobile-row">

                                        <div class="cart-mobile-label">
                                            Product ID
                                        </div>

                                        <div class="cart-mobile-value">

                                            <?php
                                            echo e(
                                                $row["product_id"]
                                            );
                                            ?>

                                        </div>

                                    </div>


                                    <!-- Unit Price -->

                                    <div class="cart-mobile-row">

                                        <div class="cart-mobile-label">
                                            Unit Price
                                        </div>

                                        <div class="cart-mobile-value">

                                            <?php
                                            echo number_format(
                                                (float)$row["unit_price"],
                                                2
                                            );
                                            ?>

                                        </div>

                                    </div>


                                    <!-- Actions -->

                                    <div class="cart-mobile-row cart-mobile-actions">

                                        <div class="cart-mobile-label">
                                            Action
                                        </div>

                                        <div class="cart-mobile-value">

                                            <div class="action-buttons">

                                                <a
                                                    href="<?php echo e($current_page); ?>?edit_id=<?php echo (int)$row["cart_item_id"]; ?>"
                                                    class="btn-cart-edit"
                                                    title="Edit"
                                                >

                                                    <i class="fas fa-edit"></i>

                                                </a>


                                                <a
                                                    href="<?php echo e($current_page); ?>?delete_id=<?php echo (int)$row["cart_item_id"]; ?>"
                                                    class="btn-cart-delete"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this cart item?');"
                                                >

                                                    <i class="fas fa-trash"></i>

                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <div class="cart-mobile-card">

                                <div class="cart-empty">

                                    No Cart Items Found.

                                </div>

                            </div>

                        <?php endif; ?>

                    </div>


                </div>

            </div>

        </div>


        <!-- =================================================
             FOOTER
        ================================================= -->

        <div class="container-fluid pt-4 px-4">

            <div class="bg-secondary rounded-top p-4">

                <div class="row">

                    <div class="col-12 col-sm-6 text-center text-sm-start">

                        &copy;

                        <a
                            href="#"
                            class="text-decoration-none"
                        >
                            Your Site Name
                        </a>,

                        All Right Reserved.

                    </div>


                    <div class="col-12 col-sm-6 text-center text-sm-end">

                        Designed By

                        <a
                            href="#"
                            class="text-decoration-none"
                        >
                            HTML Codex
                        </a>

                        <br>

                        Distributed By

                        <a
                            href="#"
                            class="text-decoration-none"
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
     EDIT POPUP
===================================================== -->

<?php if ($edit_record !== null): ?>

    <div class="cart-edit-overlay">


        <div class="cart-edit-modal">


            <!-- =================================================
                 EDIT HEADER
            ================================================= -->

            <div class="cart-edit-header">

                <h5>
                    Edit Cart Item
                </h5>


                <a
                    href="<?php echo e($current_page); ?>"
                    class="cart-edit-close"
                    title="Close"
                >

                    &times;

                </a>

            </div>


            <!-- =================================================
                 EDIT BODY
            ================================================= -->

            <div class="cart-edit-body">


                <form
                    method="POST"
                    action="<?php echo e($current_page); ?>"
                >


                    <!-- Cart Item ID -->

                    <div class="mb-3">

                        <label class="cart-edit-label">
                            Cart Item ID
                        </label>

                        <input
                            type="text"
                            name="cart_item_id"
                            class="cart-edit-input"
                            value="<?php echo e($edit_record["cart_item_id"]); ?>"
                            readonly
                        >

                    </div>


                    <!-- Cart ID -->

                    <div class="mb-3">

                        <label class="cart-edit-label">
                            Cart ID
                        </label>

                        <input
                            type="number"
                            name="cart_id"
                            class="cart-edit-input"
                            value="<?php echo e($edit_record["cart_id"]); ?>"
                            min="1"
                            required
                        >

                    </div>


                    <!-- Product ID -->

                    <div class="mb-3">

                        <label class="cart-edit-label">
                            Product ID
                        </label>

                        <input
                            type="number"
                            name="product_id"
                            class="cart-edit-input"
                            value="<?php echo e($edit_record["product_id"]); ?>"
                            min="1"
                            required
                        >

                    </div>


                    <!-- Quantity -->

                    <div class="mb-3">

                        <label class="cart-edit-label">
                            Quantity
                        </label>

                        <input
                            type="number"
                            name="quantity"
                            class="cart-edit-input"
                            value="<?php echo e($edit_record["quantity"]); ?>"
                            min="1"
                            required
                        >

                    </div>


                    <!-- Unit Price -->

                    <div class="mb-3">

                        <label class="cart-edit-label">
                            Unit Price
                        </label>

                        <input
                            type="number"
                            name="unit_price"
                            class="cart-edit-input"
                            value="<?php echo e($edit_record["unit_price"]); ?>"
                            min="0.01"
                            step="0.01"
                            required
                        >

                    </div>


                    <!-- BUTTONS -->

                    <div class="cart-edit-actions">

                        <button
                            type="submit"
                            name="update_cart_item"
                            class="btn btn-primary"
                        >

                            <i class="fas fa-save me-1"></i>

                            Update

                        </button>


                        <a
                            href="<?php echo e($current_page); ?>"
                            class="btn btn-secondary"
                        >

                            <i class="fas fa-times me-1"></i>

                            Cancel

                        </a>

                    </div>


                </form>


            </div>

        </div>

    </div>

<?php endif; ?>


<!-- =====================================================
     JAVASCRIPT LIBRARIES
===================================================== -->

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>


<!-- =====================================================
     DARKPAN LIBRARIES
===================================================== -->

<script src="lib/chart/chart.min.js"></script>

<script src="lib/easing/easing.min.js"></script>

<script src="lib/waypoints/waypoints.min.js"></script>

<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<script src="lib/tempusdominus/js/moment.min.js"></script>

<script src="lib/tempusdominus/js/moment-timezone.min.js"></script>

<script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>


<!-- =====================================================
     DARKPAN MAIN JS
===================================================== -->

<script src="js/main.js"></script>


<!-- =====================================================
     CUSTOMER FIND STYLE SIDEBAR JAVASCRIPT
===================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        // =================================================
        // ELEMENTS
        // =================================================

        const sidebar =
            document.querySelector(".sidebar");


        const mobileOverlay =
            document.getElementById(
                "sidebarMobileOverlay"
            );


        const oldOverlay =
            document.getElementById(
                "sidebarOverlay"
            );


        // =================================================
        // TOGGLE BUTTONS
        //
        // SAME PATTERN AS CUSTOMER FIND
        // =================================================

        const toggleButtons =
            document.querySelectorAll(
                ".sidebar-toggler, .navbar-toggler"
            );


        // =================================================
        // MOBILE CHECK
        // =================================================

        function isMobile() {

            return window.innerWidth <= 991;
        }


        // =================================================
        // OPEN MOBILE SIDEBAR
        // =================================================

        function openMobileSidebar() {

            if (!sidebar) {
                return;
            }


            document.body.classList.add(
                "sidebar-open"
            );


            document.body.classList.remove(
                "sidebar-collapsed"
            );


            if (mobileOverlay) {

                mobileOverlay.style.display =
                    "block";
            }


            if (oldOverlay) {

                oldOverlay.style.display =
                    "block";
            }

        }


        // =================================================
        // CLOSE MOBILE SIDEBAR
        // =================================================

        function closeMobileSidebar() {

            if (!sidebar) {
                return;
            }


            document.body.classList.remove(
                "sidebar-open"
            );


            if (mobileOverlay) {

                mobileOverlay.style.display =
                    "none";
            }


            if (oldOverlay) {

                oldOverlay.style.display =
                    "none";
            }

        }


        // =================================================
        // TOGGLE MOBILE SIDEBAR
        // =================================================

        function toggleMobileSidebar() {

            if (
                document.body.classList.contains(
                    "sidebar-open"
                )
            ) {

                closeMobileSidebar();

            } else {

                openMobileSidebar();

            }

        }


        // =================================================
        // TOGGLE BUTTON
        //
        // CAPTURE TRUE
        //
        // PREVENTS main.js DOUBLE TOGGLE
        // =================================================

        toggleButtons.forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function (event) {

                        event.preventDefault();

                        event.stopImmediatePropagation();


                        if (isMobile()) {

                            toggleMobileSidebar();

                        } else {

                            document.body.classList.toggle(
                                "sidebar-collapsed"
                            );

                        }

                    },
                    true
                );

            }
        );


        // =================================================
        // MOBILE OVERLAY CLICK
        // =================================================

        if (mobileOverlay) {

            mobileOverlay.addEventListener(
                "click",
                function () {

                    closeMobileSidebar();

                }
            );

        }


        // =================================================
        // OLD OVERLAY CLICK
        // =================================================

        if (oldOverlay) {

            oldOverlay.addEventListener(
                "click",
                function () {

                    closeMobileSidebar();

                }
            );

        }


        // =================================================
        // OUTSIDE CLICK
        // =================================================

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


                const clickedInsideSidebar =
                    sidebar &&
                    sidebar.contains(
                        event.target
                    );


                const clickedToggle =
                    event.target.closest(
                        ".sidebar-toggler, .navbar-toggler"
                    );


                if (
                    !clickedInsideSidebar &&
                    !clickedToggle
                ) {

                    closeMobileSidebar();

                }

            }
        );


        // =================================================
        // SIDEBAR LINKS
        // =================================================

        if (sidebar) {

            const sidebarLinks =
                sidebar.querySelectorAll("a");


            sidebarLinks.forEach(
                function (link) {

                    link.addEventListener(
                        "click",
                        function () {

                            if (isMobile()) {

                                closeMobileSidebar();

                            }

                        }
                    );

                }
            );

        }


        // =================================================
        // ESCAPE KEY
        // =================================================

        document.addEventListener(
            "keydown",
            function (event) {

                if (event.key === "Escape") {

                    closeMobileSidebar();

                }

            }
        );


        // =================================================
        // WINDOW RESIZE
        // =================================================

        window.addEventListener(
            "resize",
            function () {

                if (window.innerWidth > 991) {

                    closeMobileSidebar();

                } else {

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