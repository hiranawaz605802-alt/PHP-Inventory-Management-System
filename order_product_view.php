
<?php

include_once("Database.php");

$db = new Database();
$conn = $db->connection();


// =====================================================
// CURRENT PAGE
// =====================================================

$current_page = "order_product_view.php";


// =====================================================
// MESSAGE
// =====================================================

$message = "";
$message_type = "";


// =====================================================
// DELETE ORDER
// =====================================================

if (isset($_GET["delete_id"])) {

    $delete_id = (int) $_GET["delete_id"];

    if ($delete_id > 0) {

        $delete_sql = "
            DELETE FROM entity_asad_order
            WHERE order_id = $delete_id
        ";

        if (mysqli_query($conn, $delete_sql)) {

            echo "<script>
                    alert('Order Deleted Successfully');
                    window.location.href='$current_page';
                  </script>";

            exit();

        } else {

            $message = "Delete Error: " . mysqli_error($conn);
            $message_type = "danger";
        }

    } else {

        $message = "Invalid Order ID.";
        $message_type = "danger";
    }
}


// =====================================================
// UPDATE ORDER
// =====================================================

if (isset($_POST["update_order"])) {

    $order_id = isset($_POST["order_id"])
        ? (int) $_POST["order_id"]
        : 0;

    $customer_id = isset($_POST["customer_id"])
        ? (int) $_POST["customer_id"]
        : 0;

    $order_date = isset($_POST["order_date"])
        ? mysqli_real_escape_string(
            $conn,
            trim($_POST["order_date"])
        )
        : "";

    $total_amount = isset($_POST["total_amount"])
        ? (float) $_POST["total_amount"]
        : 0;

    $status = isset($_POST["status"])
        ? mysqli_real_escape_string(
            $conn,
            trim($_POST["status"])
        )
        : "";


    // =================================================
    // VALIDATION
    // =================================================

    if ($order_id <= 0) {

        $message = "Invalid Order ID.";
        $message_type = "danger";

    } elseif ($customer_id <= 0) {

        $message = "Please enter Customer ID.";
        $message_type = "danger";

    } elseif ($order_date == "") {

        $message = "Please enter Order Date.";
        $message_type = "danger";

    } elseif ($total_amount < 0) {

        $message = "Total Amount cannot be negative.";
        $message_type = "danger";

    } elseif ($status == "") {

        $message = "Please select Status.";
        $message_type = "danger";

    } else {

        // =================================================
        // CHECK CUSTOMER
        // =================================================

        $customer_check_sql = "
            SELECT customer_id
            FROM entity_asad_customer
            WHERE customer_id = $customer_id
            LIMIT 1
        ";

        $customer_check_result = mysqli_query(
            $conn,
            $customer_check_sql
        );


        if ($customer_check_result === false) {

            $customer_exists = true;

        } else {

            $customer_exists =
                mysqli_num_rows($customer_check_result) > 0;
        }


        if (!$customer_exists) {

            $message = "Customer ID does not exist.";
            $message_type = "danger";

        } else {

            // =================================================
            // UPDATE
            // =================================================

            $update_sql = "
                UPDATE entity_asad_order

                SET
                    customer_id = $customer_id,
                    order_date = '$order_date',
                    total_amount = $total_amount,
                    status = '$status'

                WHERE order_id = $order_id
            ";

            $update_result = mysqli_query(
                $conn,
                $update_sql
            );


            if ($update_result) {

                echo "<script>
                        alert('Order Updated Successfully');
                        window.location.href='$current_page';
                      </script>";

                exit();

            } else {

                $message = "Update Error: " .
                           mysqli_error($conn);

                $message_type = "danger";
            }
        }
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
                order_id,
                customer_id,
                order_date,
                total_amount,
                status

            FROM entity_asad_order

            WHERE order_id = $edit_id

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

            $edit_record =
                mysqli_fetch_assoc($edit_result);
        }
    }
}


// =====================================================
// GET ALL ORDERS
// =====================================================

$sql = "
    SELECT
        order_id,
        customer_id,
        order_date,
        total_amount,
        status

    FROM entity_asad_order

    ORDER BY order_id DESC
";

$result = mysqli_query(
    $conn,
    $sql
);

if (!$result) {

    die(
        "Order View Error: " .
        mysqli_error($conn)
    );
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

<title>Order View</title>


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
SAME AS CUSTOMER PURCHASE DETAILS
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
SIDEBAR SCROLLBAR
========================================================= */

.main-container .sidebar::-webkit-scrollbar {

    width: 5px;
}


.main-container .sidebar::-webkit-scrollbar-track {

    background: transparent;
}


.main-container .sidebar::-webkit-scrollbar-thumb {

    background: rgba(255,255,255,0.15);

    border-radius: 10px;
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

    position: fixed !important;

    inset: 0 !important;

    width: 100vw !important;

    height: 100vh !important;

    background: rgba(0,0,0,0.55);

    z-index: 99998 !important;

    cursor: pointer;
}


/* =========================================================
MAIN ORDER CONTAINER
========================================================= */

.main-order-container {

    width: 100% !important;
    max-width: 100% !important;

    min-width: 0 !important;

    margin: 0 !important;

    padding: 18px !important;

    overflow: hidden !important;
}


/* =========================================================
MESSAGE
========================================================= */

.main-order-container .alert {

    width: 100% !important;

    margin-bottom: 15px !important;

    border-radius: 6px;
}


/* =========================================================
TABLE BOX
========================================================= */

.table-box {

    width: 100% !important;
    max-width: 100% !important;

    min-width: 0 !important;

    background: #191c24;

    border-radius: 8px;

    padding: 18px;

    margin: 0 !important;

    overflow: hidden !important;
}


/* =========================================================
TITLE
========================================================= */

.table-box h5 {

    color: #ffffff;

    font-size: 17px;

    font-weight: 600;

    margin: 0 0 15px 0 !important;

    padding: 0;
}


/* =========================================================
TABLE WRAPPER
========================================================= */

.table-responsive {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    margin: 0 !important;
    padding: 0 !important;

    overflow-x: auto !important;

    overflow-y: hidden !important;

    -webkit-overflow-scrolling: touch;
}


/* =========================================================
DESKTOP TABLE
========================================================= */

.order-table {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 850px !important;

    margin: 0 !important;

    border-collapse: collapse !important;

    table-layout: fixed !important;
}


/* =========================================================
TABLE CELLS
========================================================= */

.order-table th,
.order-table td {

    padding: 11px 8px !important;

    vertical-align: middle !important;

    font-size: 13px !important;
}


/* =========================================================
TABLE HEADER
========================================================= */

.order-table th {

    color: #8f9bc0 !important;

    font-weight: 600 !important;

    white-space: nowrap !important;
}


/* =========================================================
TABLE BODY
========================================================= */

.order-table td {

    color: #8f9bc0 !important;

    white-space: normal !important;

    word-break: break-word;
}


/* =========================================================
COLUMN WIDTH
========================================================= */

.order-table th:nth-child(1),
.order-table td:nth-child(1) {

    width: 10%;
}


.order-table th:nth-child(2),
.order-table td:nth-child(2) {

    width: 14%;
}


.order-table th:nth-child(3),
.order-table td:nth-child(3) {

    width: 17%;
}


.order-table th:nth-child(4),
.order-table td:nth-child(4) {

    width: 16%;
}


.order-table th:nth-child(5),
.order-table td:nth-child(5) {

    width: 13%;
}


.order-table th:nth-child(6),
.order-table td:nth-child(6) {

    width: 30%;
}


/* =========================================================
ACTION
========================================================= */

.action-cell {

    white-space: nowrap !important;
}


.action-btn {

    display: inline-flex !important;

    align-items: center;

    justify-content: center;

    margin-right: 3px;

    margin-bottom: 3px;
}


.edit-btn,
.delete-btn {

    padding: 6px 10px !important;

    font-size: 12px !important;

    white-space: nowrap !important;
}


/* =========================================================
EDIT OVERLAY
========================================================= */

.edit-overlay {

    position: fixed !important;

    inset: 0 !important;

    width: 100% !important;

    height: 100% !important;

    background: rgba(0,0,0,0.78);

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 20px;

    z-index: 999999 !important;

    overflow-y: auto;
}


/* =========================================================
EDIT BOX
========================================================= */

.edit-box {

    width: 100%;

    max-width: 850px;

    background: #191c24;

    border-radius: 10px;

    padding: 25px;

    box-shadow:
        0 10px 40px rgba(0,0,0,0.6);
}


/* =========================================================
EDIT HEADER
========================================================= */

.edit-title {

    width: 100%;

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 20px;
}


.edit-title h5 {

    margin: 0;

    color: #ffffff;

    font-size: 18px;

    font-weight: 600;
}


/* =========================================================
CLOSE EDIT
========================================================= */

.close-edit {

    width: 35px;

    height: 35px;

    min-width: 35px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #dc3545;

    color: #ffffff;

    text-decoration: none;
}


.close-edit:hover {

    color: #ffffff;

    text-decoration: none;

    background: #bb2d3b;
}


/* =========================================================
EDIT LABEL
========================================================= */

.edit-box label {

    display: block;

    color: #ffffff;

    font-weight: 600;

    margin-bottom: 7px;
}


/* =========================================================
EDIT INPUT
========================================================= */

.edit-box input,
.edit-box select {

    width: 100% !important;

    height: 43px !important;

    background: #ffffff !important;

    color: #000000 !important;

    border: none !important;

    border-radius: 5px !important;

    padding: 8px 10px !important;

    outline: none !important;
}


.edit-box input:focus,
.edit-box select:focus {

    box-shadow:
        0 0 0 2px rgba(13,110,253,0.25) !important;
}


.edit-box input[readonly] {

    background: #d6d6d6 !important;

    color: #333333 !important;
}


/* =========================================================
EDIT BUTTONS
========================================================= */

.edit-buttons {

    display: flex;

    align-items: center;

    gap: 8px;

    margin-top: 8px;
}


.update-btn,
.cancel-btn {

    min-width: 110px;
}


/* =========================================================
TABLET
========================================================= */

@media (max-width: 991px) {

    /* SIDEBAR */

    .main-container .sidebar {

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

        overflow-y: auto !important;

        overflow-x: hidden !important;

        transition: left 0.3s ease !important;
    }


    /* OPEN SIDEBAR */

    body.sidebar-open
    .main-container
    .sidebar {

        left: 0 !important;
    }


    /* SHOW OVERLAY */

    body.sidebar-open
    .sidebar-mobile-overlay {

        display: block !important;
    }


    /* CONTENT FULL WIDTH */

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


    /* ORDER */

    .main-order-container {

        padding: 15px !important;
    }


    .table-box {

        padding: 16px !important;
    }


    .order-table {

        min-width: 780px !important;
    }


    .order-table th,
    .order-table td {

        font-size: 12px !important;

        padding: 9px 6px !important;
    }


    /* EDIT */

    .edit-overlay {

        padding: 15px;
    }


    .edit-box {

        max-width: 700px;

        padding: 20px;
    }


    body.sidebar-open {

        overflow: hidden !important;
    }

}


/* =========================================================
MOBILE
========================================================= */

@media (max-width: 767px) {

    /* SIDEBAR */

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


    /* NAVBAR */

    .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        margin: 0 !important;

        padding-left: 10px !important;

        padding-right: 10px !important;
    }


    /* ORDER AREA */

    .main-order-container {

        width: 100% !important;

        max-width: 100% !important;

        padding: 10px 7px !important;
    }


    /* TABLE BOX */

    .table-box {

        width: 100% !important;

        max-width: 100% !important;

        padding: 12px !important;

        border-radius: 7px;
    }


    .table-box h5 {

        font-size: 16px;

        margin-bottom: 12px !important;
    }


    /* MOBILE TABLE */

    .table-responsive {

        width: 100% !important;

        max-width: 100% !important;

        overflow: visible !important;
    }


    .order-table {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        display: block !important;

        table-layout: auto !important;
    }


    .order-table thead {

        display: none !important;
    }


    .order-table tbody {

        display: block !important;

        width: 100% !important;
    }


    .order-table tr {

        display: block !important;

        width: 100% !important;

        background: #1f222b;

        border-radius: 8px;

        margin-bottom: 10px;

        padding: 10px;
    }


    .order-table td {

        display: flex !important;

        align-items: center;

        justify-content: space-between;

        width: 100% !important;

        padding: 7px 4px !important;

        border: none !important;

        font-size: 13px !important;

        text-align: right;

        word-break: break-word;
    }


    .order-table td::before {

        content: attr(data-label);

        color: #8f9bc0;

        font-weight: 600;

        text-align: left;

        margin-right: 12px;

        flex-shrink: 0;
    }


    .order-table td.action-cell {

        display: flex !important;

        justify-content: flex-start !important;

        align-items: flex-start;

        flex-wrap: wrap;

        gap: 5px;

        padding-top: 10px !important;
    }


    .order-table td.action-cell::before {

        width: 100%;

        margin-bottom: 5px;
    }


    .edit-btn,
    .delete-btn {

        font-size: 12px !important;

        padding: 6px 10px !important;

        min-width: 70px;
    }


    /* EDIT POPUP */

    .edit-overlay {

        padding: 8px;

        align-items: center;
    }


    .edit-box {

        width: 100%;

        max-width: 100%;

        padding: 15px;

        border-radius: 8px;
    }


    .edit-title h5 {

        font-size: 16px;
    }


    .close-edit {

        width: 32px;

        height: 32px;

        min-width: 32px;
    }


    .edit-box .row {

        margin-left: 0 !important;

        margin-right: 0 !important;
    }


    .edit-box .col-md-6,
    .edit-box .col-md-4 {

        width: 100% !important;

        padding-left: 0 !important;

        padding-right: 0 !important;

        margin-bottom: 12px;
    }


    .edit-buttons {

        width: 100%;

        flex-direction: column;

        gap: 7px;
    }


    .edit-buttons .btn {

        width: 100%;

        min-height: 42px;
    }

}


/* =========================================================
SMALL MOBILE
========================================================= */

@media (max-width: 575px) {

    .main-order-container {

        padding: 8px 6px !important;
    }


    .table-box {

        padding: 10px !important;
    }


    .order-table td {

        font-size: 12px !important;

        padding: 6px 3px !important;
    }


    .edit-box {

        padding: 13px;
    }

}


/* =========================================================
VERY SMALL MOBILE
========================================================= */

@media (max-width: 400px) {

    .main-order-container {

        padding: 7px 5px !important;
    }


    .table-box {

        padding: 9px !important;
    }


    .order-table td {

        font-size: 11px !important;

        padding: 6px 3px !important;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     MAIN CONTAINER
====================================================== -->

<div class="main-container">


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
         CONTENT
    ================================================== -->

    <div
        class="content"
        id="mainContent"
    >


        <!-- =================================================
             NAVBAR
        ================================================== -->

        <?php include("navbar.php"); ?>


        <!-- =================================================
             MAIN ORDER AREA
        ================================================== -->

        <div class="main-order-container">


            <!-- =================================================
                 MESSAGE
            ================================================== -->

            <?php if ($message != "") { ?>

                <div
                    class="alert alert-<?php echo htmlspecialchars($message_type); ?>"
                >

                    <?php
                    echo htmlspecialchars($message);
                    ?>

                </div>

            <?php } ?>


            <!-- =================================================
                 TABLE BOX
            ================================================== -->

            <div class="table-box">


                <h5>

                    <i class="fa fa-file-invoice me-2"></i>

                    Orders

                </h5>


                <div class="table-responsive">


                    <table
                        class="table table-dark table-hover order-table"
                    >


                        <thead>

                            <tr>

                                <th>
                                    Order ID
                                </th>

                                <th>
                                    Customer ID
                                </th>

                                <th>
                                    Order Date
                                </th>

                                <th>
                                    Total Amount
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php

                        if (mysqli_num_rows($result) > 0) {

                            while ($row = mysqli_fetch_assoc($result)) {

                        ?>

                            <tr>


                                <td data-label="Order ID">

                                    <?php
                                    echo htmlspecialchars(
                                        $row["order_id"]
                                    );
                                    ?>

                                </td>


                                <td data-label="Customer ID">

                                    <?php
                                    echo htmlspecialchars(
                                        $row["customer_id"]
                                    );
                                    ?>

                                </td>


                                <td data-label="Order Date">

                                    <?php
                                    echo htmlspecialchars(
                                        $row["order_date"]
                                    );
                                    ?>

                                </td>


                                <td data-label="Total Amount">

                                    <?php
                                    echo htmlspecialchars(
                                        $row["total_amount"]
                                    );
                                    ?>

                                </td>


                                <td data-label="Status">

                                    <?php
                                    echo htmlspecialchars(
                                        $row["status"]
                                    );
                                    ?>

                                </td>


                                <td
                                    data-label="Action"
                                    class="action-cell"
                                >


                                    <a
                                        href="<?php echo $current_page; ?>?edit_id=<?php echo (int) $row["order_id"]; ?>"
                                        class="btn btn-primary btn-sm edit-btn action-btn"
                                    >

                                        <i class="fa fa-edit"></i>

                                        Edit

                                    </a>


                                    <a
                                        href="<?php echo $current_page; ?>?delete_id=<?php echo (int) $row["order_id"]; ?>"
                                        class="btn btn-danger btn-sm delete-btn action-btn"
                                        onclick="return confirm('Are you sure you want to delete this Order?');"
                                    >

                                        <i class="fa fa-trash"></i>

                                        Delete

                                    </a>


                                </td>


                            </tr>

                        <?php

                            }

                        } else {

                        ?>

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center"
                                >

                                    No Order Record Found

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


    <!-- =================================================
         EDIT POPUP
    ================================================== -->

    <?php if ($edit_record != null) { ?>


        <div class="edit-overlay">


            <div class="edit-box">


                <div class="edit-title">


                    <h5>

                        <i class="fa fa-edit me-2"></i>

                        Edit Order

                    </h5>


                    <a
                        href="<?php echo $current_page; ?>"
                        class="close-edit"
                        title="Close"
                    >

                        <i class="fa fa-times"></i>

                    </a>


                </div>


                <form
                    method="POST"
                    action="<?php echo $current_page; ?>"
                >


                    <input
                        type="hidden"
                        name="order_id"
                        value="<?php
                            echo htmlspecialchars(
                                $edit_record["order_id"]
                            );
                        ?>"
                    >


                    <div class="row">


                        <!-- ORDER ID -->

                        <div class="col-md-6 mb-3">

                            <label>
                                Order ID
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars(
                                        $edit_record["order_id"]
                                    );
                                ?>"
                                readonly
                            >

                        </div>


                        <!-- CUSTOMER ID -->

                        <div class="col-md-6 mb-3">

                            <label>
                                Customer ID
                            </label>

                            <input
                                type="number"
                                name="customer_id"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars(
                                        $edit_record["customer_id"]
                                    );
                                ?>"
                                min="1"
                                required
                            >

                        </div>


                        <!-- ORDER DATE -->

                        <div class="col-md-4 mb-3">

                            <label>
                                Order Date
                            </label>

                            <input
                                type="date"
                                name="order_date"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars(
                                        $edit_record["order_date"]
                                    );
                                ?>"
                                required
                            >

                        </div>


                        <!-- TOTAL -->

                        <div class="col-md-4 mb-3">

                            <label>
                                Total Amount
                            </label>

                            <input
                                type="number"
                                name="total_amount"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars(
                                        $edit_record["total_amount"]
                                    );
                                ?>"
                                step="0.01"
                                min="0"
                                required
                            >

                        </div>


                        <!-- STATUS -->

                        <div class="col-md-4 mb-3">

                            <label>
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-control"
                                required
                            >

                                <option value="">
                                    -- Select Status --
                                </option>


                                <?php

                                $statuses = [
                                    "Pending",
                                    "Processing",
                                    "Completed",
                                    "Cancelled"
                                ];

                                foreach (
                                    $statuses
                                    as $status_option
                                ) {

                                ?>

                                    <option
                                        value="<?php
                                            echo htmlspecialchars(
                                                $status_option
                                            );
                                        ?>"
                                        <?php

                                        if (
                                            $edit_record["status"]
                                            == $status_option
                                        ) {

                                            echo "selected";
                                        }

                                        ?>
                                    >

                                        <?php
                                        echo htmlspecialchars(
                                            $status_option
                                        );
                                        ?>

                                    </option>

                                <?php

                                }

                                ?>

                            </select>

                        </div>


                    </div>


                    <!-- =================================================
                         BUTTONS
                    ================================================== -->

                    <div class="edit-buttons">


                        <button
                            type="submit"
                            name="update_order"
                            class="btn btn-success update-btn"
                        >

                            <i class="fa fa-save me-1"></i>

                            Update Order

                        </button>


                        <a
                            href="<?php echo $current_page; ?>"
                            class="btn btn-danger cancel-btn"
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

<script
    src="js/main.js"
></script>


<!-- =====================================================
     SIDEBAR FIX
     SAME BEHAVIOUR AS CUSTOMER PURCHASE DETAILS
====================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        /* =================================================
           SIDEBAR
        ================================================= */

        const sidebar =
            document.querySelector(
                ".main-container .sidebar"
            );


        /* =================================================
           OVERLAY
        ================================================= */

        const overlay =
            document.getElementById(
                "sidebarOverlay"
            );


        /* =================================================
           ALL TOGGLERS
        ================================================= */

        const togglers =
            document.querySelectorAll(
                ".sidebar-toggler, .navbar-toggler, .sidebar-toggle, [data-sidebar-toggle]"
            );


        /* =================================================
           SAFETY
        ================================================= */

        if (!sidebar) {

            console.log(
                "Sidebar not found."
            );

            return;
        }


        /* =================================================
           MOBILE CHECK
        ================================================= */

        function isMobile() {

            return window.innerWidth <= 991;
        }


        /* =================================================
           OPEN MOBILE SIDEBAR
        ================================================= */

        function openMobileSidebar() {

            document.body.classList.add(
                "sidebar-open"
            );

            document.body.classList.remove(
                "sidebar-collapsed"
            );
        }


        /* =================================================
           CLOSE MOBILE SIDEBAR
        ================================================= */

        function closeMobileSidebar() {

            document.body.classList.remove(
                "sidebar-open"
            );
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
            ============================================== */

            if (isMobile()) {

                if (
                    document.body.classList.contains(
                        "sidebar-open"
                    )
                ) {

                    closeMobileSidebar();

                } else {

                    openMobileSidebar();
                }

                return;
            }


            /* =============================================
               DESKTOP / LAPTOP
            ============================================== */

            document.body.classList.toggle(
                "sidebar-collapsed"
            );

            document.body.classList.remove(
                "sidebar-open"
            );
        }


        /* =================================================
           TOGGLER EVENTS
        ================================================= */

        togglers.forEach(
            function (toggler) {

                toggler.addEventListener(
                    "click",
                    function (event) {

                        toggleSidebar(event);

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
                function (event) {

                    event.preventDefault();

                    event.stopPropagation();

                    closeMobileSidebar();

                },
                true
            );
        }


        /* =================================================
           SIDEBAR LINKS
        ================================================= */

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


                        /* =================================
                           EMPTY LINK
                        ================================= */

                        if (
                            !href ||
                            href === "#" ||
                            href === "javascript:void(0)" ||
                            href === "javascript:void(0);"
                        ) {

                            return;
                        }


                        /* =================================
                           DROPDOWN
                        ================================= */

                        if (
                            link.hasAttribute(
                                "data-bs-toggle"
                            ) ||
                            link.hasAttribute(
                                "data-toggle"
                            ) ||
                            link.classList.contains(
                                "dropdown-toggle"
                            )
                        ) {

                            return;
                        }


                        /* =================================
                           MOBILE CLOSE
                        ================================= */

                        if (isMobile()) {

                            closeMobileSidebar();
                        }

                    },
                    true
                );
            }
        );


        /* =================================================
           CLICK OUTSIDE SIDEBAR
        ================================================= */

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
                    sidebar.contains(
                        event.target
                    );


                const clickedToggler =
                    event.target.closest(
                        ".sidebar-toggler, .navbar-toggler, .sidebar-toggle, [data-sidebar-toggle]"
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


        /* =================================================
           ESC KEY
        ================================================= */

        document.addEventListener(
            "keydown",
            function (event) {

                if (
                    event.key === "Escape" &&
                    isMobile()
                ) {

                    closeMobileSidebar();
                }

            }
        );


        /* =================================================
           RESIZE
        ================================================= */

        window.addEventListener(
            "resize",
            function () {

                if (window.innerWidth > 991) {

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


        /* =================================================
           INITIAL STATE
        ================================================= */

        if (isMobile()) {

            document.body.classList.remove(
                "sidebar-open"
            );

            document.body.classList.remove(
                "sidebar-collapsed"
            );
        }

    }
);

</script>

</body>

</html>
