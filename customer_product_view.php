<?php

include("Database.php");

$db = new Database();
$conn = $db->connection();

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
// UPDATE CUSTOMER
// =====================================================

if (isset($_POST["update_customer"])) {

    $customer_id = intval($_POST["customer_id"] ?? 0);

    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone_number = trim($_POST["phone_number"] ?? "");
    $address = trim($_POST["address"] ?? "");


    if ($customer_id <= 0) {

        echo "<script>
                alert('Invalid Customer ID');
                window.location.href='customer_product_view.php';
              </script>";

        exit;
    }


    if (
        $first_name === "" ||
        $last_name === "" ||
        $email === "" ||
        $phone_number === "" ||
        $address === ""
    ) {

        echo "<script>
                alert('All fields are required.');
                window.history.back();
              </script>";

        exit;
    }


    // =================================================
    // UPDATE QUERY
    // =================================================

    $sql_update = "
        UPDATE entity_asad_customer
        SET
            first_name = ?,
            last_name = ?,
            email = ?,
            phone_number = ?,
            address = ?
        WHERE customer_id = ?
    ";


    $stmt = $conn->prepare($sql_update);


    if (!$stmt) {

        die(
            "Update Prepare Error: " .
            e($conn->error)
        );
    }


    $stmt->bind_param(
        "sssssi",
        $first_name,
        $last_name,
        $email,
        $phone_number,
        $address,
        $customer_id
    );


    if ($stmt->execute()) {

        $stmt->close();

        echo "<script>
                alert('Customer Updated Successfully');
                window.location.href='customer_product_view.php';
              </script>";

        exit;

    } else {

        $error = $stmt->error;

        $stmt->close();

        die(
            "Update Error: " .
            e($error)
        );
    }
}


// =====================================================
// DELETE CUSTOMER
// =====================================================

if (isset($_GET["delete_id"])) {

    $delete_id = intval(
        $_GET["delete_id"]
    );


    if ($delete_id <= 0) {

        echo "<script>
                alert('Invalid Customer ID');
                window.location.href='customer_product_view.php';
              </script>";

        exit;
    }


    // =================================================
    // DELETE QUERY
    // =================================================

    $sql_delete = "
        DELETE FROM entity_asad_customer
        WHERE customer_id = ?
    ";


    $stmt_delete =
        $conn->prepare(
            $sql_delete
        );


    if (!$stmt_delete) {

        die(
            "Delete Prepare Error: " .
            e($conn->error)
        );
    }


    $stmt_delete->bind_param(
        "i",
        $delete_id
    );


    if ($stmt_delete->execute()) {

        $stmt_delete->close();

        echo "<script>
                alert('Customer Deleted Successfully');
                window.location.href='customer_product_view.php';
              </script>";

        exit;

    } else {

        $error =
            $stmt_delete->error;

        $stmt_delete->close();

        die(
            "Delete Error: " .
            e($error)
        );
    }
}


// =====================================================
// EDIT CUSTOMER DATA
// =====================================================

$edit_data = null;


if (isset($_GET["edit_id"])) {

    $edit_id = intval(
        $_GET["edit_id"]
    );


    if ($edit_id > 0) {

        $sql_edit = "
            SELECT
                customer_id,
                first_name,
                last_name,
                email,
                phone_number,
                address,
                registration_date
            FROM entity_asad_customer
            WHERE customer_id = ?
            LIMIT 1
        ";


        $stmt_edit =
            $conn->prepare(
                $sql_edit
            );


        if (!$stmt_edit) {

            die(
                "Edit Prepare Error: " .
                e($conn->error)
            );
        }


        $stmt_edit->bind_param(
            "i",
            $edit_id
        );


        $stmt_edit->execute();


        $result_edit =
            $stmt_edit->get_result();


        if (
            $result_edit &&
            $result_edit->num_rows > 0
        ) {

            $edit_data =
                $result_edit->fetch_assoc();
        }


        $stmt_edit->close();
    }
}


// =====================================================
// GET ALL CUSTOMERS
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
        e(mysqli_error($conn))
    );
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

```
<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Customers</title>


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
    href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap"
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
     DARKPAN CSS
====================================================== -->

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

.container-fluid.position-relative.d-flex {

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

.container-fluid .sidebar {

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
TOGGLERS
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
    .container-fluid
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

    background:
        rgba(0, 0, 0, 0.55);

    z-index: 99998;

    cursor: pointer;
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
CUSTOMER PAGE
========================================================= */

.customer-page {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    margin: 0 !important;

    padding: 24px !important;

    overflow: hidden !important;

    background: #000 !important;
}


/* =========================================================
CUSTOMER CARD
========================================================= */

.customer-card {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    border-radius: 8px !important;

    overflow: hidden !important;
}


/* =========================================================
CUSTOMER HEADER
========================================================= */

.customer-card-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 15px;

    flex-wrap: wrap;
}


.customer-title {

    margin: 0;

    font-size: 22px;

    font-weight: 600;
}


/* =========================================================
TABLE WRAPPER
========================================================= */

.customer-table-wrapper {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    overflow-x: auto !important;

    -webkit-overflow-scrolling: touch;
}


/* =========================================================
CUSTOMER TABLE
========================================================= */

.customer-table {

    width: 100% !important;

    margin: 0 !important;

    border-collapse: collapse !important;

    table-layout: fixed !important;
}


.customer-table th,
.customer-table td {

    vertical-align: middle !important;

    word-break: break-word;

    overflow-wrap: anywhere;
}


.customer-table th {

    white-space: normal;

    font-size: 13px;

    font-weight: 600;
}


.customer-table td {

    font-size: 13px;
}


/* =========================================================
COLUMN WIDTHS
========================================================= */

.col-id {
    width: 7%;
}

.col-first {
    width: 10%;
}

.col-last {
    width: 10%;
}

.col-email {
    width: 15%;
}

.col-phone {
    width: 12%;
}

.col-address {
    width: 16%;
}

.col-date {
    width: 13%;
}

.col-edit {
    width: 8%;
}

.col-delete {
    width: 9%;
}


/* =========================================================
PHONE NUMBER
========================================================= */

.phone-number {

    white-space: normal !important;

    word-break: break-word;

    overflow-wrap: anywhere;
}


/* =========================================================
ACTION BUTTON
========================================================= */

.action-btn {

    min-width: 70px;
}


/* =========================================================
EDIT OVERLAY
========================================================= */

.edit-overlay {

    position: fixed;

    inset: 0;

    background:
        rgba(0, 0, 0, 0.65);

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 20px;

    z-index: 10000;
}


.edit-box {

    width: 100%;

    max-width: 650px;

    max-height: 90vh;

    overflow-y: auto;

    background: #191c24;

    border-radius: 10px;

    padding: 25px;

    box-shadow:
        0 10px 40px
        rgba(0, 0, 0, 0.4);
}


.edit-box h4 {

    margin-bottom: 20px;
}


.edit-box label {

    margin-bottom: 6px;

    font-weight: 500;
}


.edit-box .form-control {

    width: 100%;
}


/* =========================================================
TABLET / LAPTOP
========================================================= */

@media (max-width: 991px) {

    .container-fluid .sidebar {

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
    .container-fluid
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


    .customer-page {

        width: 100% !important;

        max-width: 100% !important;

        padding: 15px !important;
    }


    /* =====================================================
       TABLE TO CARDS
    ====================================================== */

    .customer-table-wrapper {

        overflow: visible !important;
    }


    .customer-table {

        display: block !important;

        width: 100% !important;
    }


    .customer-table thead {

        display: none !important;
    }


    .customer-table tbody {

        display: block !important;

        width: 100% !important;
    }


    .customer-table tr {

        display: block !important;

        width: 100% !important;

        margin-bottom: 18px;

        padding: 15px;

        border:
            1px solid
            rgba(255,255,255,0.10);

        border-radius: 10px;

        background:
            rgba(0,0,0,0.10);
    }


    .customer-table td {

        display: flex !important;

        width: 100% !important;

        min-height: 42px;

        align-items: flex-start;

        justify-content: space-between;

        gap: 15px;

        padding: 10px 5px;

        border: 0 !important;

        border-bottom:
            1px solid
            rgba(255,255,255,0.08) !important;
    }


    .customer-table td:last-child {

        border-bottom: 0 !important;
    }


    .customer-table td::before {

        content: attr(data-label);

        flex: 0 0 42%;

        max-width: 42%;

        font-weight: 600;

        white-space: normal;
    }


    .customer-table td > * {

        max-width: 58%;

        text-align: right;
    }


    .customer-table td[data-label="Edit"],
    .customer-table td[data-label="Delete"] {

        align-items: center;
    }


    .customer-table td[data-label="Edit"] > *,
    .customer-table td[data-label="Delete"] > * {

        margin-left: auto;
    }
}


/* =========================================================
MOBILE
========================================================= */

@media (max-width: 767px) {

    .container-fluid .sidebar {

        width: 250px !important;

        min-width: 250px !important;

        left: -250px !important;
    }


    body.sidebar-open
    .container-fluid
    .sidebar {

        left: 0 !important;
    }


    .content {

        width: 100% !important;

        max-width: 100% !important;

        margin-left: 0 !important;
    }


    .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        margin: 0 !important;

        padding-left: 10px !important;

        padding-right: 10px !important;
    }


    .customer-page {

        width: 100% !important;

        max-width: 100% !important;

        padding: 8px !important;

        margin: 0 !important;
    }


    .customer-card {

        width: 100% !important;

        max-width: 100% !important;
    }


    .customer-card-header {

        align-items: flex-start;

        flex-direction: column;
    }


    .customer-title {

        font-size: 19px;
    }


    .customer-table tr {

        padding: 12px;
    }


    .customer-table td {

        gap: 10px;

        padding: 9px 3px;

        font-size: 13px;
    }


    .customer-table td::before {

        flex-basis: 40%;

        max-width: 40%;
    }


    .customer-table td > * {

        max-width: 60%;
    }


    .edit-overlay {

        padding: 12px;
    }


    .edit-box {

        padding: 18px;

        max-height: 94vh;
    }
}


/* =========================================================
SMALL MOBILE
========================================================= */

@media (max-width: 575px) {

    .customer-page {

        padding: 6px !important;
    }


    .customer-card {

        border-radius: 6px !important;
    }


    .customer-table tr {

        padding: 10px;
    }


    .customer-table td {

        padding: 8px 3px;

        font-size: 12px;
    }


    .action-btn {

        min-width: 65px;

        font-size: 11px;
    }
}


/* =========================================================
VERY SMALL MOBILE
========================================================= */

@media (max-width: 380px) {

    .customer-table td {

        flex-direction: column;

        gap: 4px;

        align-items: flex-start;
    }


    .customer-table td::before {

        flex: none;

        max-width: 100%;
    }


    .customer-table td > * {

        max-width: 100%;

        text-align: left;

        margin-left: 0 !important;
    }


    .customer-table td[data-label="Edit"] > *,
    .customer-table td[data-label="Delete"] > * {

        margin-left: 0 !important;
    }
}


/* =========================================================
DESKTOP
========================================================= */

@media (min-width: 1200px) {

    .customer-table {

        display: table !important;
    }


    .customer-table thead {

        display: table-header-group !important;
    }


    .customer-table tbody {

        display: table-row-group !important;
    }


    .customer-table tr {

        display: table-row !important;
    }


    .customer-table td,
    .customer-table th {

        display: table-cell !important;
    }
}


/* =========================================================
LARGE DESKTOP
========================================================= */

@media (min-width: 1400px) {

    .customer-page {

        padding: 20px !important;
    }


    .customer-table th,
    .customer-table td {

        font-size: 13px;
    }
}

</style>

</head>

<body>

<!-- =====================================================
     MAIN CONTAINER
====================================================== -->

<div class="container-fluid position-relative d-flex p-0">

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
         MAIN CONTENT
    ================================================== -->

    <div class="container-fluid customer-page">


        <div class="bg-secondary rounded p-4 customer-card">


            <!-- =========================================
                 HEADER
            ========================================== -->

            <div class="customer-card-header mb-4">

                <div>

                    <h6 class="customer-title">

                        <i class="fa fa-users me-2"></i>

                        Customer List

                    </h6>

                </div>


                <div>

                    <a
                        href="customer_add.php"
                        class="btn btn-primary"
                    >

                        <i class="fa fa-plus me-2"></i>

                        Add Customer

                    </a>

                </div>

            </div>


            <!-- =========================================
                 CUSTOMER TABLE
            ========================================== -->

            <div class="customer-table-wrapper">

                <table
                    class="table table-dark table-hover customer-table"
                >

                    <thead>

                        <tr>

                            <th class="col-id">
                                Customer ID
                            </th>

                            <th class="col-first">
                                First Name
                            </th>

                            <th class="col-last">
                                Last Name
                            </th>

                            <th class="col-email">
                                Email
                            </th>

                            <th class="col-phone">
                                Phone Number
                            </th>

                            <th class="col-address">
                                Address
                            </th>

                            <th class="col-date">
                                Registration Date
                            </th>

                            <th class="col-edit text-center">
                                Edit
                            </th>

                            <th class="col-delete text-center">
                                Delete
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    if (
                        mysqli_num_rows($res) > 0
                    ) {

                        while (
                            $row =
                            mysqli_fetch_assoc($res)
                        ) {

                    ?>

                        <tr>


                            <!-- CUSTOMER ID -->

                            <td data-label="Customer ID">

                                <?php

                                echo e(
                                    $row["customer_id"]
                                );

                                ?>

                            </td>


                            <!-- FIRST NAME -->

                            <td data-label="First Name">

                                <?php

                                echo e(
                                    $row["first_name"]
                                );

                                ?>

                            </td>


                            <!-- LAST NAME -->

                            <td data-label="Last Name">

                                <?php

                                echo e(
                                    $row["last_name"]
                                );

                                ?>

                            </td>


                            <!-- EMAIL -->

                            <td data-label="Email">

                                <?php

                                echo e(
                                    $row["email"]
                                );

                                ?>

                            </td>


                            <!-- PHONE -->

                            <td
                                data-label="Phone Number"
                                class="phone-number"
                            >

                                <?php

                                if (
                                    isset(
                                        $row["phone_number"]
                                    ) &&
                                    trim(
                                        $row["phone_number"]
                                    ) !== ""
                                ) {

                                    echo e(
                                        $row["phone_number"]
                                    );

                                } else {

                                    echo "N/A";
                                }

                                ?>

                            </td>


                            <!-- ADDRESS -->

                            <td data-label="Address">

                                <?php

                                echo e(
                                    $row["address"]
                                );

                                ?>

                            </td>


                            <!-- REGISTRATION DATE -->

                            <td data-label="Registration Date">

                                <?php

                                echo e(
                                    $row[
                                        "registration_date"
                                    ]
                                );

                                ?>

                            </td>


                            <!-- EDIT -->

                            <td
                                data-label="Edit"
                                class="text-center"
                            >

                                <a
                                    href="customer_product_view.php?edit_id=<?php echo urlencode($row["customer_id"]); ?>"
                                    class="btn btn-sm btn-warning action-btn"
                                >

                                    <i
                                        class="fa fa-edit me-1"
                                    ></i>

                                    Edit

                                </a>

                            </td>


                            <!-- DELETE -->

                            <td
                                data-label="Delete"
                                class="text-center"
                            >

                                <a
                                    href="customer_product_view.php?delete_id=<?php echo urlencode($row["customer_id"]); ?>"
                                    class="btn btn-sm btn-danger action-btn"
                                    onclick="return confirm('Are you sure you want to delete this customer?');"
                                >

                                    <i
                                        class="fa fa-trash me-1"
                                    ></i>

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
                                colspan="9"
                                class="text-center py-4"
                            >

                                <i
                                    class="fa fa-users fa-2x mb-2 d-block"
                                ></i>

                                No Customer Record Found.

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


    <!-- =================================================
         EDIT CUSTOMER OVERLAY
    ================================================== -->

    <?php if ($edit_data !== null) { ?>

        <div class="edit-overlay">

            <div class="edit-box">


                <!-- EDIT HEADER -->

                <div
                    class="d-flex justify-content-between align-items-center mb-3"
                >

                    <h4 class="mb-0">

                        <i
                            class="fa fa-user-edit me-2"
                        ></i>

                        Edit Customer

                    </h4>


                    <a
                        href="customer_product_view.php"
                        class="btn btn-sm btn-secondary"
                    >

                        <i class="fa fa-times"></i>

                    </a>

                </div>


                <!-- EDIT FORM -->

                <form
                    method="POST"
                    action="customer_product_view.php"
                >


                    <!-- CUSTOMER ID -->

                    <input
                        type="hidden"
                        name="customer_id"
                        value="<?php echo e($edit_data["customer_id"]); ?>"
                    >


                    <!-- FIRST NAME -->

                    <div class="mb-3">

                        <label class="form-label">
                            First Name
                        </label>

                        <input
                            type="text"
                            name="first_name"
                            class="form-control"
                            value="<?php echo e($edit_data["first_name"]); ?>"
                            required
                        >

                    </div>


                    <!-- LAST NAME -->

                    <div class="mb-3">

                        <label class="form-label">
                            Last Name
                        </label>

                        <input
                            type="text"
                            name="last_name"
                            class="form-control"
                            value="<?php echo e($edit_data["last_name"]); ?>"
                            required
                        >

                    </div>


                    <!-- EMAIL -->

                    <div class="mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="<?php echo e($edit_data["email"]); ?>"
                            required
                        >

                    </div>


                    <!-- PHONE -->

                    <div class="mb-3">

                        <label class="form-label">
                            Phone Number
                        </label>

                        <input
                            type="tel"
                            name="phone_number"
                            class="form-control"
                            value="<?php echo e($edit_data["phone_number"]); ?>"
                            placeholder="Enter phone number"
                            inputmode="tel"
                            required
                        >

                    </div>


                    <!-- ADDRESS -->

                    <div class="mb-3">

                        <label class="form-label">
                            Address
                        </label>

                        <textarea
                            name="address"
                            class="form-control"
                            rows="3"
                            required
                        ><?php echo e($edit_data["address"]); ?></textarea>

                    </div>


                    <!-- REGISTRATION DATE -->

                    <div class="mb-3">

                        <label class="form-label">
                            Registration Date
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?php echo e($edit_data["registration_date"]); ?>"
                            readonly
                        >

                    </div>


                    <!-- BUTTONS -->

                    <div class="d-flex gap-2 flex-wrap">

                        <button
                            type="submit"
                            name="update_customer"
                            class="btn btn-primary"
                        >

                            <i
                                class="fa fa-save me-2"
                            ></i>

                            Update Customer

                        </button>


                        <a
                            href="customer_product_view.php"
                            class="btn btn-secondary"
                        >

                            <i
                                class="fa fa-times me-2"
                            ></i>

                            Cancel

                        </a>

                    </div>


                </form>

            </div>

        </div>

    <?php } ?>


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
     BOOTSTRAP
====================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
></script>

<!-- =====================================================
     DARKPAN LIBRARIES
====================================================== -->

<script
    src="lib/chart/chart.min.js"
></script>

<script
    src="lib/easing/easing.min.js"
></script>

<script
    src="lib/waypoints/waypoints.min.js"
></script>

<script
    src="lib/owlcarousel/owl.carousel.min.js"
></script>

<script
    src="lib/tempusdominus/js/moment.min.js"
></script>

<script
    src="lib/tempusdominus/js/moment-timezone.min.js"
></script>

<script
    src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"
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


        // =================================================
        // FIND SIDEBAR TOGGLERS
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
                ".container-fluid .sidebar"
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
        // TOGGLE SIDEBAR
        // =================================================

        function toggleSidebar(event) {

            if (event) {

                event.preventDefault();

                event.stopPropagation();
            }


            // =============================================
            // MOBILE / TABLET
            // =============================================

            if (window.innerWidth <= 991) {

                document.body.classList.toggle(
                    "sidebar-open"
                );

                document.body.classList.remove(
                    "sidebar-collapsed"
                );
            }


            // =============================================
            // DESKTOP
            // =============================================

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
        // ADD TOGGLER EVENT
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


                        // =================================
                        // IGNORE EMPTY LINKS
                        // =================================

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


                        // =================================
                        // IGNORE DROPDOWN BUTTONS
                        // =================================

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


                        // =================================
                        // CLOSE MOBILE SIDEBAR
                        // =================================

                        document.body.classList.remove(
                            "sidebar-open"
                        );


                        // =================================
                        // MOBILE / TABLET
                        // =================================

                        if (
                            window.innerWidth <= 991
                        ) {

                            event.preventDefault();

                            event.stopPropagation();


                            // =============================
                            // DIRECT PAGE OPEN
                            // =============================

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

                // =========================================
                // DESKTOP
                // =========================================

                if (
                    window.innerWidth > 991
                ) {

                    return;
                }


                // =========================================
                // SIDEBAR NOT OPEN
                // =========================================

                if (
                    !document.body.classList.contains(
                        "sidebar-open"
                    )
                ) {

                    return;
                }


                // =========================================
                // CHECK SIDEBAR CLICK
                // =========================================

                const clickedInsideSidebar =
                    sidebar.contains(
                        event.target
                    );


                // =========================================
                // CHECK TOGGLER CLICK
                // =========================================

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


                // =========================================
                // CLOSE IF OUTSIDE
                // =========================================

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
