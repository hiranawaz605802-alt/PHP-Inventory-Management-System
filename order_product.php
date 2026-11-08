
<?php

include_once("Database.php");

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
// SAVE ORDER
// =====================================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // -------------------------------------------------
    // CART ID
    // -------------------------------------------------

    $cart_id = isset($_POST["cart_id"])
        ? intval($_POST["cart_id"])
        : 0;


    // -------------------------------------------------
    // TOTAL AMOUNT
    // -------------------------------------------------

    $total_amount = isset($_POST["total_amount"])
        ? floatval($_POST["total_amount"])
        : 0;


    // -------------------------------------------------
    // STATUS
    // -------------------------------------------------

    $status = isset($_POST["status"])
        ? trim($_POST["status"])
        : "";


    // =================================================
    // VALIDATION
    // =================================================

    if ($cart_id <= 0) {

        $message = "Please select Shopping Cart.";
        $message_type = "danger";

    } elseif ($total_amount <= 0) {

        $message = "Please enter Total Amount.";
        $message_type = "danger";

    } elseif ($status == "") {

        $message = "Please select Order Status.";
        $message_type = "danger";

    } else {

        // =================================================
        // GET CUSTOMER ID FROM SHOPPING CART
        // =================================================

        $cart_check_sql = "
            SELECT
                cart_id,
                customer_id
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
                "Shopping Cart Error: " .
                mysqli_error($conn);

            $message_type = "danger";

        } elseif (mysqli_num_rows($cart_check) == 0) {

            $message =
                "Selected Shopping Cart does not exist.";

            $message_type = "danger";

        } else {

            // =================================================
            // GET CART DATA
            // =================================================

            $cart_data = mysqli_fetch_assoc($cart_check);

            $customer_id =
                intval($cart_data["customer_id"]);


            // =================================================
            // ORDER DATE
            // =================================================

            $order_date = date("Y-m-d");


            // =================================================
            // SAFE STATUS
            // =================================================

            $safe_status = mysqli_real_escape_string(
                $conn,
                $status
            );


            // =================================================
            // INSERT ORDER
            // =================================================

            $insert_sql = "
                INSERT INTO entity_asad_order
                (
                    customer_id,
                    order_date,
                    total_amount,
                    status
                )
                VALUES
                (
                    $customer_id,
                    '$order_date',
                    $total_amount,
                    '$safe_status'
                )
            ";


            $insert_result = mysqli_query(
                $conn,
                $insert_sql
            );


            if ($insert_result) {

                header(
                    "Location: order_product_view.php"
                );

                exit;

            } else {

                $message =
                    "Order Insert Error: " .
                    mysqli_error($conn);

                $message_type = "danger";
            }
        }
    }
}


// =====================================================
// GET SHOPPING CARTS
// =====================================================

$cart_sql = "
    SELECT
        cart_id,
        customer_id
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


while ($row = mysqli_fetch_assoc($cart_result)) {

    $shopping_carts[] = $row;
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <title>Add Order</title>

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="keywords"
        content=""
    >

    <meta
        name="description"
        content=""
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
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Nunito:wght@600;700;800&display=swap"
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
         DARKPAN STYLE
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


/* =========================================================
MAIN CONTAINER
========================================================= */

.main-page-container {

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
SAME AS CUSTOMER PURCHASE DETAILS PAGE
========================================================= */

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
BACK TO TOP
========================================================= */

.back-to-top {

    display: none !important;

    visibility: hidden !important;

    opacity: 0 !important;
}


/* =========================================================
ORDER PAGE
========================================================= */

.order-page {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    margin: 0 !important;

    padding: 18px !important;

    overflow: hidden !important;
}


/* =========================================================
ORDER BOX
========================================================= */

.order-box {

    width: 100% !important;

    max-width: 1100px !important;

    margin: 0 auto !important;

    background: #191c24 !important;

    border-radius: 7px !important;

    padding: 25px !important;

    box-sizing: border-box !important;
}


/* =========================================================
TITLE
========================================================= */

.order-title {

    color: #ffffff !important;

    font-size: 22px;

    font-weight: 700;

    margin: 0;
}


/* =========================================================
LABEL
========================================================= */

.order-box label {

    color: #ffffff !important;

    font-size: 14px;

    font-weight: 600;

    margin-bottom: 7px;

    display: block;
}


.order-box label small {

    color: #adb5bd !important;

    font-weight: 400;
}


/* =========================================================
INPUT + SELECT
========================================================= */

.order-box input,
.order-box select {

    width: 100% !important;

    height: 43px !important;

    background: #ffffff !important;

    color: #212529 !important;

    border: 1px solid #ced4da !important;

    border-radius: 4px !important;

    padding: 8px 12px !important;

    box-sizing: border-box !important;

    outline: none !important;
}


/* =========================================================
INPUT FOCUS
========================================================= */

.order-box input:focus,
.order-box select:focus {

    border-color: #86b7fe !important;

    box-shadow:
        0 0 0 .15rem
        rgba(13, 110, 253, .15) !important;
}


/* =========================================================
AUTOMATIC INPUT
========================================================= */

.auto-box {

    background: #e9ecef !important;

    color: #495057 !important;

    cursor: not-allowed !important;
}


/* =========================================================
DATA BOX
========================================================= */

.data-box {

    width: 100% !important;

    margin-top: 20px !important;

    padding: 20px !important;

    background: #20232d !important;

    border-radius: 7px !important;

    box-sizing: border-box !important;
}


.data-box:first-of-type {

    margin-top: 0 !important;
}


/* =========================================================
DATA BOX HEADING
========================================================= */

.data-box-heading {

    color: #ffffff !important;

    font-size: 17px;

    font-weight: 600;

    margin: 0 0 20px 0;
}


/* =========================================================
BOOTSTRAP ROW
========================================================= */

.order-box .row {

    margin-left: -10px;

    margin-right: -10px;
}


.order-box .row > div {

    padding-left: 10px;

    padding-right: 10px;
}


/* =========================================================
SAVE BUTTON
========================================================= */

.save-btn {

    width: 100% !important;

    min-height: 45px !important;

    font-size: 15px;

    font-weight: 600;

    border-radius: 4px;
}


/* =========================================================
ALERT
========================================================= */

.order-alert {

    border-radius: 5px;

    margin-bottom: 20px;
}


/* =========================================================
TABLET / MOBILE
SAME SIDEBAR SYSTEM
========================================================= */

@media (max-width: 991px) {

    /* -----------------------------------------------
       SIDEBAR
    ----------------------------------------------- */

    .main-page-container .sidebar {

        width: 250px !important;

        min-width: 250px !important;

        height: 100vh !important;

        position: fixed !important;

        top: 0 !important;

        left: -250px !important;

        bottom: 0 !important;

        z-index: 99999 !important;

        transition: left 0.3s ease !important;

        overflow-y: auto !important;

        overflow-x: hidden !important;
    }


    /* -----------------------------------------------
       SIDEBAR OPEN
    ----------------------------------------------- */

    body.sidebar-open
    .main-page-container
    .sidebar {

        left: 0 !important;
    }


    /* -----------------------------------------------
       OVERLAY
    ----------------------------------------------- */

    body.sidebar-open
    .sidebar-mobile-overlay {

        display: block !important;
    }


    /* -----------------------------------------------
       CONTENT
    ----------------------------------------------- */

    .content {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        margin-left: 0 !important;

        padding: 0 !important;
    }


    /* -----------------------------------------------
       NAVBAR
    ----------------------------------------------- */

    .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        padding-left: 15px !important;

        padding-right: 15px !important;
    }


    /* -----------------------------------------------
       ORDER PAGE
    ----------------------------------------------- */

    .order-page {

        width: 100% !important;

        max-width: 100% !important;

        padding: 15px !important;
    }


    .order-box {

        max-width: 100% !important;
    }


    /* -----------------------------------------------
       FOOTER
    ----------------------------------------------- */

    .content > .container-fluid {

        max-width: 100% !important;
    }
}


/* =========================================================
TABLET
========================================================= */

@media (min-width: 768px) and (max-width: 991px) {

    .order-page {

        padding: 15px !important;
    }


    .order-box {

        padding: 18px !important;
    }


    .data-box {

        padding: 16px !important;
    }
}


/* =========================================================
MOBILE
========================================================= */

@media (max-width: 767px) {

    .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        margin: 0 !important;

        padding-left: 10px !important;

        padding-right: 10px !important;
    }


    .order-page {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        padding: 8px !important;

        margin: 0 !important;

        overflow: hidden !important;
    }


    .order-box {

        width: 100% !important;

        max-width: 100% !important;

        padding: 18px !important;

        border-radius: 7px !important;
    }


    .data-box {

        width: 100% !important;

        max-width: 100% !important;

        padding: 15px !important;

        margin-top: 15px !important;

        border-radius: 7px !important;
    }


    .order-title {

        font-size: 19px !important;
    }


    .data-box-heading {

        font-size: 16px !important;
    }


    .order-box .row {

        margin-left: -6px;

        margin-right: -6px;
    }


    .order-box .row > div {

        padding-left: 6px;

        padding-right: 6px;
    }
}


/* =========================================================
SMALL MOBILE
========================================================= */

@media (max-width: 575px) {

    .order-page {

        padding: 6px !important;
    }


    .order-box {

        padding: 12px !important;

        border-radius: 6px !important;
    }


    .data-box {

        padding: 12px !important;

        margin-top: 12px !important;
    }


    .order-title {

        font-size: 18px !important;
    }


    .order-box label {

        font-size: 13px !important;
    }


    .order-box input,
    .order-box select {

        height: 42px !important;

        font-size: 13px !important;
    }


    .order-box .row {

        margin-left: 0 !important;

        margin-right: 0 !important;
    }


    .order-box .row > div {

        padding-left: 0 !important;

        padding-right: 0 !important;
    }


    .save-btn {

        min-height: 44px !important;
    }
}


/* =========================================================
VERY SMALL MOBILE
========================================================= */

@media (max-width: 400px) {

    .order-page {

        padding: 5px !important;
    }


    .order-box {

        padding: 10px !important;
    }


    .data-box {

        padding: 10px !important;
    }


    .order-title {

        font-size: 17px !important;
    }
}

</style>

</head>


<body>


<!-- =====================================================
     MAIN WRAPPER
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
             ORDER CONTENT
        ================================================== -->

        <div class="order-page">


            <!-- =================================================
                 ORDER BOX
            ================================================= -->

            <div class="order-box">


                <!-- =================================================
                     TITLE
                ================================================= -->

                <div
                    class="d-flex align-items-center justify-content-between mb-4"
                >

                    <h5 class="order-title">

                        <i class="fa fa-shopping-cart me-2"></i>

                        Add New Order

                    </h5>

                </div>


                <!-- =================================================
                     MESSAGE
                ================================================= -->

                <?php if ($message != "") { ?>

                    <div
                        class="alert alert-<?php echo htmlspecialchars($message_type); ?> order-alert"
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
                         SHOPPING CART
                    ================================================= -->

                    <div class="data-box">


                        <h6 class="data-box-heading">

                            <i class="fa fa-shopping-cart me-2"></i>

                            Select Shopping Cart

                        </h6>


                        <div class="row">


                            <!-- CART ID -->

                            <div class="col-md-6 mb-3">

                                <label for="cart_id">

                                    Shopping Cart

                                </label>


                                <select
                                    name="cart_id"
                                    id="cart_id"
                                    required
                                >

                                    <option value="">

                                        -- Select Shopping Cart --

                                    </option>


                                    <?php foreach ($shopping_carts as $cart) { ?>

                                        <option
                                            value="<?php echo htmlspecialchars($cart["cart_id"]); ?>"
                                        >

                                            Cart ID:
                                            <?php echo htmlspecialchars($cart["cart_id"]); ?>

                                            -

                                            Customer ID:
                                            <?php echo htmlspecialchars($cart["customer_id"]); ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>


                            <!-- CUSTOMER ID -->

                            <div class="col-md-6 mb-3">

                                <label for="customer_id">

                                    Customer ID

                                    <small>
                                        (Automatic)
                                    </small>

                                </label>


                                <input
                                    type="text"
                                    name="customer_id"
                                    id="customer_id"
                                    class="auto-box"
                                    readonly
                                    placeholder="Customer ID"
                                >

                            </div>


                        </div>


                    </div>


                    <!-- =================================================
                         ORDER INFORMATION
                    ================================================== -->

                    <div class="data-box">


                        <h6 class="data-box-heading">

                            <i class="fa fa-file-invoice me-2"></i>

                            Order Information

                        </h6>


                        <!-- FIRST ROW -->

                        <div class="row">


                            <!-- ORDER ID -->

                            <div class="col-md-4 mb-3">

                                <label>

                                    Order ID

                                </label>


                                <input
                                    type="text"
                                    value="AUTO"
                                    class="auto-box"
                                    readonly
                                >

                            </div>


                            <!-- ORDER DATE -->

                            <div class="col-md-4 mb-3">

                                <label>

                                    Order Date

                                    <small>
                                        (Automatic)
                                    </small>

                                </label>


                                <input
                                    type="text"
                                    name="order_date"
                                    id="order_date"
                                    value="<?php echo date("Y-m-d"); ?>"
                                    class="auto-box"
                                    readonly
                                >

                            </div>


                            <!-- TOTAL AMOUNT -->

                            <div class="col-md-4 mb-3">

                                <label for="total_amount">

                                    Total Amount

                                </label>


                                <input
                                    type="number"
                                    name="total_amount"
                                    id="total_amount"
                                    step="0.01"
                                    min="0"
                                    placeholder="Enter Total Amount"
                                    required
                                >

                            </div>


                        </div>


                        <!-- SECOND ROW -->

                        <div class="row">


                            <!-- STATUS -->

                            <div class="col-md-6 mb-3">

                                <label for="status">

                                    Order Status

                                </label>


                                <select
                                    name="status"
                                    id="status"
                                    required
                                >

                                    <option value="">

                                        -- Select Status --

                                    </option>


                                    <option value="Pending">

                                        Pending

                                    </option>


                                    <option value="Processing">

                                        Processing

                                    </option>


                                    <option value="Completed">

                                        Completed

                                    </option>


                                    <option value="Cancelled">

                                        Cancelled

                                    </option>

                                </select>

                            </div>


                            <!-- CUSTOMER INFORMATION -->

                            <div class="col-md-6 mb-3">

                                <label for="customer_info">

                                    Customer Information

                                </label>


                                <input
                                    type="text"
                                    id="customer_info"
                                    class="auto-box"
                                    readonly
                                    placeholder="Select Shopping Cart"
                                >

                            </div>


                        </div>


                    </div>


                    <!-- =================================================
                         SAVE ORDER
                    ================================================== -->

                    <div class="mt-4">

                        <button
                            type="submit"
                            class="btn btn-primary save-btn"
                        >

                            <i class="fa fa-save me-2"></i>

                            Save Order

                        </button>

                    </div>


                </form>


            </div>


        </div>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <div class="container-fluid pt-4 px-4">

            <div class="bg-secondary rounded-top p-4">

                <div class="row">

                    <div
                        class="col-12 col-sm-6 text-center text-sm-start"
                    >

                        &copy;

                        <a
                            href="#"
                            class="text-decoration-none"
                        >
                            Your Site Name
                        </a>,

                        All Right Reserved.

                    </div>


                    <div
                        class="col-12 col-sm-6 text-center text-sm-end"
                    >

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
     JQUERY
===================================================== -->

<script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
></script>


<!-- =====================================================
     BOOTSTRAP JS
===================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
></script>


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
     SHOPPING CART DATA
===================================================== -->

<script>

const shoppingCarts = <?php

    echo json_encode(
        $shopping_carts,
        JSON_HEX_TAG |
        JSON_HEX_APOS |
        JSON_HEX_QUOT |
        JSON_HEX_AMP
    );

?>;


// =====================================================
// SHOPPING CART SELECT
// =====================================================

const cartSelect =
    document.getElementById("cart_id");


if (cartSelect) {

    cartSelect.addEventListener(
        "change",
        function () {

            const selectedCartId =
                this.value;


            const cart =
                shoppingCarts.find(
                    function (row) {

                        return String(
                            row.cart_id
                        ) === String(
                            selectedCartId
                        );

                    }
                );


            if (cart) {

                document.getElementById(
                    "customer_id"
                ).value =
                    cart.customer_id || "";


                document.getElementById(
                    "customer_info"
                ).value =
                    "Customer ID: " +
                    (cart.customer_id || "");

            }

            else {

                document.getElementById(
                    "customer_id"
                ).value = "";


                document.getElementById(
                    "customer_info"
                ).value = "";

            }

        }
    );

}

</script>


<!-- =====================================================
     SIDEBAR FIX
     SAME AS CUSTOMER PURCHASE DETAILS PAGE
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
                ".main-page-container .sidebar"
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
            =================================================
            MOBILE / TABLET
            =================================================
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
            =================================================
            DESKTOP
            =================================================
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
                        CLOSE MOBILE SIDEBAR
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


</body>

</html>
