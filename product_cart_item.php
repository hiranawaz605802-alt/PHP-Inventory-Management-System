<?php

include("Database.php");

$db = new Database();
$conn = $db->connection();


// =====================================================
// DATABASE CONNECTION CHECK
// =====================================================

if (!$conn) {

    die("Database Connection Failed");

}


// =====================================================
// ADD CUSTOMER TO SHOPPING CART
// =====================================================

if (isset($_POST["add_to_cart"])) {


    // -------------------------------------------------
    // GET CUSTOMER ID
    // -------------------------------------------------

    $customer_id = isset($_POST["customer_id"])
        ? intval($_POST["customer_id"])
        : 0;


    // -------------------------------------------------
    // CHECK CUSTOMER ID
    // -------------------------------------------------

    if ($customer_id <= 0) {

        echo "<script>

                alert('Please Select Customer');

                window.location.href =
                    'customer_product_view.php';

              </script>";

        exit();

    }


    // =================================================
    // CHECK CUSTOMER EXISTS
    // =================================================

    $check_sql = "

        SELECT customer_id

        FROM entity_asad_customer

        WHERE customer_id = ?

        LIMIT 1

    ";


    $check_stmt = mysqli_prepare(
        $conn,
        $check_sql
    );


    if (!$check_stmt) {

        die(
            "Customer Check Prepare Error: " .
            htmlspecialchars(
                mysqli_error($conn)
            )
        );

    }


    mysqli_stmt_bind_param(
        $check_stmt,
        "i",
        $customer_id
    );


    if (!mysqli_stmt_execute($check_stmt)) {

        die(
            "Customer Check Execute Error: " .
            htmlspecialchars(
                mysqli_stmt_error($check_stmt)
            )
        );

    }


    $check_result =
        mysqli_stmt_get_result(
            $check_stmt
        );


    if (!$check_result) {

        die(
            "Customer Check Result Error: " .
            htmlspecialchars(
                mysqli_stmt_error($check_stmt)
            )
        );

    }


    // -------------------------------------------------
    // CUSTOMER NOT FOUND
    // -------------------------------------------------

    if (
        mysqli_num_rows($check_result) == 0
    ) {

        mysqli_stmt_close(
            $check_stmt
        );


        echo "<script>

                alert(
                    'Selected Customer Does Not Exist'
                );

                window.location.href =
                    'customer_product_view.php';

              </script>";

        exit();

    }


    mysqli_stmt_close(
        $check_stmt
    );


    // =================================================
    // INSERT CUSTOMER INTO SHOPPING CART
    // =================================================

    $insert_sql = "

        INSERT INTO entity_asad_shooping_cart
        (
            customer_id
        )

        VALUES
        (
            ?
        )

    ";


    $insert_stmt =
        mysqli_prepare(
            $conn,
            $insert_sql
        );


    // -------------------------------------------------
    // INSERT PREPARE ERROR
    // -------------------------------------------------

    if (!$insert_stmt) {

        die(

            "<div style='
                background:#dc3545;
                color:#fff;
                padding:25px;
                margin:30px;
                border-radius:10px;
                font-family:Arial;
            '>

                <h3>
                    Cart Insert Prepare Error
                </h3>

                <p>"
                .
                htmlspecialchars(
                    mysqli_error($conn)
                )
                .
                "</p>

            </div>"

        );

    }


    // -------------------------------------------------
    // BIND CUSTOMER ID
    // -------------------------------------------------

    mysqli_stmt_bind_param(
        $insert_stmt,
        "i",
        $customer_id
    );


    // =================================================
    // EXECUTE INSERT
    // =================================================

    if (
        mysqli_stmt_execute(
            $insert_stmt
        )
    ) {


        // ---------------------------------------------
        // GET NEW CART ID
        // ---------------------------------------------

        $cart_id =
            mysqli_insert_id($conn);


        mysqli_stmt_close(
            $insert_stmt
        );


        echo "<script>

                alert(
                    'Customer Added To Shopping Cart Successfully!\\n\\nCart ID: "
                    .
                    $cart_id
                    .
                    "'
                );

                window.location.href =
                    'customer_product_view.php';

              </script>";

        exit();

    }


    // =================================================
    // INSERT ERROR
    // =================================================

    $insert_error =
        mysqli_stmt_error(
            $insert_stmt
        );


    mysqli_stmt_close(
        $insert_stmt
    );


    echo "

    <div style='
        background:#dc3545;
        color:#ffffff;
        padding:25px;
        margin:30px;
        border-radius:10px;
        font-family:Arial,sans-serif;
        max-width:800px;
    '>

        <h3 style='margin-top:0;'>

            ❌ Cart Insert Error

        </h3>


        <p>

            <strong>
                MySQL Error:
            </strong>

        </p>


        <p style='
            background:#ffffff;
            color:#111111;
            padding:15px;
            border-radius:5px;
            word-break:break-word;
        '>

            "
            .
            htmlspecialchars(
                $insert_error
            )
            .
            "

        </p>


        <p>

            <strong>
                Customer ID:
            </strong>

            "
            .
            htmlspecialchars(
                $customer_id
            )
            .
            "

        </p>

    </div>

    ";

    exit();

}


// =====================================================
// GET CUSTOMERS
// =====================================================

$sql = "

    SELECT

        customer_id,
        first_name,
        last_name,
        email,
        phone_number,
        address,
        registration_date

    FROM entity_asad_customer

    ORDER BY customer_id DESC

";


$res = mysqli_query(
    $conn,
    $sql
);


if (!$res) {

    die(

        "Customer Query Error: " .
        htmlspecialchars(
            mysqli_error($conn)
        )

    );

}


// =====================================================
// CUSTOMER ARRAY
// =====================================================

$customers = [];


while (
    $row = mysqli_fetch_assoc($res)
) {

    $customers[] = $row;

}

?>

<!DOCTYPE html>

<html lang="en">

<head>


    <meta charset="utf-8">


    <title>
        DarkPan - Shopping Cart
    </title>


    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >


    <link
        href="img/favicon.ico"
        rel="icon"
    >


    <!-- =================================================
         GOOGLE FONT
    ================================================== -->

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


    <!-- =================================================
         FONT AWESOME
    ================================================== -->

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
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

}


/* =========================================================
   BOX SIZING
========================================================= */

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

    transition:
        left 0.3s ease !important;

}


/* =========================================================
   CONTENT
========================================================= */

.content {

    width:
        calc(100% - 250px) !important;

    max-width:
        calc(100% - 250px) !important;

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


    body.sidebar-collapsed
    .content {

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

    background:
        rgba(0,0,0,0.55);

    z-index: 99998;

}


/* =========================================================
   SHOPPING CART PAGE
========================================================= */

.cart-page {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    margin: 0 !important;

    padding:
        20px 25px 40px !important;

    overflow: hidden !important;

    background: #000 !important;

}


/* =========================================================
   CART BOX
========================================================= */

.cart-box {

    width: 100% !important;

    max-width: 1000px !important;

    margin: 0 auto !important;

    padding: 25px !important;

    background: #191c24 !important;

    border-radius: 10px !important;

    box-sizing: border-box !important;

}


/* =========================================================
   TITLE
========================================================= */

.cart-box > h5 {

    color: #ffffff !important;

    font-size: 22px !important;

    font-weight: 600 !important;

    margin:
        0 0 25px !important;

}


/* =========================================================
   DATA BOX
========================================================= */

.data-box,
.customer-data-box {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    padding: 20px !important;

    background: #20232d !important;

    border-radius: 8px !important;

    box-sizing: border-box !important;

    overflow: hidden !important;

}


.customer-data-box {

    display: none;

    margin-top: 20px !important;

}


/* =========================================================
   BOX TITLES
========================================================= */

.data-box h6,
.customer-data-box h6 {

    color: #ffffff !important;

    font-size: 18px !important;

    font-weight: 600 !important;

    margin:
        0 0 20px !important;

}


/* =========================================================
   LABEL
========================================================= */

.cart-box label {

    display: block !important;

    width: 100% !important;

    color: #ffffff !important;

    font-weight: 600 !important;

    margin-bottom: 8px !important;

}


/* =========================================================
   SELECT + INPUT
========================================================= */

.cart-box select,
.cart-box input {

    display: block !important;

    width: 100% !important;

    max-width: 100% !important;

    height: 45px !important;

    padding:
        8px 12px !important;

    background: #ffffff !important;

    color: #111111 !important;

    border: none !important;

    border-radius: 5px !important;

    outline: none !important;

    box-sizing: border-box !important;

}


/* =========================================================
   TEXTAREA
========================================================= */

.cart-box textarea {

    display: block !important;

    width: 100% !important;

    max-width: 100% !important;

    padding:
        10px 12px !important;

    background: #ffffff !important;

    color: #111111 !important;

    border: none !important;

    border-radius: 5px !important;

    outline: none !important;

    resize: none !important;

    box-sizing: border-box !important;

}


/* =========================================================
   FORM FOCUS
========================================================= */

.cart-box select:focus,
.cart-box input:focus,
.cart-box textarea:focus {

    background: #ffffff !important;

    color: #111111 !important;

    border: none !important;

    outline: none !important;

    box-shadow: none !important;

}


/* =========================================================
   CART BUTTON
========================================================= */

.cart-btn {

    width: 100% !important;

    max-width: 100% !important;

    margin-top: 20px !important;

}


.cart-btn .btn {

    min-height: 45px !important;

    padding:
        10px 25px !important;

    font-size: 16px !important;

    font-weight: 600 !important;

    border-radius: 5px !important;

}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 991px) {


    /* -----------------------------------------------------
       SIDEBAR
    ----------------------------------------------------- */

    .main-container .sidebar {

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


    /* -----------------------------------------------------
       SIDEBAR OPEN
    ----------------------------------------------------- */

    body.sidebar-open
    .main-container
    .sidebar {

        left: 0 !important;

    }


    /* -----------------------------------------------------
       OVERLAY
    ----------------------------------------------------- */

    body.sidebar-open
    .sidebar-mobile-overlay {

        display: block !important;

    }


    /* -----------------------------------------------------
       CONTENT
    ----------------------------------------------------- */

    .content {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        margin-left: 0 !important;

        padding: 0 !important;

    }


    /* -----------------------------------------------------
       NAVBAR
    ----------------------------------------------------- */

    .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        padding-left: 15px !important;

        padding-right: 15px !important;

    }


    /* -----------------------------------------------------
       CART PAGE
    ----------------------------------------------------- */

    .cart-page {

        width: 100% !important;

        max-width: 100% !important;

        padding: 18px !important;

    }


    .cart-box {

        width: 100% !important;

        max-width: 100% !important;

        padding: 20px !important;

    }


    .data-box,
    .customer-data-box {

        width: 100% !important;

        max-width: 100% !important;

        padding: 18px !important;

    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767px) {


    /* -----------------------------------------------------
       SIDEBAR
    ----------------------------------------------------- */

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


    /* -----------------------------------------------------
       CONTENT
    ----------------------------------------------------- */

    .content {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        margin-left: 0 !important;

        padding: 0 !important;

    }


    /* -----------------------------------------------------
       NAVBAR
    ----------------------------------------------------- */

    .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        margin: 0 !important;

        padding-left: 10px !important;

        padding-right: 10px !important;

    }


    /* -----------------------------------------------------
       PAGE
    ----------------------------------------------------- */

    .cart-page {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        padding: 15px !important;

        margin: 0 !important;

        overflow: hidden !important;

    }


    /* -----------------------------------------------------
       CART BOX
    ----------------------------------------------------- */

    .cart-box {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        padding: 18px !important;

        border-radius: 8px !important;

    }


    /* -----------------------------------------------------
       BOX
    ----------------------------------------------------- */

    .data-box,
    .customer-data-box {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        padding: 15px !important;

    }


    /* -----------------------------------------------------
       TITLE
    ----------------------------------------------------- */

    .cart-box > h5 {

        font-size: 20px !important;

        margin-bottom: 20px !important;

    }


    /* -----------------------------------------------------
       BUTTON
    ----------------------------------------------------- */

    .cart-btn .btn {

        width: 100% !important;

    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 575px) {


    .cart-page {

        padding: 10px !important;

    }


    .cart-box {

        padding: 15px !important;

        border-radius: 7px !important;

    }


    .data-box,
    .customer-data-box {

        padding: 12px !important;

        border-radius: 7px !important;

    }


    .cart-box > h5 {

        font-size: 18px !important;

    }


    .data-box h6,
    .customer-data-box h6 {

        font-size: 16px !important;

    }


    .cart-box select,
    .cart-box input {

        height: 43px !important;

        font-size: 13px !important;

    }


}


/* =========================================================
   VERY SMALL MOBILE
========================================================= */

@media (max-width: 400px) {


    .cart-page {

        padding: 7px !important;

    }


    .cart-box {

        padding: 10px !important;

    }


    .data-box,
    .customer-data-box {

        padding: 10px !important;

    }


    .cart-box > h5 {

        font-size: 17px !important;

    }


    .data-box h6,
    .customer-data-box h6 {

        font-size: 15px !important;

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
             CART PAGE
        ================================================== -->

        <div class="cart-page">


            <div class="cart-box">


                <!-- =================================================
                     TITLE
                ================================================== -->

                <h5>

                    <i
                        class="fa fa-shopping-cart me-2"
                    ></i>

                    Shopping Cart

                </h5>


                <!-- =================================================
                     FORM
                ================================================== -->

                <form
                    method="POST"
                    action="shoping_product_insert.php"
                >


                    <!-- =================================================
                         SELECT CUSTOMER
                    ================================================== -->

                    <div class="data-box">


                        <h6>

                            <i
                                class="fa fa-user me-2"
                            ></i>

                            Select Customer

                        </h6>


                        <div class="row">


                            <div
                                class="col-md-12 mb-3"
                            >


                                <label
                                    for="customer_id"
                                >

                                    Select Customer

                                </label>


                                <select
                                    name="customer_id"
                                    id="customer_id"
                                    required
                                >


                                    <option value="">

                                        -- Select Customer --

                                    </option>


                                    <?php foreach (
                                        $customers
                                        as $customer
                                    ) { ?>


                                        <option
                                            value="<?php

                                            echo htmlspecialchars(
                                                $customer[
                                                    "customer_id"
                                                ]
                                            );

                                            ?>"
                                        >


                                            <?php

                                            echo htmlspecialchars(
                                                $customer[
                                                    "customer_id"
                                                ]
                                            );

                                            ?>

                                            -

                                            <?php

                                            echo htmlspecialchars(
                                                $customer[
                                                    "first_name"
                                                ]
                                            );

                                            ?>

                                            <?php

                                            echo htmlspecialchars(
                                                $customer[
                                                    "last_name"
                                                ]
                                            );

                                            ?>


                                        </option>


                                    <?php } ?>


                                </select>


                            </div>


                        </div>


                    </div>


                    <!-- =================================================
                         CUSTOMER INFORMATION
                    ================================================== -->

                    <div
                        id="customerDataBox"
                        class="customer-data-box"
                    >


                        <h6>

                            <i
                                class="fa fa-user-circle me-2"
                            ></i>

                            Customer Information

                        </h6>


                        <div class="row">


                            <!-- CUSTOMER ID -->

                            <div
                                class="col-md-6 mb-3"
                            >

                                <label>
                                    Customer ID
                                </label>


                                <input
                                    type="text"
                                    id="show_customer_id"
                                    readonly
                                >

                            </div>


                            <!-- FIRST NAME -->

                            <div
                                class="col-md-6 mb-3"
                            >

                                <label>
                                    First Name
                                </label>


                                <input
                                    type="text"
                                    id="show_first_name"
                                    readonly
                                >

                            </div>


                            <!-- LAST NAME -->

                            <div
                                class="col-md-6 mb-3"
                            >

                                <label>
                                    Last Name
                                </label>


                                <input
                                    type="text"
                                    id="show_last_name"
                                    readonly
                                >

                            </div>


                            <!-- EMAIL -->

                            <div
                                class="col-md-6 mb-3"
                            >

                                <label>
                                    Email
                                </label>


                                <input
                                    type="text"
                                    id="show_email"
                                    readonly
                                >

                            </div>


                            <!-- PHONE -->

                            <div
                                class="col-md-6 mb-3"
                            >

                                <label>
                                    Phone Number
                                </label>


                                <input
                                    type="text"
                                    id="show_phone_number"
                                    readonly
                                >

                            </div>


                            <!-- REGISTRATION DATE -->

                            <div
                                class="col-md-6 mb-3"
                            >

                                <label>
                                    Registration Date
                                </label>


                                <input
                                    type="text"
                                    id="show_registration_date"
                                    readonly
                                >

                            </div>


                            <!-- ADDRESS -->

                            <div
                                class="col-md-12 mb-3"
                            >

                                <label>
                                    Address
                                </label>


                                <textarea
                                    id="show_address"
                                    rows="3"
                                    readonly
                                ></textarea>


                            </div>


                        </div>


                    </div>


                    <!-- =================================================
                         ADD TO CART
                    ================================================== -->

                    <div class="cart-btn">


                        <button
                            type="submit"
                            name="add_to_cart"
                            value="1"
                            class="btn btn-success"
                        >


                            <i
                                class="fa fa-cart-plus me-2"
                            ></i>


                            Add To Cart


                        </button>


                    </div>


                </form>


            </div>


        </div>


    </div>


</div>


<!-- =====================================================
     CUSTOMER DATA
===================================================== -->

<script>

const customers = <?php

echo json_encode(
    $customers,
    JSON_HEX_TAG |
    JSON_HEX_APOS |
    JSON_HEX_QUOT |
    JSON_HEX_AMP
);

?>;


const customerSelect =
    document.getElementById(
        "customer_id"
    );


const customerBox =
    document.getElementById(
        "customerDataBox"
    );


// =====================================================
// CUSTOMER SELECT
// =====================================================

if (customerSelect) {


    customerSelect.addEventListener(
        "change",
        function () {


            const selectedId =
                this.value;


            // -------------------------------------------------
            // NO CUSTOMER
            // -------------------------------------------------

            if (selectedId === "") {


                customerBox.style.display =
                    "none";


                clearCustomer();


                return;

            }


            // -------------------------------------------------
            // FIND CUSTOMER
            // -------------------------------------------------

            const customer =
                customers.find(
                    function (item) {

                        return String(
                            item.customer_id
                        ) === String(
                            selectedId
                        );

                    }
                );


            // -------------------------------------------------
            // CUSTOMER FOUND
            // -------------------------------------------------

            if (customer) {


                customerBox.style.display =
                    "block";


                document.getElementById(
                    "show_customer_id"
                ).value =
                    customer.customer_id || "";


                document.getElementById(
                    "show_first_name"
                ).value =
                    customer.first_name || "";


                document.getElementById(
                    "show_last_name"
                ).value =
                    customer.last_name || "";


                document.getElementById(
                    "show_email"
                ).value =
                    customer.email || "";


                document.getElementById(
                    "show_phone_number"
                ).value =
                    customer.phone_number || "";


                document.getElementById(
                    "show_registration_date"
                ).value =
                    customer.registration_date || "";


                document.getElementById(
                    "show_address"
                ).value =
                    customer.address || "";

            }


        }
    );

}


// =====================================================
// CLEAR CUSTOMER
// =====================================================

function clearCustomer() {


    document.getElementById(
        "show_customer_id"
    ).value = "";


    document.getElementById(
        "show_first_name"
    ).value = "";


    document.getElementById(
        "show_last_name"
    ).value = "";


    document.getElementById(
        "show_email"
    ).value = "";


    document.getElementById(
        "show_phone_number"
    ).value = "";


    document.getElementById(
        "show_registration_date"
    ).value = "";


    document.getElementById(
        "show_address"
    ).value = "";

}

</script>


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
     SIDEBAR FIX
===================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        // =================================================
        // FIND TOGGLERS
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
        // SIDEBAR TOGGLE
        // =================================================

        function toggleSidebar(event) {


            if (event) {

                event.preventDefault();

                event.stopPropagation();

            }


            // -------------------------------------------------
            // MOBILE / TABLET
            // -------------------------------------------------

            if (
                window.innerWidth <= 991
            ) {


                document.body.classList.toggle(
                    "sidebar-open"
                );


                document.body.classList.remove(
                    "sidebar-collapsed"
                );


            }


            // -------------------------------------------------
            // DESKTOP
            // -------------------------------------------------

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
        // ADD TOGGLE EVENTS
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
            sidebar.querySelectorAll(
                "a"
            );


        sidebarLinks.forEach(
            function (link) {


                link.addEventListener(
                    "click",
                    function (event) {


                        const href =
                            link.getAttribute(
                                "href"
                            );


                        // -------------------------------------------------
                        // IGNORE EMPTY LINKS
                        // -------------------------------------------------

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


                        // -------------------------------------------------
                        // IGNORE DROPDOWN BUTTONS
                        // -------------------------------------------------

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


                        // -------------------------------------------------
                        // CLOSE SIDEBAR
                        // -------------------------------------------------

                        document.body.classList.remove(
                            "sidebar-open"
                        );


                        // -------------------------------------------------
                        // MOBILE / TABLET
                        // -------------------------------------------------

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


                // -------------------------------------------------
                // DESKTOP
                // -------------------------------------------------

                if (
                    window.innerWidth > 991
                ) {

                    return;

                }


                // -------------------------------------------------
                // SIDEBAR NOT OPEN
                // -------------------------------------------------

                if (
                    !document.body.classList.contains(
                        "sidebar-open"
                    )
                ) {

                    return;

                }


                // -------------------------------------------------
                // CHECK SIDEBAR CLICK
                // -------------------------------------------------

                const clickedInsideSidebar =
                    sidebar.contains(
                        event.target
                    );


                // -------------------------------------------------
                // CHECK TOGGLER CLICK
                // -------------------------------------------------

                let clickedToggler =
                    false;


                togglers.forEach(
                    function (toggler) {


                        if (
                            toggler.contains(
                                event.target
                            )
                        ) {

                            clickedToggler =
                                true;

                        }


                    }
                );


                // -------------------------------------------------
                // CLOSE ON OUTSIDE CLICK
                // -------------------------------------------------

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


            }
        );


    }
);

</script>


</body>

</html>