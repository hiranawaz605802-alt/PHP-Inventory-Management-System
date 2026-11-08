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
// INSERT CART ITEM
// =====================================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // -------------------------------------------------
    // CART ID
    // -------------------------------------------------

    $cart_id = isset($_POST["cart_id"])
        ? (int) $_POST["cart_id"]
        : 0;


    // -------------------------------------------------
    // PRODUCT ID
    // -------------------------------------------------

    $product_id = isset($_POST["product_id"])
        ? (int) $_POST["product_id"]
        : 0;


    // -------------------------------------------------
    // QUANTITY
    // -------------------------------------------------

    $quantity = isset($_POST["quantity"])
        ? (int) $_POST["quantity"]
        : 0;


    // =================================================
    // VALIDATION
    // =================================================

    if ($cart_id <= 0) {

        $message = "Please select Cart ID.";
        $message_type = "danger";

    } elseif ($product_id <= 0) {

        $message = "Please select Product ID.";
        $message_type = "danger";

    } elseif ($quantity <= 0) {

        $message = "Please enter valid Quantity.";
        $message_type = "danger";

    } else {

        // =================================================
        // CHECK CART
        // =================================================

        $cart_check_sql = "
            SELECT cart_id
            FROM entity_asad_shooping_cart
            WHERE cart_id = $cart_id
            LIMIT 1
        ";

        $cart_check = mysqli_query(
            $conn,
            $cart_check_sql
        );


        if (!$cart_check) {

            $message =
                "Cart Check Error: " .
                mysqli_error($conn);

            $message_type = "danger";

        } elseif (mysqli_num_rows($cart_check) == 0) {

            $message =
                "Selected Cart ID does not exist.";

            $message_type = "danger";

        } else {

            // =================================================
            // CHECK PRODUCT
            // =================================================

            $product_sql = "
                SELECT
                    id,
                    price,
                    stock_quantity
                FROM entity_asad_product
                WHERE id = $product_id
                LIMIT 1
            ";

            $product_result = mysqli_query(
                $conn,
                $product_sql
            );


            if (!$product_result) {

                $message =
                    "Product Check Error: " .
                    mysqli_error($conn);

                $message_type = "danger";

            } elseif (
                mysqli_num_rows($product_result) == 0
            ) {

                $message =
                    "Selected Product does not exist.";

                $message_type = "danger";

            } else {

                $product = mysqli_fetch_assoc(
                    $product_result
                );


                // =================================================
                // PRODUCT PRICE
                // =================================================

                $unit_price =
                    (float) $product["price"];


                // =================================================
                // STOCK
                // =================================================

                $stock_quantity =
                    (int) $product["stock_quantity"];


                // =================================================
                // STOCK CHECK
                // =================================================

                if ($quantity > $stock_quantity) {

                    $message =
                        "Only " .
                        $stock_quantity .
                        " quantity available in stock.";

                    $message_type = "danger";

                } elseif ($unit_price <= 0) {

                    $message =
                        "Product Unit Price is not valid.";

                    $message_type = "danger";

                } else {

                    // =================================================
                    // INSERT CART ITEM
                    // =================================================

                    $insert_sql = "
                        INSERT INTO entity_asad_cart_items
                        (
                            cart_id,
                            product_id,
                            quantity,
                            unit_price
                        )
                        VALUES
                        (
                            $cart_id,
                            $product_id,
                            $quantity,
                            $unit_price
                        )
                    ";


                    $insert_result = mysqli_query(
                        $conn,
                        $insert_sql
                    );


                    if ($insert_result) {

                        header(
                            "Location: cart_items_product_view.php"
                        );

                        exit();

                    } else {

                        $message =
                            "Insert Error: " .
                            mysqli_error($conn);

                        $message_type = "danger";
                    }
                }
            }
        }
    }
}


// =====================================================
// GET CART IDS
// =====================================================

$cart_sql = "
    SELECT
        cart_id
    FROM entity_asad_shooping_cart
    ORDER BY cart_id DESC
";


$cart_result = mysqli_query(
    $conn,
    $cart_sql
);


if (!$cart_result) {

    die(
        "Shopping Cart Query Error: " .
        mysqli_error($conn)
    );

}


$shopping_carts = [];


while (
    $row = mysqli_fetch_assoc($cart_result)
) {

    $shopping_carts[] = $row;
}


// =====================================================
// GET PRODUCTS
// =====================================================

$product_sql = "
    SELECT
        id,
        name,
        price,
        stock_quantity
    FROM entity_asad_product
    ORDER BY id DESC
";


$product_result = mysqli_query(
    $conn,
    $product_sql
);


if (!$product_result) {

    die(
        "Product Query Error: " .
        mysqli_error($conn)
    );

}


$products = [];


while (
    $row = mysqli_fetch_assoc($product_result)
) {

    $products[] = $row;
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

    <title>Add Cart Item</title>


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
         FONT AWESOME
    ====================================================== -->

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
        rel="stylesheet"
    >


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
}


body {

    background: #000 !important;
}


*,
*::before,
*::after {

    box-sizing: border-box;
}


/* =====================================================
   MAIN WRAPPER
===================================================== */

.cart-main-wrapper {

    width: 100% !important;
    max-width: 100% !important;

    min-height: 100vh !important;

    margin: 0 !important;
    padding: 0 !important;

    position: relative !important;

    overflow-x: hidden !important;

    background: #000 !important;
}


/* =====================================================
   SIDEBAR DESKTOP
===================================================== */

.cart-main-wrapper .sidebar {

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

    pointer-events: auto !important;
}


/* =====================================================
   SIDEBAR LINKS
===================================================== */

.cart-main-wrapper .sidebar a,
.cart-main-wrapper .sidebar button,
.cart-main-wrapper .sidebar .nav-link {

    pointer-events: auto !important;

    position: relative !important;

    z-index: 100001 !important;

    cursor: pointer !important;
}


/* =====================================================
   CONTENT AREA
===================================================== */

.cart-content-area {

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


/* =====================================================
   NAVBAR
===================================================== */

.cart-content-area > .navbar {

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
   TOGGLE BUTTON
===================================================== */

.sidebar-toggler,
.navbar-toggler,
#sidebarToggle,
#sidebar-toggler,
.sidebar-toggle,
.menu-toggle,
[data-sidebar-toggle] {

    position: relative !important;

    z-index: 100001 !important;

    cursor: pointer !important;

    pointer-events: auto !important;

    border: 0 !important;

    outline: none !important;
}


/* =====================================================
   DESKTOP SIDEBAR COLLAPSE
===================================================== */

@media (min-width: 992px) {

    body.sidebar-collapsed
    .cart-main-wrapper .sidebar {

        left: -250px !important;
    }


    body.sidebar-collapsed
    .cart-content-area {

        width: 100% !important;
        max-width: 100% !important;

        margin-left: 0 !important;
    }

}


/* =====================================================
   PAGE
===================================================== */

.cart-page {

    width: 100% !important;
    max-width: 100% !important;

    min-height: calc(100vh - 70px) !important;

    margin: 0 !important;

    padding: 25px 25px 40px !important;

    overflow: hidden !important;
}


/* =====================================================
   FORM BOX
===================================================== */

.cart-box {

    width: 100% !important;
    max-width: 1050px !important;

    margin: 0 auto !important;

    padding: 30px !important;

    background: #191c24 !important;

    border:
        1px solid
        rgba(255,255,255,0.06) !important;

    border-radius: 12px !important;

    box-shadow:
        0 8px 30px
        rgba(0,0,0,0.25) !important;

    overflow: hidden !important;
}


/* =====================================================
   TITLE
===================================================== */

.cart-title {

    margin: 0 0 8px !important;

    color: #fff !important;

    font-size: 24px !important;

    font-weight: 600 !important;

    line-height: 1.4 !important;
}


/* =====================================================
   DIAGRAM TITLE
===================================================== */

.diagram-title {

    margin: 0 0 28px !important;

    padding-bottom: 18px !important;

    color: #8f9bbd !important;

    font-size: 13px !important;

    line-height: 1.7 !important;

    border-bottom:
        1px solid
        rgba(255,255,255,0.07) !important;
}


/* =====================================================
   ALERT
===================================================== */

.cart-box .alert {

    width: 100% !important;

    margin-bottom: 25px !important;

    border: none !important;

    border-radius: 7px !important;

    font-size: 14px !important;

    word-break: break-word !important;
}


/* =====================================================
   FIELD BOX
===================================================== */

.field-box {

    width: 100% !important;

    margin-bottom: 22px !important;

    padding: 24px !important;

    background: #20232d !important;

    border:
        1px solid
        rgba(255,255,255,0.05) !important;

    border-radius: 10px !important;

    overflow: hidden !important;
}


/* =====================================================
   FIELD TITLE
===================================================== */

.field-box h6 {

    margin: 0 0 22px !important;

    padding-bottom: 13px !important;

    color: #fff !important;

    font-size: 17px !important;

    font-weight: 600 !important;

    border-bottom:
        1px solid
        rgba(255,255,255,0.07) !important;
}


/* =====================================================
   ROW
===================================================== */

.cart-box .row {

    margin-left: -10px !important;
    margin-right: -10px !important;
}


.cart-box .row > div {

    padding-left: 10px !important;
    padding-right: 10px !important;
}


/* =====================================================
   LABEL
===================================================== */

.cart-box label {

    display: block !important;

    margin-bottom: 8px !important;

    color: #fff !important;

    font-size: 14px !important;

    font-weight: 500 !important;
}


/* =====================================================
   LABEL SMALL
===================================================== */

.cart-box label small {

    color: #8f9bbd !important;

    font-size: 11px !important;

    font-weight: 400 !important;
}


/* =====================================================
   INPUT SELECT
===================================================== */

.cart-box input,
.cart-box select {

    width: 100% !important;

    max-width: 100% !important;

    height: 46px !important;

    padding: 10px 13px !important;

    border:
        1px solid
        #343946 !important;

    border-radius: 6px !important;

    background: #fff !important;

    color: #222 !important;

    font-size: 14px !important;

    outline: none !important;

    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease !important;
}


/* =====================================================
   SELECT
===================================================== */

.cart-box select {

    cursor: pointer !important;
}


/* =====================================================
   FOCUS
===================================================== */

.cart-box input:focus,
.cart-box select:focus {

    border-color: #86b7fe !important;

    box-shadow:
        0 0 0 3px
        rgba(13,110,253,0.15) !important;
}


/* =====================================================
   READONLY
===================================================== */

.cart-box input[readonly] {

    background: #e9ecef !important;

    color: #495057 !important;

    cursor: not-allowed !important;

    border-color: #d6d9dc !important;
}


/* =====================================================
   UNIT PRICE
===================================================== */

#unit_price {

    font-weight: 600 !important;
}


/* =====================================================
   TOTAL
===================================================== */

#total {

    background: #e9ecef !important;

    color: #198754 !important;

    font-size: 15px !important;

    font-weight: 700 !important;
}


/* =====================================================
   SAVE BUTTON
===================================================== */

.save-btn {

    width: 100% !important;

    height: 50px !important;

    margin-top: 3px !important;

    border: none !important;

    border-radius: 7px !important;

    font-size: 15px !important;

    font-weight: 600 !important;

    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease !important;
}


.save-btn:hover {

    transform: translateY(-1px) !important;

    box-shadow:
        0 5px 15px
        rgba(13,110,253,0.25) !important;
}


/* =====================================================
   SIDEBAR OVERLAY
===================================================== */

.cart-sidebar-overlay {

    display: none;

    position: fixed !important;

    inset: 0 !important;

    width: 100vw !important;
    height: 100vh !important;

    background:
        rgba(0,0,0,0.55) !important;

    z-index: 99998 !important;

    cursor: pointer !important;
}


@media (max-width: 991px) {

    body.sidebar-open
    .cart-sidebar-overlay {

        display: block !important;
    }

}


/* =====================================================
   TABLET
===================================================== */

@media (max-width: 991px) {

    .cart-main-wrapper .sidebar {

        width: 250px !important;

        min-width: 250px !important;

        height: 100vh !important;

        position: fixed !important;

        top: 0 !important;

        left: -250px !important;

        bottom: 0 !important;

        z-index: 99999 !important;

        transition:
            left 0.3s ease !important;
    }


    body.sidebar-open
    .cart-main-wrapper .sidebar {

        left: 0 !important;
    }


    .cart-content-area {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        margin-left: 0 !important;

        padding: 0 !important;
    }


    .cart-content-area > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        padding-left: 15px !important;
        padding-right: 15px !important;
    }


    .cart-page {

        width: 100% !important;

        padding: 20px !important;
    }


    .cart-box {

        width: 100% !important;

        max-width: 100% !important;

        padding: 25px !important;
    }


    .field-box {

        padding: 20px !important;
    }


    .cart-box .row {

        margin-left: 0 !important;
        margin-right: 0 !important;
    }


    .cart-box .row > div {

        padding-left: 0 !important;
        padding-right: 0 !important;
    }

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 767px) {

    .cart-main-wrapper .sidebar {

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
    .cart-main-wrapper .sidebar {

        left: 0 !important;
    }


    .cart-content-area {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        margin-left: 0 !important;
    }


    .cart-content-area > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        padding-left: 10px !important;
        padding-right: 10px !important;
    }


    .cart-page {

        width: 100% !important;

        padding:
            12px 8px 30px !important;
    }


    .cart-box {

        width: 100% !important;

        max-width: 100% !important;

        padding: 16px !important;

        border-radius: 8px !important;
    }


    .cart-title {

        font-size: 19px !important;

        margin-bottom: 7px !important;
    }


    .diagram-title {

        font-size: 11px !important;

        line-height: 1.6 !important;

        padding-bottom: 14px !important;

        margin-bottom: 18px !important;

        word-break: break-word !important;
    }


    .field-box {

        width: 100% !important;

        padding: 15px !important;

        margin-bottom: 15px !important;

        border-radius: 8px !important;
    }


    .field-box h6 {

        font-size: 15px !important;

        margin-bottom: 17px !important;
    }


    .cart-box label {

        font-size: 13px !important;
    }


    .cart-box input,
    .cart-box select {

        width: 100% !important;

        height: 43px !important;

        padding:
            9px 11px !important;

        font-size: 13px !important;
    }


    .save-btn {

        width: 100% !important;

        height: 47px !important;

        font-size: 14px !important;
    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media (max-width: 575px) {

    .cart-page {

        padding:
            9px 6px 25px !important;
    }


    .cart-box {

        padding: 13px !important;

        border-radius: 7px !important;
    }


    .cart-title {

        font-size: 18px !important;
    }


    .field-box {

        padding: 13px !important;
    }


    .field-box h6 {

        font-size: 14px !important;

        padding-bottom: 11px !important;
    }


    .cart-box label {

        font-size: 12.5px !important;
    }


    .cart-box input,
    .cart-box select {

        height: 42px !important;

        font-size: 13px !important;
    }


    .cart-box .alert {

        font-size: 12px !important;

        padding: 10px !important;
    }

}


/* =====================================================
   VERY SMALL MOBILE
===================================================== */

@media (max-width: 380px) {

    .cart-page {

        padding:
            7px 5px 20px !important;
    }


    .cart-box {

        padding: 10px !important;
    }


    .field-box {

        padding: 11px !important;
    }


    .cart-title {

        font-size: 17px !important;
    }


    .diagram-title {

        font-size: 10px !important;
    }


    .cart-box input,
    .cart-box select {

        height: 41px !important;

        padding:
            8px 9px !important;

        font-size: 12px !important;
    }


    .save-btn {

        height: 45px !important;

        font-size: 13px !important;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     MAIN WRAPPER
===================================================== -->

<div class="cart-main-wrapper">


    <!-- =================================================
         SIDEBAR
    ================================================== -->

    <?php include("sidebar.php"); ?>


    <!-- =================================================
         MOBILE OVERLAY
    ================================================== -->

    <div
        class="cart-sidebar-overlay"
        id="sidebarOverlay"
    ></div>


    <!-- =================================================
         CONTENT
    ================================================== -->

    <div class="cart-content-area">


        <!-- =================================================
             NAVBAR
        ================================================== -->

        <?php include("navbar.php"); ?>


        <!-- =================================================
             PAGE
        ================================================== -->

        <div class="cart-page">


            <!-- =================================================
                 FORM BOX
            ================================================== -->

            <div class="cart-box">


                <!-- =================================================
                     TITLE
                ================================================== -->

                <h5 class="cart-title">

                    <i
                        class="fa fa-shopping-basket me-2"
                    ></i>

                    Add Cart Item

                </h5>


                <!-- =================================================
                     TABLE COLUMNS
                ================================================== -->

                <div class="diagram-title">

                    Cart_Item Table:

                    cart_item_id,

                    cart_id,

                    product_id,

                    quantity,

                    unit_price

                </div>


                <!-- =================================================
                     MESSAGE
                ================================================== -->

                <?php if ($message != "") { ?>

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
                     FORM
                ================================================== -->

                <form
                    method="POST"
                    id="cartItemForm"
                >


                    <!-- =================================================
                         CART
                    ================================================== -->

                    <div class="field-box">


                        <h6>

                            <i
                                class="fa fa-shopping-cart me-2"
                            ></i>

                            Cart Item

                        </h6>


                        <div class="row">


                            <div
                                class="col-md-6 mb-3"
                            >

                                <label>
                                    Cart ID
                                </label>


                                <select
                                    name="cart_id"
                                    id="cart_id"
                                    required
                                >

                                    <option value="">

                                        -- Select Cart ID --

                                    </option>


                                    <?php foreach (
                                        $shopping_carts
                                        as $cart
                                    ) { ?>

                                        <option
                                            value="<?php
                                                echo (int)
                                                $cart["cart_id"];
                                            ?>"
                                        >

                                            Cart ID:

                                            <?php

                                            echo htmlspecialchars(
                                                $cart["cart_id"],
                                                ENT_QUOTES,
                                                "UTF-8"
                                            );

                                            ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>


                        </div>

                    </div>


                    <!-- =================================================
                         PRODUCT
                    ================================================== -->

                    <div class="field-box">


                        <h6>

                            <i
                                class="fa fa-box me-2"
                            ></i>

                            Product

                        </h6>


                        <div class="row">


                            <div
                                class="col-md-6 mb-3"
                            >

                                <label>
                                    Product
                                </label>


                                <select
                                    name="product_id"
                                    id="product_id"
                                    required
                                >

                                    <option value="">

                                        -- Select Product --

                                    </option>


                                    <?php foreach (
                                        $products
                                        as $product
                                    ) { ?>

                                        <option
                                            value="<?php
                                                echo (int)
                                                $product["id"];
                                            ?>"
                                        >

                                            ID:

                                            <?php

                                            echo htmlspecialchars(
                                                $product["id"],
                                                ENT_QUOTES,
                                                "UTF-8"
                                            );

                                            ?>

                                            -

                                            <?php

                                            echo htmlspecialchars(
                                                $product["name"],
                                                ENT_QUOTES,
                                                "UTF-8"
                                            );

                                            ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>


                        </div>

                    </div>


                    <!-- =================================================
                         CART ITEM INFORMATION
                    ================================================== -->

                    <div class="field-box">


                        <h6>

                            <i
                                class="fa fa-shopping-basket me-2"
                            ></i>

                            Cart Item Information

                        </h6>


                        <div class="row">


                            <!-- CART ITEM ID -->

                            <div
                                class="col-md-4 mb-3"
                            >

                                <label>
                                    Cart Item ID
                                </label>


                                <input
                                    type="text"
                                    value="AUTO"
                                    readonly
                                >

                            </div>


                            <!-- QUANTITY -->

                            <div
                                class="col-md-4 mb-3"
                            >

                                <label>
                                    Quantity
                                </label>


                                <input
                                    type="number"
                                    name="quantity"
                                    id="quantity"
                                    min="1"
                                    placeholder="Enter Quantity"
                                    required
                                >

                            </div>


                            <!-- UNIT PRICE -->

                            <div
                                class="col-md-4 mb-3"
                            >

                                <label>
                                    Unit Price
                                </label>


                                <input
                                    type="number"
                                    name="unit_price"
                                    id="unit_price"
                                    step="0.01"
                                    readonly
                                    placeholder="Automatic"
                                >

                            </div>


                        </div>


                        <!-- =================================================
                             TOTAL
                        ================================================== -->

                        <div class="row">


                            <div
                                class="col-md-12 mb-3"
                            >

                                <label>

                                    Total

                                    <small>
                                        (Display Only)
                                    </small>

                                </label>


                                <input
                                    type="text"
                                    id="total"
                                    readonly
                                    placeholder="Quantity × Unit Price"
                                >

                            </div>


                        </div>


                    </div>


                    <!-- =================================================
                         SAVE
                    ================================================== -->

                    <button
                        type="submit"
                        class="btn btn-primary save-btn"
                    >

                        <i
                            class="fa fa-save me-2"
                        ></i>

                        Save Cart Item

                    </button>


                </form>


            </div>

        </div>

    </div>

</div>


<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>


// =====================================================
// PRODUCT DATA
// =====================================================

const products = <?php

    echo json_encode(
        $products,
        JSON_HEX_TAG |
        JSON_HEX_APOS |
        JSON_HEX_QUOT |
        JSON_HEX_AMP
    );

?>;


// =====================================================
// PRODUCT SELECT
// =====================================================

const productSelect =
    document.getElementById(
        "product_id"
    );


if (productSelect) {

    productSelect.addEventListener(
        "change",
        function () {

            const productId =
                this.value;


            const product =
                products.find(
                    function (row) {

                        return String(
                            row.id
                        ) === String(
                            productId
                        );

                    }
                );


            if (product) {

                document.getElementById(
                    "unit_price"
                ).value =
                    product.price || "";


                document.getElementById(
                    "quantity"
                ).max =
                    product.stock_quantity || "";


                checkStock();

                calculateTotal();

            } else {

                document.getElementById(
                    "unit_price"
                ).value = "";


                document.getElementById(
                    "quantity"
                ).removeAttribute(
                    "max"
                );


                document.getElementById(
                    "total"
                ).value = "";

            }

        }
    );

}


// =====================================================
// QUANTITY
// =====================================================

const quantityInput =
    document.getElementById(
        "quantity"
    );


if (quantityInput) {

    quantityInput.addEventListener(
        "input",
        function () {

            checkStock();

            calculateTotal();

        }
    );

}


// =====================================================
// CALCULATE TOTAL
// =====================================================

function calculateTotal() {

    const quantity =
        parseFloat(
            document.getElementById(
                "quantity"
            ).value
        ) || 0;


    const price =
        parseFloat(
            document.getElementById(
                "unit_price"
            ).value
        ) || 0;


    const total =
        quantity * price;


    if (
        quantity > 0 &&
        price > 0
    ) {

        document.getElementById(
            "total"
        ).value =
            total.toFixed(2);

    } else {

        document.getElementById(
            "total"
        ).value = "";

    }

}


// =====================================================
// STOCK CHECK
// =====================================================

function checkStock() {

    const productId =
        document.getElementById(
            "product_id"
        ).value;


    const quantity =
        parseInt(
            document.getElementById(
                "quantity"
            ).value
        ) || 0;


    const quantityInput =
        document.getElementById(
            "quantity"
        );


    const product =
        products.find(
            function (row) {

                return String(
                    row.id
                ) === String(
                    productId
                );

            }
        );


    if (
        product &&
        quantity >
        parseInt(
            product.stock_quantity
        )
    ) {

        quantityInput.setCustomValidity(

            "Only " +
            product.stock_quantity +
            " quantity available in stock."

        );

    } else {

        quantityInput.setCustomValidity("");

    }

}


// =====================================================
// SIDEBAR SYSTEM
// SAME FIX AS USERS / PRODUCT PAGE
// =====================================================

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const togglers =
            document.querySelectorAll(
                ".sidebar-toggler, " +
                ".navbar-toggler, " +
                "#sidebarToggle, " +
                "#sidebar-toggler, " +
                ".sidebar-toggle, " +
                ".menu-toggle, " +
                "[data-sidebar-toggle]"
            );


        const sidebar =
            document.querySelector(
                ".cart-main-wrapper .sidebar"
            );


        const overlay =
            document.getElementById(
                "sidebarOverlay"
            );


        if (!sidebar) {

            console.log(
                "Sidebar not found."
            );

            return;
        }


        // =================================================
        // TOGGLE SIDEBAR
        // =================================================

        function toggleSidebar(event) {

            if (event) {

                event.preventDefault();

                event.stopPropagation();

            }


            if (window.innerWidth <= 991) {

                document.body.classList.toggle(
                    "sidebar-open"
                );

                document.body.classList.remove(
                    "sidebar-collapsed"
                );

            } else {

                document.body.classList.toggle(
                    "sidebar-collapsed"
                );

                document.body.classList.remove(
                    "sidebar-open"
                );

            }

        }


        // =================================================
        // TOGGLER EVENTS
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


                        // Empty links ignore
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


                        // Dropdown links ko ignore
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


                        // Mobile sidebar close
                        document.body.classList.remove(
                            "sidebar-open"
                        );


                        // Mobile/tablet direct navigation
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


        // =================================================
        // OVERLAY CLICK
        // =================================================

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

                    document.body.classList.remove(
                        "sidebar-open"
                    );

                }

            },
            true
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


<!-- =====================================================
     JQUERY
===================================================== -->

<script
    src="https://code.jquery.com/jquery-3.4.1.min.js">
</script>


<!-- =====================================================
     BOOTSTRAP JS
===================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>