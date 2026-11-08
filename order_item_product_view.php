
<?php

include("Database.php");

$db = new Database();
$conn = $db->connection();


// =====================================================
// CURRENT PAGE
// =====================================================

$current_page = basename($_SERVER["PHP_SELF"]);


// =====================================================
// DELETE ORDER ITEM
// =====================================================

if (isset($_GET["delete_id"])) {

    $delete_id = (int) $_GET["delete_id"];

    if ($delete_id > 0) {

        $delete_sql = "
            DELETE FROM entity_asad_order_item
            WHERE order_item_id = $delete_id
        ";

        if (mysqli_query($conn, $delete_sql)) {

            echo "<script>
                alert('Order Item Deleted Successfully');
                window.location.href='" . htmlspecialchars($current_page, ENT_QUOTES) . "';
            </script>";

            exit();

        } else {

            die(
                "Delete Error: " .
                mysqli_error($conn)
            );
        }
    }
}


// =====================================================
// UPDATE ORDER ITEM
// =====================================================

if (isset($_POST["update_order_item"])) {

    $order_item_id = isset($_POST["order_item_id"])
        ? (int) $_POST["order_item_id"]
        : 0;

    $order_id = isset($_POST["order_id"])
        ? (int) $_POST["order_id"]
        : 0;

    $product_id = isset($_POST["product_id"])
        ? (int) $_POST["product_id"]
        : 0;

    $quantity = isset($_POST["quantity"])
        ? (int) $_POST["quantity"]
        : 0;

    $until_price = isset($_POST["until_price"])
        ? (float) $_POST["until_price"]
        : 0;


    // =================================================
    // BASIC VALIDATION
    // =================================================

    if ($order_item_id <= 0) {
        die("Invalid Order Item ID.");
    }

    if ($order_id <= 0) {
        die("Please enter valid Order ID.");
    }

    if ($product_id <= 0) {
        die("Please enter valid Product ID.");
    }

    if ($quantity <= 0) {
        die("Please enter valid Quantity.");
    }

    if ($until_price <= 0) {
        die("Please enter valid Unit Price.");
    }


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

        die(
            "Order Check Error: " .
            mysqli_error($conn)
        );
    }

    if (mysqli_num_rows($order_check_result) == 0) {

        die("Selected Order ID does not exist.");
    }


    // =================================================
    // CHECK PRODUCT
    // =================================================

    $product_check_sql = "
        SELECT id
        FROM entity_asad_product
        WHERE id = $product_id
        LIMIT 1
    ";

    $product_check_result = mysqli_query(
        $conn,
        $product_check_sql
    );

    if (!$product_check_result) {

        die(
            "Product Check Error: " .
            mysqli_error($conn)
        );
    }

    if (mysqli_num_rows($product_check_result) == 0) {

        die("Selected Product ID does not exist.");
    }


    // =================================================
    // PRICE FORMAT
    // =================================================

    $until_price_sql = number_format(
        $until_price,
        2,
        ".",
        ""
    );


    // =================================================
    // UPDATE
    // =================================================

    $update_sql = "
        UPDATE entity_asad_order_item
        SET
            order_id = $order_id,
            product_id = $product_id,
            quantity = $quantity,
            until_price = $until_price_sql
        WHERE order_item_id = $order_item_id
    ";


    if (mysqli_query($conn, $update_sql)) {

        echo "<script>
            alert('Order Item Updated Successfully');
            window.location.href='" . htmlspecialchars($current_page, ENT_QUOTES) . "';
        </script>";

        exit();

    } else {

        die(
            "Update Error: " .
            mysqli_error($conn)
        );
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
                order_item_id,
                order_id,
                product_id,
                quantity,
                until_price
            FROM entity_asad_order_item
            WHERE order_item_id = $edit_id
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

            $edit_record = mysqli_fetch_assoc(
                $edit_result
            );
        }
    }
}


// =====================================================
// GET ALL ORDER ITEMS
// =====================================================

$sql = "
    SELECT
        order_item_id,
        order_id,
        product_id,
        quantity,
        until_price
    FROM entity_asad_order_item
    ORDER BY order_item_id DESC
";

$result = mysqli_query(
    $conn,
    $sql
);

if (!$result) {

    die(
        "Order Item View Error: " .
        mysqli_error($conn)
    );
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <title>Order Item View</title>

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <link
        href="img/favicon.ico"
        rel="icon"
    >


    <!-- GOOGLE FONT -->

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


    <!-- FONT AWESOME -->

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
        rel="stylesheet"
    >


    <!-- BOOTSTRAP -->

    <link
        href="css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- DARKPAN -->

    <link
        href="css/style.css"
        rel="stylesheet"
    >


<style>

/* =========================================================
GLOBAL
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
MAIN PAGE
========================================================= */

.main-page-container {

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
.sidebar-toggle,
.navbar-toggler,
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
    .main-page-container
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
MOBILE OVERLAY
========================================================= */

#sidebarOverlay {

    display: none;

    position: fixed;

    inset: 0;

    width: 100vw;
    height: 100vh;

    background:
        rgba(0,0,0,0.55);

    z-index: 99998 !important;
}


/* =========================================================
MOBILE / TABLET SIDEBAR
========================================================= */

@media (max-width: 991px) {

    .main-page-container .sidebar {

        width: 250px !important;
        min-width: 250px !important;

        height: 100vh !important;

        position: fixed !important;

        top: 0 !important;
        bottom: 0 !important;

        left: -250px !important;

        z-index: 99999 !important;

        transition:
            left 0.3s ease !important;

        overflow-y: auto !important;
        overflow-x: hidden !important;
    }


    /*
    OPEN SIDEBAR
    */

    body.sidebar-open
    .main-page-container
    .sidebar {

        left: 0 !important;
    }


    /*
    SHOW OVERLAY
    */

    body.sidebar-open
    #sidebarOverlay {

        display: block !important;
    }


    /*
    CONTENT FULL WIDTH
    */

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


    /*
    SIDEBAR LINKS
    */

    .main-page-container .sidebar a,
    .main-page-container .sidebar .nav-link {

        pointer-events: auto !important;

        cursor: pointer !important;
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
PAGE
========================================================= */

.order-item-page {

    width: 100% !important;

    max-width: 100% !important;

    min-height: calc(100vh - 70px);

    padding: 20px !important;

    margin: 0 !important;

    background: #000 !important;

    overflow-x: hidden !important;
}


/* =========================================================
MAIN BOX
========================================================= */

.order-item-box {

    width: 100% !important;

    max-width: 100% !important;

    padding: 20px !important;

    background: #191c24 !important;

    border-radius: 8px !important;

    overflow: hidden !important;
}


/* =========================================================
TITLE
========================================================= */

.order-item-title {

    color: #fff !important;

    font-size: 18px;

    font-weight: 600;

    margin: 0 0 20px 0 !important;
}


/* =========================================================
TABLE WRAPPER
========================================================= */

.order-table-wrapper {

    width: 100% !important;

    max-width: 100% !important;

    overflow-x: auto !important;

    overflow-y: hidden !important;

    -webkit-overflow-scrolling: touch;
}


/* =========================================================
TABLE
========================================================= */

.order-table {

    width: 100% !important;

    min-width: 760px;

    margin: 0 !important;

    border-collapse: collapse;
}


.order-table th {

    color: #8f9bc0 !important;

    font-weight: 600 !important;

    white-space: nowrap !important;

    padding: 13px 10px !important;

    vertical-align: middle !important;

    border-bottom:
        1px solid #454b5a !important;
}


.order-table td {

    color: #fff !important;

    padding: 13px 10px !important;

    vertical-align: middle !important;

    white-space: nowrap;

    border-bottom:
        1px solid #303541 !important;
}


/* =========================================================
BUTTONS
========================================================= */

.edit-btn,
.delete-btn {

    min-width: 78px !important;

    display: inline-flex !important;

    align-items: center !important;

    justify-content: center !important;

    gap: 5px;

    white-space: nowrap !important;

    border-radius: 5px !important;
}


/* =========================================================
EDIT OVERLAY
========================================================= */

.edit-overlay {

    position: fixed;

    top: 0;
    left: 0;
    right: 0;
    bottom: 0;

    width: 100vw;
    height: 100vh;
    height: 100dvh;

    background:
        rgba(0,0,0,0.82);

    display: flex;

    align-items: center;
    justify-content: center;

    padding: 20px;

    z-index: 999999;

    overflow-y: auto;
}


/* =========================================================
EDIT BOX
========================================================= */

.edit-box {

    width: 100%;

    max-width: 850px;

    max-height:
        calc(100dvh - 40px);

    padding: 25px;

    background: #191c24;

    border-radius: 10px;

    overflow-y: auto;

    overflow-x: hidden;

    box-shadow:
        0 10px 40px
        rgba(0,0,0,0.65);
}


/* =========================================================
EDIT HEADER
========================================================= */

.edit-title {

    width: 100%;

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 22px;
}


.edit-title h5 {

    color: #fff !important;

    font-size: 19px;

    font-weight: 600;

    margin: 0 !important;
}


/* =========================================================
CLOSE EDIT
========================================================= */

.close-edit {

    width: 36px;
    height: 36px;

    min-width: 36px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #dc3545;

    color: #fff !important;

    border-radius: 50%;

    text-decoration: none !important;
}


.close-edit:hover {

    background: #bb2d3b;

    color: #fff !important;
}


/* =========================================================
EDIT LABEL
========================================================= */

.edit-box label {

    display: block;

    color: #fff !important;

    font-weight: 600;

    margin-bottom: 7px;
}


/* =========================================================
EDIT INPUT
========================================================= */

.edit-box .form-control {

    width: 100% !important;

    min-height: 45px;

    background: #fff !important;

    color: #111 !important;

    border: none !important;

    border-radius: 5px !important;
}


.edit-box .form-control:focus {

    background: #fff !important;

    color: #111 !important;

    outline: none !important;

    border: none !important;

    box-shadow:
        0 0 0 2px
        rgba(13,110,253,0.30) !important;
}


.edit-box .form-control[readonly] {

    background: #d6d6d6 !important;

    color: #333 !important;

    cursor: not-allowed;
}


/* =========================================================
EDIT BUTTONS
========================================================= */

.edit-buttons {

    width: 100%;

    display: flex;

    align-items: center;

    gap: 10px;

    margin-top: 10px;
}


.edit-buttons .btn {

    min-height: 45px;
}


/* =========================================================
TABLET
========================================================= */

@media (max-width: 991px) {

    .order-item-page {

        padding: 15px !important;
    }


    .order-item-box {

        padding: 16px !important;
    }

}


/* =========================================================
MOBILE TABLE
========================================================= */

@media (max-width: 767px) {

    .order-item-page {

        padding: 10px 8px !important;
    }


    .order-item-box {

        padding: 14px !important;

        border-radius: 7px !important;

        overflow: visible !important;
    }


    .order-item-title {

        font-size: 17px !important;

        margin-bottom: 15px !important;
    }


    .order-table-wrapper {

        width: 100% !important;

        overflow: visible !important;
    }


    .order-table {

        width: 100% !important;

        min-width: 0 !important;

        display: block !important;

        border: 0 !important;
    }


    .order-table thead {

        display: none !important;
    }


    .order-table tbody {

        width: 100% !important;

        display: block !important;
    }


    .order-table tbody tr {

        width: 100% !important;

        display: block !important;

        margin-bottom: 12px !important;

        padding: 8px !important;

        background: #1f232d !important;

        border:
            1px solid #343a49 !important;

        border-radius: 8px !important;
    }


    .order-table tbody td {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        display: flex !important;

        align-items: center !important;

        justify-content: space-between !important;

        gap: 10px !important;

        padding: 10px 6px !important;

        color: #fff !important;

        font-size: 13px !important;

        white-space: normal !important;

        overflow-wrap: anywhere !important;

        word-break: break-word !important;

        border-bottom:
            1px solid #343a49 !important;
    }


    .order-table tbody td:last-child {

        border-bottom: none !important;
    }


    /* =================================================
       MOBILE LABELS
    ================================================= */

    .order-table tbody td::before {

        flex: 0 0 42%;

        max-width: 42%;

        color: #8f9bc0 !important;

        font-weight: 600;

        font-size: 12px;

        line-height: 1.4;
    }


    .order-table tbody td:nth-child(1)::before {

        content: "Order Item ID";
    }


    .order-table tbody td:nth-child(2)::before {

        content: "Order ID";
    }


    .order-table tbody td:nth-child(3)::before {

        content: "Product ID";
    }


    .order-table tbody td:nth-child(4)::before {

        content: "Quantity";
    }


    .order-table tbody td:nth-child(5)::before {

        content: "Unit Price";
    }


    .order-table tbody td:nth-child(6)::before {

        content: "Edit";
    }


    .order-table tbody td:nth-child(7)::before {

        content: "Delete";
    }


    /* =================================================
       MOBILE BUTTONS
    ================================================= */

    .order-table tbody td .edit-btn,
    .order-table tbody td .delete-btn {

        width: 55% !important;

        max-width: 55% !important;

        min-width: 0 !important;

        min-height: 39px !important;

        padding: 7px 10px !important;

        font-size: 12px !important;

        flex-shrink: 0 !important;
    }


    /* =================================================
       NO RECORD
    ================================================= */

    .order-table tbody tr td[colspan="7"] {

        display: block !important;

        width: 100% !important;

        text-align: center !important;

        border: 0 !important;

        padding: 20px 10px !important;
    }


    .order-table tbody tr td[colspan="7"]::before {

        display: none !important;
    }


    /* =================================================
       EDIT POPUP
    ================================================= */

    .edit-overlay {

        padding: 10px !important;

        align-items: center !important;

        justify-content: center !important;
    }


    .edit-box {

        width: 100% !important;

        max-width: 100% !important;

        max-height:
            calc(100dvh - 20px) !important;

        padding: 16px !important;

        border-radius: 8px !important;
    }


    .edit-title h5 {

        font-size: 17px !important;
    }


    .close-edit {

        width: 33px;

        height: 33px;

        min-width: 33px;
    }


    .edit-box .col-md-6,
    .edit-box .col-md-4 {

        width: 100% !important;

        padding-left: 0 !important;

        padding-right: 0 !important;

        margin-bottom: 12px !important;
    }


    .edit-buttons {

        width: 100% !important;

        display: flex !important;

        flex-direction: column !important;

        gap: 9px !important;
    }


    .edit-buttons .btn {

        width: 100% !important;

        min-height: 45px !important;

        margin: 0 !important;
    }

}


/* =========================================================
SMALL MOBILE
========================================================= */

@media (max-width: 575px) {

    .order-item-page {

        padding: 8px 6px !important;
    }


    .order-item-box {

        padding: 10px !important;
    }


    .order-table tbody tr {

        padding: 6px !important;
    }


    .order-table tbody td {

        padding: 9px 5px !important;

        gap: 7px !important;

        font-size: 12px !important;
    }


    .order-table tbody td::before {

        flex: 0 0 40%;

        max-width: 40%;

        font-size: 11px !important;
    }


    .order-table tbody td .edit-btn,
    .order-table tbody td .delete-btn {

        width: 58% !important;

        max-width: 58% !important;

        min-height: 37px !important;

        padding: 6px 8px !important;

        font-size: 11px !important;
    }

}


/* =========================================================
VERY SMALL MOBILE
========================================================= */

@media (max-width: 380px) {

    .order-item-page {

        padding: 7px 5px !important;
    }


    .order-item-box {

        padding: 9px !important;
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
         SIDEBAR
    ================================================== -->

    <?php include("sidebar.php"); ?>


    <!-- =================================================
         MOBILE SIDEBAR OVERLAY
    ================================================== -->

    <div
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
             PAGE
        ================================================== -->

        <div class="order-item-page">


            <div class="order-item-box">


                <!-- =================================================
                     TITLE
                ================================================== -->

                <h6 class="order-item-title">

                    <i class="fa fa-shopping-cart me-2"></i>

                    Order Item

                </h6>


                <!-- =================================================
                     TABLE
                ================================================== -->

                <div class="order-table-wrapper">


                    <table
                        class="table table-dark table-hover order-table"
                    >


                        <thead>

                            <tr>

                                <th>
                                    Order Item ID
                                </th>

                                <th>
                                    Order ID
                                </th>

                                <th>
                                    Product ID
                                </th>

                                <th>
                                    Quantity
                                </th>

                                <th>
                                    Unit Price
                                </th>

                                <th>
                                    Edit
                                </th>

                                <th>
                                    Delete
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php

                        if (mysqli_num_rows($result) > 0) {

                            while (
                                $row =
                                mysqli_fetch_assoc($result)
                            ) {

                        ?>

                            <tr>


                                <!-- ORDER ITEM ID -->

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $row["order_item_id"]
                                    );

                                    ?>

                                </td>


                                <!-- ORDER ID -->

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $row["order_id"]
                                    );

                                    ?>

                                </td>


                                <!-- PRODUCT ID -->

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $row["product_id"]
                                    );

                                    ?>

                                </td>


                                <!-- QUANTITY -->

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $row["quantity"]
                                    );

                                    ?>

                                </td>


                                <!-- UNIT PRICE -->

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        number_format(
                                            (float)
                                            $row["until_price"],
                                            2
                                        )
                                    );

                                    ?>

                                </td>


                                <!-- EDIT -->

                                <td>

                                    <a
                                        href="<?php echo htmlspecialchars($current_page); ?>?edit_id=<?php echo (int)$row["order_item_id"]; ?>"
                                        class="btn btn-primary btn-sm edit-btn"
                                    >

                                        <i class="fa fa-edit"></i>

                                        <span>Edit</span>

                                    </a>

                                </td>


                                <!-- DELETE -->

                                <td>

                                    <a
                                        href="<?php echo htmlspecialchars($current_page); ?>?delete_id=<?php echo (int)$row["order_item_id"]; ?>"
                                        class="btn btn-danger btn-sm delete-btn"
                                        onclick="return confirm('Are you sure you want to delete this Order Item?');"
                                    >

                                        <i class="fa fa-trash"></i>

                                        <span>Delete</span>

                                    </a>

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
                                    class="text-center"
                                >

                                    No Order Item Record Found

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


    <!-- =====================================================
         EDIT POPUP
    ===================================================== -->

    <?php if ($edit_record !== null) { ?>


        <div class="edit-overlay">


            <div class="edit-box">


                <!-- EDIT HEADER -->

                <div class="edit-title">


                    <h5>

                        <i class="fa fa-edit me-2"></i>

                        Edit Order Item

                    </h5>


                    <a
                        href="<?php echo htmlspecialchars($current_page); ?>"
                        class="close-edit"
                        title="Close"
                    >

                        <i class="fa fa-times"></i>

                    </a>


                </div>


                <!-- EDIT FORM -->

                <form
                    method="POST"
                    action="<?php echo htmlspecialchars($current_page); ?>"
                >


                    <!-- HIDDEN ID -->

                    <input
                        type="hidden"
                        name="order_item_id"
                        value="<?php
                            echo htmlspecialchars(
                                $edit_record["order_item_id"]
                            );
                        ?>"
                    >


                    <!-- ORDER ITEM ID + ORDER ID -->

                    <div class="row">


                        <!-- ORDER ITEM ID -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Order Item ID

                            </label>


                            <input
                                type="number"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars(
                                        $edit_record["order_item_id"]
                                    );
                                ?>"
                                readonly
                            >

                        </div>


                        <!-- ORDER ID -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Order ID

                            </label>


                            <input
                                type="number"
                                name="order_id"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars(
                                        $edit_record["order_id"]
                                    );
                                ?>"
                                min="1"
                                required
                            >

                        </div>


                    </div>


                    <!-- PRODUCT + QUANTITY + PRICE -->

                    <div class="row">


                        <!-- PRODUCT ID -->

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Product ID

                            </label>


                            <input
                                type="number"
                                name="product_id"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars(
                                        $edit_record["product_id"]
                                    );
                                ?>"
                                min="1"
                                required
                            >

                        </div>


                        <!-- QUANTITY -->

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Quantity

                            </label>


                            <input
                                type="number"
                                name="quantity"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars(
                                        $edit_record["quantity"]
                                    );
                                ?>"
                                min="1"
                                required
                            >

                        </div>


                        <!-- UNIT PRICE -->

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Unit Price

                            </label>


                            <input
                                type="number"
                                name="until_price"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars(
                                        $edit_record["until_price"]
                                    );
                                ?>"
                                min="0.01"
                                step="0.01"
                                required
                            >

                        </div>


                    </div>


                    <!-- BUTTONS -->

                    <div class="edit-buttons">


                        <button
                            type="submit"
                            name="update_order_item"
                            class="btn btn-success"
                        >

                            <i class="fa fa-save me-1"></i>

                            Update Order Item

                        </button>


                        <a
                            href="<?php echo htmlspecialchars($current_page); ?>"
                            class="btn btn-danger"
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

<script src="js/main.js"></script>


<!-- =====================================================
     SIDEBAR FIX
===================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        /*
        =====================================================
        FIND SIDEBAR TOGGLERS
        =====================================================
        */

        const togglers =
            document.querySelectorAll(
                ".sidebar-toggler, .navbar-toggler, .sidebar-toggle, [data-sidebar-toggle]"
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
        OPEN SIDEBAR
        =====================================================
        */

        function openSidebar() {

            if (
                window.innerWidth <= 991
            ) {

                sidebar.classList.add(
                    "mobile-open"
                );

                document.body.classList.add(
                    "sidebar-open"
                );

                document.body.classList.remove(
                    "sidebar-collapsed"
                );
            }
        }


        /*
        =====================================================
        CLOSE SIDEBAR
        =====================================================
        */

        function closeSidebar() {

            sidebar.classList.remove(
                "mobile-open"
            );

            document.body.classList.remove(
                "sidebar-open"
            );
        }


        /*
        =====================================================
        TOGGLE SIDEBAR
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

            if (
                window.innerWidth <= 991
            ) {

                if (
                    document.body.classList.contains(
                        "sidebar-open"
                    )
                ) {

                    closeSidebar();

                }
                else {

                    openSidebar();

                }

                return;
            }


            /*
            DESKTOP
            */

            document.body.classList.toggle(
                "sidebar-collapsed"
            );

            document.body.classList.remove(
                "sidebar-open"
            );

        }


        /*
        =====================================================
        ADD TOGGLE EVENTS
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
                        IGNORE BOOTSTRAP DROPDOWN LINKS
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
                        MOBILE CLOSE
                        */

                        if (
                            window.innerWidth <= 991
                        ) {

                            closeSidebar();

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
                function (event) {

                    event.preventDefault();

                    event.stopPropagation();

                    closeSidebar();

                },
                true
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


                /*
                DESKTOP PAR OUTSIDE CLICK
                NAHI CHAHIYE
                */

                if (
                    window.innerWidth > 991
                ) {

                    return;
                }


                /*
                SIDEBAR OPEN NAHI
                */

                if (
                    !document.body.classList.contains(
                        "sidebar-open"
                    )
                ) {

                    return;
                }


                /*
                CHECK SIDEBAR
                */

                const clickedInsideSidebar =
                    sidebar.contains(
                        event.target
                    );


                /*
                CHECK TOGGLER
                */

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


                /*
                OUTSIDE CLICK
                */

                if (
                    !clickedInsideSidebar &&
                    !clickedToggler
                ) {

                    closeSidebar();

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

                    closeSidebar();

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
