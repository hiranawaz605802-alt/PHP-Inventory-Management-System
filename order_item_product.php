<?php

include_once("Database.php");

$db = new Database();
$conn = $db->connection();


// =====================================================
// MESSAGE
// =====================================================

$message = "";
$message_type = "";


// =====================================================
// SAVE ORDER ITEM
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $order_id = isset($_POST["order_id"])
        ? (int) $_POST["order_id"]
        : 0;

    $product_id = isset($_POST["product_id"])
        ? (int) $_POST["product_id"]
        : 0;

    $quantity = isset($_POST["quantity"])
        ? (int) $_POST["quantity"]
        : 0;


    // =================================================
    // VALIDATION
    // =================================================

    if ($order_id <= 0) {

        $message = "Please select Order ID.";
        $message_type = "danger";

    } elseif ($product_id <= 0) {

        $message = "Please select Product.";
        $message_type = "danger";

    } elseif ($quantity <= 0) {

        $message = "Please enter valid Quantity.";
        $message_type = "danger";

    } else {


        // =================================================
        // CHECK ORDER
        // =================================================

        $order_check_sql = "
            SELECT order_id
            FROM entity_asad_order
            WHERE order_id = $order_id
            LIMIT 1
        ";

        $order_check_result = mysqli_query(
            $conn,
            $order_check_sql
        );


        if (!$order_check_result) {

            $message =
                "Order Check Error: " .
                mysqli_error($conn);

            $message_type = "danger";

        } elseif (mysqli_num_rows($order_check_result) == 0) {

            $message =
                "Selected Order ID does not exist.";

            $message_type = "danger";

        } else {


            // =================================================
            // CHECK PRODUCT
            // =================================================

            $product_check_sql = "
                SELECT
                    id,
                    name,
                    price,
                    stock_quantity
                FROM entity_asad_product
                WHERE id = $product_id
                LIMIT 1
            ";

            $product_check_result = mysqli_query(
                $conn,
                $product_check_sql
            );


            if (!$product_check_result) {

                $message =
                    "Product Check Error: " .
                    mysqli_error($conn);

                $message_type = "danger";

            } elseif (
                mysqli_num_rows(
                    $product_check_result
                ) == 0
            ) {

                $message =
                    "Selected Product does not exist.";

                $message_type = "danger";

            } else {


                // =================================================
                // GET PRODUCT
                // =================================================

                $product = mysqli_fetch_assoc(
                    $product_check_result
                );


                $unit_price =
                    (float) $product["price"];


                $stock_quantity =
                    (int) $product["stock_quantity"];


                // =================================================
                // STOCK VALIDATION
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
                    // INSERT ORDER ITEM
                    // =================================================

                    $unit_price_sql =
                        number_format(
                            $unit_price,
                            2,
                            ".",
                            ""
                        );


                    $insert_sql = "
                        INSERT INTO entity_asad_order_item
                        (
                            order_id,
                            product_id,
                            quantity,
                            until_price
                        )
                        VALUES
                        (
                            $order_id,
                            $product_id,
                            $quantity,
                            $unit_price_sql
                        )
                    ";


                    $insert_result =
                        mysqli_query(
                            $conn,
                            $insert_sql
                        );


                    if ($insert_result) {

                        header(
                            "Location: order_item_product_view.php"
                        );

                        exit();

                    } else {

                        $message =
                            "Order Item Insert Error: " .
                            mysqli_error($conn);

                        $message_type = "danger";
                    }
                }
            }
        }
    }
}


// =====================================================
// GET ORDERS
// =====================================================

$order_sql = "
    SELECT
        order_id,
        customer_id,
        order_date,
        total_amount,
        status
    FROM entity_asad_order
    ORDER BY order_id DESC
";


$order_result = mysqli_query(
    $conn,
    $order_sql
);


if (!$order_result) {

    die(
        "Order Query Error: " .
        mysqli_error($conn)
    );
}


$orders = [];


while ($row = mysqli_fetch_assoc($order_result)) {

    $orders[] = $row;
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


while ($row = mysqli_fetch_assoc($product_result)) {

    $products[] = $row;
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

```
<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Add Order Item</title>


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
>

<link
    rel="preconnect"
    href="https://fonts.gstatic.com"
    crossorigin
>

<link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&family=Roboto:wght@500;600;700&display=swap"
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
```

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
ORDER ITEM PAGE
========================================================= */

.order-item-page {

    width: 100% !important;

    max-width: 100% !important;

    min-height: calc(100vh - 70px);

    margin: 0 !important;

    padding: 20px !important;

    background: #000 !important;

    overflow: hidden !important;
}


/* =========================================================
FORM BOX
========================================================= */

.order-item-box {

    width: 100% !important;

    max-width: 1050px;

    margin: 0 auto !important;

    padding: 28px;

    background: #191c24;

    border-radius: 10px;

    border:
        1px solid rgba(255,255,255,0.06);

    box-shadow:
        0 8px 30px rgba(0,0,0,0.25);
}


/* =========================================================
TITLE
========================================================= */

.order-item-title {

    margin: 0 0 8px 0 !important;

    color: #ffffff;

    font-size: 22px;

    font-weight: 600;
}


/* =========================================================
TABLE INFO
========================================================= */

.table-info {

    margin-bottom: 22px;

    padding-bottom: 16px;

    color: #8f9bbd;

    font-size: 13px;

    line-height: 1.7;

    border-bottom:
        1px solid rgba(255,255,255,0.07);
}


/* =========================================================
ALERT
========================================================= */

.order-item-box .alert {

    margin-bottom: 20px !important;

    border: none;

    border-radius: 6px;
}


/* =========================================================
FORM SECTION
========================================================= */

.form-section {

    width: 100%;

    margin-bottom: 20px;

    padding: 22px;

    background: #20232d;

    border:
        1px solid rgba(255,255,255,0.05);

    border-radius: 9px;
}


/* =========================================================
SECTION TITLE
========================================================= */

.form-section-title {

    margin: 0 0 20px 0 !important;

    padding-bottom: 12px;

    color: #ffffff;

    font-size: 16px;

    font-weight: 600;

    border-bottom:
        1px solid rgba(255,255,255,0.07);
}


/* =========================================================
LABEL
========================================================= */

.order-item-box label {

    display: block;

    margin-bottom: 7px;

    color: #ffffff;

    font-size: 13px;

    font-weight: 600;
}


/* =========================================================
INPUT + SELECT
========================================================= */

.order-item-box input,
.order-item-box select {

    width: 100% !important;

    height: 44px !important;

    padding: 8px 12px !important;

    background: #ffffff !important;

    color: #222222 !important;

    border: 1px solid #d7d7d7 !important;

    border-radius: 5px !important;

    font-size: 13px !important;

    outline: none !important;
}


.order-item-box input:focus,
.order-item-box select:focus {

    border-color: #0d6efd !important;

    box-shadow:
        0 0 0 2px rgba(13,110,253,0.15) !important;
}


/* =========================================================
READONLY
========================================================= */

.order-item-box input[readonly] {

    background: #e9ecef !important;

    color: #495057 !important;

    cursor: not-allowed;
}


/* =========================================================
TOTAL
========================================================= */

#total {

    background: #e9ecef !important;

    color: #198754 !important;

    font-weight: 700 !important;
}


#unit_price {

    font-weight: 600 !important;
}


/* =========================================================
STOCK INFO
========================================================= */

.stock-info {

    display: block;

    margin-top: 6px;

    color: #8f9bbd;

    font-size: 12px;

    line-height: 1.5;
}


/* =========================================================
SAVE BUTTON
========================================================= */

.save-btn {

    width: 100% !important;

    min-height: 48px;

    border: none !important;

    border-radius: 6px !important;

    font-size: 14px !important;

    font-weight: 600 !important;
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


    .order-item-page {

        padding: 16px !important;
    }


    .order-item-box {

        max-width: 100%;

        padding: 22px;
    }


    .form-section {

        padding: 19px;
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


    .order-item-page {

        width: 100% !important;

        padding: 10px 7px 25px !important;
    }


    .order-item-box {

        width: 100% !important;

        max-width: 100%;

        padding: 15px !important;

        border-radius: 8px;
    }


    .order-item-title {

        font-size: 19px;

        margin-bottom: 7px !important;
    }


    .table-info {

        font-size: 12px;

        margin-bottom: 18px;

        padding-bottom: 13px;
    }


    .form-section {

        padding: 15px !important;

        margin-bottom: 14px;

        border-radius: 7px;
    }


    .form-section-title {

        font-size: 15px;

        margin-bottom: 17px !important;
    }


    .order-item-box .row {

        margin-left: 0 !important;

        margin-right: 0 !important;
    }


    .order-item-box .col-md-4,
    .order-item-box .col-md-6 {

        width: 100% !important;

        padding-left: 0 !important;

        padding-right: 0 !important;

        margin-bottom: 12px !important;
    }


    .order-item-box input,
    .order-item-box select {

        height: 43px !important;

        font-size: 13px !important;
    }


    .save-btn {

        min-height: 46px;

        font-size: 14px !important;
    }

}


/* =========================================================
SMALL MOBILE
========================================================= */

@media (max-width: 575px) {

    .order-item-page {

        padding: 8px 5px 20px !important;
    }


    .order-item-box {

        padding: 13px !important;
    }


    .form-section {

        padding: 13px !important;
    }


    .order-item-title {

        font-size: 18px;
    }


    .form-section-title {

        font-size: 14px;
    }


    .order-item-box label {

        font-size: 12px;
    }


    .stock-info {

        font-size: 11px;
    }

}


/* =========================================================
VERY SMALL MOBILE
========================================================= */

@media (max-width: 380px) {

    .order-item-page {

        padding: 6px 4px 18px !important;
    }


    .order-item-box {

        padding: 10px !important;
    }


    .form-section {

        padding: 10px !important;
    }

}

</style>

</head>

<body>

<!-- =====================================================
MAIN WRAPPER
====================================================== -->

<div class="main-container">

```
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
         ORDER ITEM PAGE
    ================================================== -->

    <div class="order-item-page">


        <div class="order-item-box">


            <!-- =================================================
                 TITLE
            ================================================= -->

            <h5 class="order-item-title">

                <i class="fa fa-file-invoice me-2"></i>

                Add Order Item

            </h5>


            <!-- =================================================
                 TABLE INFO
            ================================================= -->

            <div class="table-info">

                Order_Item Table:

                OrderItemID,

                OrderID,

                ProductID,

                Quantity,

                UnitPrice

            </div>


            <!-- =================================================
                 MESSAGE
            ================================================= -->

            <?php if ($message !== "") { ?>

                <div
                    class="alert alert-<?php
                        echo htmlspecialchars(
                            $message_type
                        );
                    ?>"
                >

                    <?php
                    echo htmlspecialchars(
                        $message
                    );
                    ?>

                </div>

            <?php } ?>


            <!-- =================================================
                 FORM
            ================================================= -->

            <form
                method="POST"
                id="orderItemForm"
            >


                <!-- =================================================
                     ORDER INFORMATION
                ================================================= -->

                <div class="form-section">


                    <h6 class="form-section-title">

                        <i class="fa fa-file-invoice me-2"></i>

                        Order Information

                    </h6>


                    <div class="row">


                        <!-- ORDER ID -->

                        <div class="col-md-6 mb-3">

                            <label for="order_id">

                                Order ID

                            </label>


                            <select
                                name="order_id"
                                id="order_id"
                                required
                            >

                                <option value="">

                                    -- Select Order ID --

                                </option>


                                <?php foreach ($orders as $order) { ?>

                                    <option
                                        value="<?php
                                            echo (int)
                                                $order["order_id"];
                                        ?>"
                                    >

                                        Order ID:

                                        <?php
                                        echo htmlspecialchars(
                                            $order["order_id"]
                                        );
                                        ?>

                                        -

                                        Customer ID:

                                        <?php
                                        echo htmlspecialchars(
                                            $order["customer_id"]
                                        );
                                        ?>

                                    </option>

                                <?php } ?>

                            </select>


                            <span class="stock-info">

                                Select the Order for this Order Item.

                            </span>

                        </div>


                        <!-- ORDER ITEM ID -->

                        <div class="col-md-6 mb-3">

                            <label for="order_item_id">

                                Order Item ID

                            </label>


                            <input
                                type="text"
                                id="order_item_id"
                                value="AUTO"
                                readonly
                            >

                        </div>


                    </div>

                </div>


                <!-- =================================================
                     PRODUCT INFORMATION
                ================================================== -->

                <div class="form-section">


                    <h6 class="form-section-title">

                        <i class="fa fa-box me-2"></i>

                        Product Information

                    </h6>


                    <div class="row">


                        <div class="col-md-6 mb-3">

                            <label for="product_id">

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


                                <?php foreach ($products as $product) { ?>

                                    <option
                                        value="<?php
                                            echo (int)
                                                $product["id"];
                                        ?>"
                                    >

                                        ID:

                                        <?php
                                        echo htmlspecialchars(
                                            $product["id"]
                                        );
                                        ?>

                                        -

                                        <?php
                                        echo htmlspecialchars(
                                            $product["name"]
                                        );
                                        ?>

                                    </option>

                                <?php } ?>

                            </select>


                            <span
                                class="stock-info"
                                id="stockInfo"
                            >

                                Select product to see stock.

                            </span>

                        </div>


                    </div>

                </div>


                <!-- =================================================
                     ORDER ITEM DETAILS
                ================================================== -->

                <div class="form-section">


                    <h6 class="form-section-title">

                        <i class="fa fa-shopping-basket me-2"></i>

                        Order Item Details

                    </h6>


                    <div class="row">


                        <!-- QUANTITY -->

                        <div class="col-md-4 mb-3">

                            <label for="quantity">

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

                        <div class="col-md-4 mb-3">

                            <label for="unit_price">

                                Unit Price

                            </label>


                            <input
                                type="number"
                                id="unit_price"
                                step="0.01"
                                readonly
                                placeholder="Automatic"
                            >

                        </div>


                        <!-- TOTAL -->

                        <div class="col-md-4 mb-3">

                            <label for="total">

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
                     SAVE BUTTON
                ================================================== -->

                <button
                    type="submit"
                    class="btn btn-primary save-btn"
                >

                    <i class="fa fa-save me-2"></i>

                    Save Order Item

                </button>


            </form>


        </div>


    </div>


</div>
```

</div>

<!-- =====================================================
JQUERY
====================================================== -->

<script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
></script>

<!-- =====================================================
BOOTSTRAP JS
====================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
></script>

<!-- =====================================================
DARKPAN MAIN JS
====================================================== -->

<script src="js/main.js"></script>

<!-- =====================================================
PRODUCT JAVASCRIPT
====================================================== -->

<script>

/* =====================================================
   PRODUCT DATA
===================================================== */

const products = <?php

echo json_encode(
    $products,
    JSON_HEX_TAG |
    JSON_HEX_APOS |
    JSON_HEX_QUOT |
    JSON_HEX_AMP
);

?>;


/* =====================================================
   ELEMENTS
===================================================== */

const productSelect =
    document.getElementById("product_id");

const quantityInput =
    document.getElementById("quantity");

const unitPriceInput =
    document.getElementById("unit_price");

const totalInput =
    document.getElementById("total");

const stockInfo =
    document.getElementById("stockInfo");


/* =====================================================
   FIND PRODUCT
===================================================== */

function getSelectedProduct() {

    if (!productSelect) {

        return null;
    }


    const productId =
        productSelect.value;


    return products.find(
        function (row) {

            return String(row.id) ===
                String(productId);

        }
    ) || null;

}


/* =====================================================
   PRODUCT CHANGE
===================================================== */

if (productSelect) {

    productSelect.addEventListener(
        "change",
        function () {

            const product =
                getSelectedProduct();


            if (product) {

                const price =
                    parseFloat(product.price) || 0;


                const stock =
                    parseInt(
                        product.stock_quantity
                    ) || 0;


                unitPriceInput.value =
                    price.toFixed(2);


                quantityInput.max =
                    stock;


                stockInfo.textContent =
                    "Available Stock: " +
                    stock;


                checkStock();

                calculateTotal();

            } else {

                unitPriceInput.value = "";

                quantityInput.value = "";

                quantityInput.removeAttribute(
                    "max"
                );

                stockInfo.textContent =
                    "Select product to see stock.";

                totalInput.value = "";

                quantityInput.setCustomValidity("");

            }

        }
    );

}


/* =====================================================
   QUANTITY INPUT
===================================================== */

if (quantityInput) {

    quantityInput.addEventListener(
        "input",
        function () {

            checkStock();

            calculateTotal();

        }
    );

}


/* =====================================================
   CALCULATE TOTAL
===================================================== */

function calculateTotal() {

    const quantity =
        parseFloat(
            quantityInput.value
        ) || 0;


    const price =
        parseFloat(
            unitPriceInput.value
        ) || 0;


    const total =
        quantity * price;


    if (
        quantity > 0 &&
        price > 0
    ) {

        totalInput.value =
            total.toFixed(2);

    } else {

        totalInput.value = "";

    }

}


/* =====================================================
   STOCK CHECK
===================================================== */

function checkStock() {

    const product =
        getSelectedProduct();


    const quantity =
        parseInt(
            quantityInput.value
        ) || 0;


    if (!product) {

        quantityInput.setCustomValidity("");

        return;
    }


    const stock =
        parseInt(
            product.stock_quantity
        ) || 0;


    if (quantity > stock) {

        quantityInput.setCustomValidity(
            "Only " +
            stock +
            " quantity available in stock."
        );


        stockInfo.textContent =
            "Available Stock: " +
            stock +
            " | Quantity exceeds stock.";

    } else {

        quantityInput.setCustomValidity("");

        stockInfo.textContent =
            "Available Stock: " +
            stock;

    }

}


/* =====================================================
   FORM VALIDATION
===================================================== */

const orderItemForm =
    document.getElementById(
        "orderItemForm"
    );


if (orderItemForm) {

    orderItemForm.addEventListener(
        "submit",
        function (event) {

            checkStock();


            if (
                !quantityInput.checkValidity()
            ) {

                event.preventDefault();

                quantityInput.reportValidity();

                return false;
            }

        }
    );

}


/* =====================================================
   MOBILE SIDEBAR FIX
   SAME SYSTEM AS PURCHASE DETAILS PAGE
===================================================== */

document.addEventListener(
    "DOMContentLoaded",
    function () {


        /* =================================================
           FIND ALL SIDEBAR TOGGLERS
        ================================================= */

        const togglers =
            document.querySelectorAll(
                ".sidebar-toggler, .navbar-toggler"
            );


        /* =================================================
           FIND SIDEBAR
        ================================================= */

        const sidebar =
            document.querySelector(
                ".main-container .sidebar"
            );


        /* =================================================
           FIND OVERLAY
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
           ADD TOGGLE EVENT
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
                           IGNORE DROPDOWN BUTTONS
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
                           CLOSE SIDEBAR
                        ================================= */

                        document.body.classList.remove(
                            "sidebar-open"
                        );


                        /* =================================
                           MOBILE / TABLET
                        ================================= */

                        if (
                            window.innerWidth <= 991
                        ) {

                            event.preventDefault();

                            event.stopPropagation();


                            /* =============================
                               DIRECT OPEN
                            ============================= */

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
           RESIZE FIX
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
