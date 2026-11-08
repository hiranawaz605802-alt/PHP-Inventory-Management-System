<?php

include("Database.php");

$db = new Database();
$conn = $db->connection();


// =====================================================
// CURRENT PAGE
// =====================================================

$current_page = "payement_product_add.php";


// =====================================================
// MESSAGE
// =====================================================

$message = "";
$message_type = "";


// =====================================================
// SAVE PAYMENT
// =====================================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // -------------------------------------------------
    // ORDER ID
    // -------------------------------------------------

    $order_id = isset($_POST["order_id"])
        ? (int) $_POST["order_id"]
        : 0;


    // -------------------------------------------------
    // AMOUNT
    // -------------------------------------------------

    $amount = isset($_POST["amount"])
        ? (float) $_POST["amount"]
        : 0;


    // -------------------------------------------------
    // PAYMENT DATE
    // -------------------------------------------------

    $payement_date = isset($_POST["payement_date"])
        ? trim($_POST["payement_date"])
        : date("Y-m-d");


    // -------------------------------------------------
    // PAYMENT METHOD
    // -------------------------------------------------

    $payement_method = isset($_POST["payement_method"])
        ? trim($_POST["payement_method"])
        : "";


    // =================================================
    // VALIDATION
    // =================================================

    if ($order_id <= 0) {

        $message = "Please select Order.";
        $message_type = "danger";

    }

    elseif ($amount <= 0) {

        $message = "Please enter Payment Amount.";
        $message_type = "danger";

    }

    elseif ($payement_date == "") {

        $message = "Please select Payment Date.";
        $message_type = "danger";

    }

    elseif ($payement_method == "") {

        $message = "Please select Payment Method.";
        $message_type = "danger";

    }

    else {


        // =================================================
        // CHECK ORDER
        // =================================================

        $order_check_sql = "

            SELECT

                order_id,
                customer_id,
                order_date,
                total_amount,
                status

            FROM entity_asad_order

            WHERE order_id = $order_id

            LIMIT 1

        ";


        $order_check = mysqli_query(
            $conn,
            $order_check_sql
        );


        if (!$order_check) {

            $message =
                "Order Error: " .
                mysqli_error($conn);

            $message_type = "danger";

        }

        elseif (
            mysqli_num_rows($order_check) == 0
        ) {

            $message =
                "Selected Order does not exist.";

            $message_type = "danger";

        }

        else {


            // =================================================
            // SAFE VALUES
            // =================================================

            $safe_date =
                mysqli_real_escape_string(
                    $conn,
                    $payement_date
                );


            $safe_method =
                mysqli_real_escape_string(
                    $conn,
                    $payement_method
                );


            // =================================================
            // INSERT PAYMENT
            // =================================================

            $insert_sql = "

                INSERT INTO entity_asad_payements

                (
                    order_id,
                    amount,
                    payement_date,
                    payement_method
                )

                VALUES

                (
                    $order_id,
                    $amount,
                    '$safe_date',
                    '$safe_method'
                )

            ";


            $insert_result =
                mysqli_query(
                    $conn,
                    $insert_sql
                );


            if ($insert_result) {

                header(
                    "Location: payement_product_view.php"
                );

                exit();

            }

            else {

                $message =
                    "Payment Insert Error: " .
                    mysqli_error($conn);

                $message_type = "danger";

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


$order_result =
    mysqli_query(
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


while (
    $row =
        mysqli_fetch_assoc(
            $order_result
        )
) {

    $orders[] = $row;

}

?>


<!DOCTYPE html>

<html lang="en">


<head>


    <meta charset="utf-8">


    <title>
        DarkPan - Add Payment
    </title>


    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >


    <!-- =================================================
         GOOGLE FONT
    ================================================= -->

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
    ================================================= -->

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
        rel="stylesheet"
    >


    <!-- =================================================
         BOOTSTRAP
    ================================================= -->

    <link
        href="css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- =================================================
         DARKPAN
    ================================================= -->

    <link
        href="css/style.css"
        rel="stylesheet"
    >


<style>


/* =====================================================
   GLOBAL RESET
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
   MAIN CONTAINER
===================================================== */

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


/* =====================================================
   SIDEBAR
   SAME AS UPPER PAYMENT VIEW CODE
===================================================== */

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
   DESKTOP SIDEBAR COLLAPSE
===================================================== */

@media (min-width: 992px) {


    body.sidebar-collapsed
    .main-container
    .sidebar {

        left: -250px !important;

    }


    body.sidebar-collapsed
    .content {

        width: 100% !important;

        max-width: 100% !important;

        margin-left: 0 !important;

    }

}


/* =====================================================
   MOBILE SIDEBAR OVERLAY
===================================================== */

.sidebar-mobile-overlay {

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


/* =====================================================
   BACK TO TOP
===================================================== */

.back-to-top {

    display: none !important;

    visibility: hidden !important;

    opacity: 0 !important;

}


/* =====================================================
   PAYMENT PAGE
===================================================== */

.payment-page {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    padding: 24px !important;

    margin: 0 !important;

    box-sizing: border-box !important;

    overflow-x: hidden !important;

    background: #000 !important;

}


/* =====================================================
   PAYMENT BOX
===================================================== */

.payment-box {

    width: 100% !important;

    max-width: 100% !important;

    background: #191c24 !important;

    border-radius: 8px !important;

    padding: 25px !important;

    margin: 0 !important;

    box-sizing: border-box !important;

}


/* =====================================================
   TITLE
===================================================== */

.payment-box h6 {

    color: white !important;

    font-size: 18px !important;

    font-weight: 600 !important;

    margin: 0 0 20px 0 !important;

}


/* =====================================================
   FORM SECTION
===================================================== */

.data-box {

    width: 100% !important;

    margin-top: 20px !important;

    padding: 20px !important;

    background: #20232d !important;

    border-radius: 8px !important;

    box-sizing: border-box !important;

}


/* =====================================================
   SECTION TITLE
===================================================== */

.data-box h6 {

    color: white !important;

    font-size: 17px !important;

    font-weight: 600 !important;

    margin-bottom: 20px !important;

}


/* =====================================================
   LABEL
===================================================== */

.payment-box label {

    display: block !important;

    color: white !important;

    font-size: 14px !important;

    font-weight: 600 !important;

    margin-bottom: 8px !important;

}


/* =====================================================
   INPUT
===================================================== */

.payment-box input,
.payment-box select {

    width: 100% !important;

    height: 45px !important;

    padding: 10px 12px !important;

    background: white !important;

    color: #222 !important;

    border: 1px solid #343946 !important;

    border-radius: 5px !important;

    outline: none !important;

    box-sizing: border-box !important;

}


/* =====================================================
   INPUT FOCUS
===================================================== */

.payment-box input:focus,
.payment-box select:focus {

    border-color: #86b7fe !important;

    box-shadow:
        0 0 0 3px
        rgba(13,110,253,.15) !important;

}


/* =====================================================
   AUTO INPUT
===================================================== */

.auto-box {

    background: #e9ecef !important;

    color: #495057 !important;

    cursor: not-allowed !important;

}


/* =====================================================
   SAVE BUTTON
===================================================== */

.save-btn {

    width: 100% !important;

    min-height: 48px !important;

    font-size: 15px !important;

    font-weight: 600 !important;

}


/* =====================================================
   TABLET
===================================================== */

@media (max-width: 991px) {


    /* =================================================
       SIDEBAR
    ================================================= */

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


    /* =================================================
       CONTENT
    ================================================= */

    .content {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        margin-left: 0 !important;

        padding: 0 !important;

    }


    /* =================================================
       NAVBAR
    ================================================= */

    .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        padding-left: 15px !important;

        padding-right: 15px !important;

    }


    /* =================================================
       PAYMENT
    ================================================= */

    .payment-page {

        padding: 20px !important;

    }


    .payment-box {

        padding: 20px !important;

    }


    .data-box {

        padding: 18px !important;

    }

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 767px) {


    /* =================================================
       SIDEBAR
    ================================================= */

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


    /* =================================================
       CONTENT
    ================================================= */

    .content {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        margin-left: 0 !important;

        padding: 0 !important;

    }


    /* =================================================
       NAVBAR
    ================================================= */

    .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        margin: 0 !important;

        padding-left: 10px !important;

        padding-right: 10px !important;

    }


    /* =================================================
       PAYMENT PAGE
    ================================================= */

    .payment-page {

        width: 100% !important;

        padding: 12px 8px !important;

    }


    .payment-box {

        width: 100% !important;

        padding: 15px !important;

        border-radius: 7px !important;

    }


    .payment-box h6 {

        font-size: 16px !important;

        margin-bottom: 15px !important;

    }


    .data-box {

        margin-top: 15px !important;

        padding: 15px !important;

        border-radius: 7px !important;

    }


    .data-box h6 {

        font-size: 15px !important;

        margin-bottom: 17px !important;

    }


    .payment-box .row {

        margin-left: 0 !important;

        margin-right: 0 !important;

    }


    .payment-box .row > div {

        padding-left: 0 !important;

        padding-right: 0 !important;

        margin-bottom: 15px !important;

    }


    .payment-box input,
    .payment-box select {

        height: 43px !important;

        font-size: 13px !important;

    }


    .save-btn {

        min-height: 46px !important;

        font-size: 14px !important;

    }

}


/* =====================================================
   VERY SMALL MOBILE
===================================================== */

@media (max-width: 400px) {


    .payment-page {

        padding: 10px 5px !important;

    }


    .payment-box {

        padding: 12px !important;

    }


    .data-box {

        padding: 12px !important;

    }

}

</style>


</head>


<body>


<!-- =====================================================
     MAIN CONTAINER
===================================================== -->

<div class="main-container">


    <!-- =================================================
         SIDEBAR
    ================================================= -->

    <?php include("sidebar.php"); ?>


    <!-- =================================================
         MOBILE SIDEBAR OVERLAY
    ================================================= -->

    <div
        class="sidebar-mobile-overlay"
        id="sidebarOverlay"
    ></div>


    <!-- =================================================
         CONTENT START
    ================================================= -->

    <div class="content">


        <!-- =================================================
             NAVBAR
        ================================================= -->

        <?php include("navbar.php"); ?>


        <!-- =================================================
             PAYMENT PAGE
        ================================================= -->

        <div class="payment-page">


            <!-- =================================================
                 PAYMENT BOX
            ================================================= -->

            <div class="payment-box">


                <!-- =================================================
                     TITLE
                ================================================= -->

                <h6>

                    <i
                        class="fa fa-money-bill-wave me-2">
                    </i>

                    Add New Payment

                </h6>


                <!-- =================================================
                     MESSAGE
                ================================================= -->

                <?php if ($message != "") { ?>


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

                <form method="POST">


                    <!-- =================================================
                         PAYMENT INFORMATION
                    ================================================= -->

                    <div class="data-box">


                        <h6>

                            <i
                                class="fa fa-credit-card me-2">
                            </i>

                            Payment Information

                        </h6>


                        <!-- =================================================
                             FIRST ROW
                        ================================================= -->

                        <div class="row">


                            <!-- PAYMENT ID -->

                            <div
                                class="col-md-4 mb-3"
                            >

                                <label>

                                    Payment ID

                                </label>


                                <input
                                    type="text"
                                    value="AUTO"
                                    class="auto-box"
                                    readonly
                                >

                            </div>


                            <!-- PAYMENT DATE -->

                            <div
                                class="col-md-4 mb-3"
                            >

                                <label>

                                    Payment Date

                                </label>


                                <input
                                    type="date"
                                    name="payement_date"
                                    id="payement_date"
                                    value="<?php

                                        echo isset(
                                            $_POST[
                                                "payement_date"
                                            ]
                                        )
                                        ? htmlspecialchars(
                                            $_POST[
                                                "payement_date"
                                            ]
                                        )
                                        : date("Y-m-d");

                                    ?>"
                                    required
                                >

                            </div>


                            <!-- AMOUNT -->

                            <div
                                class="col-md-4 mb-3"
                            >

                                <label>

                                    Amount

                                </label>


                                <input
                                    type="number"
                                    name="amount"
                                    id="amount"
                                    step="0.01"
                                    min="0"
                                    value="<?php

                                        echo isset(
                                            $_POST[
                                                "amount"
                                            ]
                                        )
                                        ? htmlspecialchars(
                                            $_POST[
                                                "amount"
                                            ]
                                        )
                                        : "";

                                    ?>"
                                    placeholder="Enter Amount"
                                    required
                                >

                            </div>


                        </div>


                        <!-- =================================================
                             SECOND ROW
                        ================================================= -->

                        <div class="row">


                            <!-- ORDER ID -->

                            <div
                                class="col-md-6 mb-3"
                            >

                                <label>

                                    Order ID

                                </label>


                                <select
                                    name="order_id"
                                    id="order_id"
                                    required
                                >

                                    <option value="">

                                        -- Select Order --

                                    </option>


                                    <?php foreach (
                                        $orders
                                        as $order
                                    ) { ?>


                                        <option

                                            value="<?php

                                                echo htmlspecialchars(
                                                    $order[
                                                        "order_id"
                                                    ]
                                                );

                                            ?>"

                                            data-customer="<?php

                                                echo htmlspecialchars(
                                                    $order[
                                                        "customer_id"
                                                    ]
                                                );

                                            ?>"

                                            data-date="<?php

                                                echo htmlspecialchars(
                                                    $order[
                                                        "order_date"
                                                    ]
                                                );

                                            ?>"

                                            data-total="<?php

                                                echo htmlspecialchars(
                                                    $order[
                                                        "total_amount"
                                                    ]
                                                );

                                            ?>"

                                            data-status="<?php

                                                echo htmlspecialchars(
                                                    $order[
                                                        "status"
                                                    ]
                                                );

                                            ?>"

                                            <?php

                                            if (
                                                isset(
                                                    $_POST[
                                                        "order_id"
                                                    ]
                                                )
                                                &&
                                                $_POST[
                                                    "order_id"
                                                ]
                                                ==
                                                $order[
                                                    "order_id"
                                                ]
                                            ) {

                                                echo "selected";

                                            }

                                            ?>

                                        >

                                            Order ID:

                                            <?php

                                            echo htmlspecialchars(
                                                $order[
                                                    "order_id"
                                                ]
                                            );

                                            ?>

                                            -

                                            Customer ID:

                                            <?php

                                            echo htmlspecialchars(
                                                $order[
                                                    "customer_id"
                                                ]
                                            );

                                            ?>

                                        </option>


                                    <?php } ?>


                                </select>

                            </div>


                            <!-- PAYMENT METHOD -->

                            <div
                                class="col-md-6 mb-3"
                            >

                                <label>

                                    Payment Method

                                </label>


                                <select
                                    name="payement_method"
                                    id="payement_method"
                                    required
                                >

                                    <option value="">

                                        -- Select Payment Method --

                                    </option>


                                    <option value="Cash">

                                        Cash

                                    </option>


                                    <option value="Card">

                                        Card

                                    </option>


                                    <option value="Bank Transfer">

                                        Bank Transfer

                                    </option>


                                    <option value="JazzCash">

                                        JazzCash

                                    </option>


                                    <option value="EasyPaisa">

                                        EasyPaisa

                                    </option>


                                </select>

                            </div>


                        </div>


                    </div>


                    <!-- =================================================
                         SELECTED ORDER INFORMATION
                    ================================================= -->

                    <div class="data-box">


                        <h6>

                            <i
                                class="fa fa-file-invoice me-2">
                            </i>

                            Selected Order Information

                        </h6>


                        <div class="row">


                            <!-- CUSTOMER ID -->

                            <div
                                class="col-md-3 mb-3"
                            >

                                <label>

                                    Customer ID

                                </label>


                                <input
                                    type="text"
                                    id="customer_id"
                                    class="auto-box"
                                    readonly
                                    placeholder="Automatic"
                                >

                            </div>


                            <!-- ORDER DATE -->

                            <div
                                class="col-md-3 mb-3"
                            >

                                <label>

                                    Order Date

                                </label>


                                <input
                                    type="text"
                                    id="order_date"
                                    class="auto-box"
                                    readonly
                                    placeholder="Automatic"
                                >

                            </div>


                            <!-- ORDER TOTAL -->

                            <div
                                class="col-md-3 mb-3"
                            >

                                <label>

                                    Order Total

                                </label>


                                <input
                                    type="text"
                                    id="order_total"
                                    class="auto-box"
                                    readonly
                                    placeholder="Automatic"
                                >

                            </div>


                            <!-- ORDER STATUS -->

                            <div
                                class="col-md-3 mb-3"
                            >

                                <label>

                                    Order Status

                                </label>


                                <input
                                    type="text"
                                    id="order_status"
                                    class="auto-box"
                                    readonly
                                    placeholder="Automatic"
                                >

                            </div>


                        </div>


                    </div>


                    <!-- =================================================
                         SAVE BUTTON
                    ================================================= -->

                    <div class="mt-4">


                        <button
                            type="submit"
                            class="btn btn-primary save-btn"
                        >

                            <i
                                class="fa fa-save me-2">
                            </i>

                            Save Payment

                        </button>


                    </div>


                </form>


            </div>


        </div>


    </div>


</div>


<!-- =====================================================
     ORDER INFORMATION JAVASCRIPT
===================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        // =================================================
        // ORDERS FROM PHP
        // =================================================

        const orders = <?php

            echo json_encode(
                $orders,
                JSON_HEX_TAG |
                JSON_HEX_APOS |
                JSON_HEX_QUOT |
                JSON_HEX_AMP
            );

        ?>;


        // =================================================
        // ORDER SELECT
        // =================================================

        const orderSelect =
            document.getElementById(
                "order_id"
            );


        // =================================================
        // LOAD ORDER DATA
        // =================================================

        function loadOrderData() {


            if (!orderSelect) {

                return;

            }


            const selectedOrderId =
                orderSelect.value;


            const order =
                orders.find(
                    function (row) {

                        return String(
                            row.order_id
                        ) === String(
                            selectedOrderId
                        );

                    }
                );


            if (order) {


                document.getElementById(
                    "customer_id"
                ).value =
                    order.customer_id || "";


                document.getElementById(
                    "order_date"
                ).value =
                    order.order_date || "";


                document.getElementById(
                    "order_total"
                ).value =
                    order.total_amount || "";


                document.getElementById(
                    "order_status"
                ).value =
                    order.status || "";


            }

            else {


                document.getElementById(
                    "customer_id"
                ).value = "";


                document.getElementById(
                    "order_date"
                ).value = "";


                document.getElementById(
                    "order_total"
                ).value = "";


                document.getElementById(
                    "order_status"
                ).value = "";

            }

        }


        // =================================================
        // ORDER CHANGE
        // =================================================

        if (orderSelect) {

            orderSelect.addEventListener(
                "change",
                loadOrderData
            );

        }


        // =================================================
        // AFTER VALIDATION ERROR
        // =================================================

        if (
            orderSelect &&
            orderSelect.value !== ""
        ) {

            loadOrderData();

        }

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
     BOOTSTRAP
===================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js">
</script>


<!-- =====================================================
     DARKPAN MAIN JS
===================================================== -->

<script
    src="js/main.js">
</script>


<!-- =====================================================
     SIDEBAR FIX
     SAME AS UPPER PAYMENT VIEW
===================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        // =================================================
        // ALL TOGGLERS
        // =================================================

        const togglers =
            document.querySelectorAll(
                ".sidebar-toggler, .navbar-toggler"
            );


        // =================================================
        // SIDEBAR
        // =================================================

        const sidebar =
            document.querySelector(
                ".main-container .sidebar"
            );


        // =================================================
        // OVERLAY
        // =================================================

        const overlay =
            document.getElementById(
                "sidebarOverlay"
            );


        // =================================================
        // SIDEBAR CHECK
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
        // TOGGLE SIDEBAR
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
        // TOGGLER CLICK
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
                        // IGNORE BOOTSTRAP DROPDOWN
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


                            // Close sidebar
                            document.body.classList.remove(
                                "sidebar-open"
                            );


                            // Open page
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


                // Sidebar is not open
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


                // Click on toggler
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


                // Close outside
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
        // WINDOW RESIZE
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