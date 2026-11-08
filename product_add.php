<?php
// =====================================================
// PRODUCT ADD PAGE
// =====================================================

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
// INSERT PRODUCT
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $discraption = trim($_POST["discraption"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $stock_quantity = trim($_POST["stock_quantity"] ?? "");
    $category_id = trim($_POST["category_id"] ?? "");


    // =================================================
    // VALIDATION
    // =================================================

    if (
        $name === "" ||
        $discraption === "" ||
        $price === "" ||
        $stock_quantity === "" ||
        $category_id === ""
    ) {

        $message = "Please fill all fields.";
        $message_type = "danger";

    } elseif (!is_numeric($price)) {

        $message = "Price must be a valid number.";
        $message_type = "danger";

    } elseif (!is_numeric($stock_quantity)) {

        $message = "Stock quantity must be a valid number.";
        $message_type = "danger";

    } elseif (!is_numeric($category_id)) {

        $message = "Category ID must be a valid number.";
        $message_type = "danger";

    } else {

        // =================================================
        // PREPARED STATEMENT
        // =================================================

        $sql = "
            INSERT INTO entity_asad_product
            (
                name,
                discraption,
                price,
                stock_quantity,
                category_id
            )
            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?
            )
        ";

        $stmt = mysqli_prepare($conn, $sql);


        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "ssdii",
                $name,
                $discraption,
                $price,
                $stock_quantity,
                $category_id
            );


            if (mysqli_stmt_execute($stmt)) {

               header("location:product_view.php");// $message = "Product added successfully.";
                $message_type = "success";


                // =================================================
                // CLEAR FORM
                // =================================================

                $name = "";
                $discraption = "";
                $price = "";
                $stock_quantity = "";
                $category_id = "";

            } else {

                $message = "Product insert failed: " .
                           mysqli_stmt_error($stmt);

                $message_type = "danger";
            }


            mysqli_stmt_close($stmt);

        } else {

            $message = "Database query error: " .
                       mysqli_error($conn);

            $message_type = "danger";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <title>DarkPan - Add Product</title>

    <meta
        content="width=device-width, initial-scale=1.0"
        name="viewport"
    >

    <meta content="" name="keywords">
    <meta content="" name="description">


    <!-- =====================================================
         FAVICON
    ====================================================== -->

    <link href="img/favicon.ico" rel="icon">


    <!-- =====================================================
         GOOGLE WEB FONTS
    ====================================================== -->

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
         LIBRARIES
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

    min-width: 0 !important;
    min-height: 100vh !important;

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
   TOGGLE BUTTON
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
   PRODUCT PAGE
========================================================= */

.product-page-container {

    width: 100% !important;
    max-width: 100% !important;

    min-width: 0 !important;

    margin: 0 !important;

    overflow-x: hidden !important;
}


/* =========================================================
   PRODUCT FORM BOX
========================================================= */

.product-form-box {

    width: 100% !important;
    max-width: 100% !important;

    min-width: 0 !important;

    overflow: hidden !important;
}


.product-form-box .form-control {

    min-height: 45px;

}


.product-form-box textarea.form-control {

    min-height: 110px;

}


/* =========================================================
   FORM BUTTONS
========================================================= */

.product-form-buttons {

    display: flex;

    gap: 10px;

    flex-wrap: wrap;
}


/* =========================================================
   MOBILE / TABLET
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

        z-index: 99999 !important;

        transition: left 0.3s ease !important;

        overflow-y: auto !important;
        overflow-x: hidden !important;
    }


    /* SIDEBAR OPEN */

    body.sidebar-open
    .main-container
    .sidebar {

        left: 0 !important;
    }


    /* OVERLAY */

    body.sidebar-open
    .sidebar-mobile-overlay {

        display: block !important;
    }


    /* CONTENT */

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
        max-width: 100% !important;

        min-height: 70px !important;

        padding-left: 15px !important;
        padding-right: 15px !important;
    }


    /* DESKTOP COLLAPSE REMOVE */

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


    /* PRODUCT PAGE */

    .product-page-container {

        width: 100% !important;
        max-width: 100% !important;

        padding-left: 15px !important;
        padding-right: 15px !important;

        overflow-x: hidden !important;
    }


    /* FORM ROW */

    .product-form-box .row {

        width: 100% !important;

        margin-left: 0 !important;
        margin-right: 0 !important;
    }


    .product-form-box .col-md-6,
    .product-form-box .col-md-12 {

        padding-left: 0 !important;
        padding-right: 0 !important;
    }

}


/* =========================================================
   TABLET
========================================================= */

@media (min-width: 768px) and (max-width: 991px) {

    .product-page-container {

        padding-left: 15px !important;
        padding-right: 15px !important;
    }


    .product-form-box {

        padding: 20px !important;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767px) {

    .content {

        width: 100% !important;
        max-width: 100% !important;

        margin-left: 0 !important;
    }


    .content > .navbar {

        width: 100% !important;
        max-width: 100% !important;

        min-height: 70px !important;

        margin: 0 !important;

        padding-left: 10px !important;
        padding-right: 10px !important;
    }


    .product-page-container {

        width: 100% !important;
        max-width: 100% !important;

        min-width: 0 !important;

        padding-left: 8px !important;
        padding-right: 8px !important;

        margin: 0 !important;

        overflow-x: hidden !important;
    }


    .product-form-box {

        width: 100% !important;
        max-width: 100% !important;

        padding: 15px !important;

        overflow: hidden !important;
    }


    .product-form-box h6 {

        font-size: 16px !important;

        margin-bottom: 20px !important;
    }


    .product-form-buttons {

        width: 100% !important;

        flex-direction: column !important;
    }


    .product-form-buttons button {

        width: 100% !important;
    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 575px) {

    .content > .navbar {

        min-height: 60px !important;

        padding-left: 8px !important;
        padding-right: 8px !important;
    }


    .product-page-container {

        padding-left: 6px !important;
        padding-right: 6px !important;
    }


    .product-form-box {

        padding: 12px !important;

        border-radius: 7px !important;
    }


    .product-form-box h6 {

        font-size: 15px !important;
    }


    .product-form-box .form-control {

        min-height: 43px !important;

        font-size: 13px !important;
    }


    .product-form-box textarea.form-control {

        min-height: 100px !important;
    }


    .product-form-box .form-label {

        font-size: 13px !important;
    }

}


/* =========================================================
   VERY SMALL MOBILE
========================================================= */

@media (max-width: 400px) {

    .product-page-container {

        padding-left: 5px !important;
        padding-right: 5px !important;
    }


    .product-form-box {

        padding: 10px !important;
    }


    .product-form-box h6 {

        font-size: 14px !important;
    }


    .product-form-box .form-control {

        font-size: 12px !important;
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
             PRODUCT FORM
        ================================================== -->

        <div
            class="container-fluid pt-4 px-4 product-page-container"
        >

            <div class="row g-4">


                <!-- =========================================
                     FORM COLUMN
                ========================================== -->

                <div class="col-12 col-xl-8">


                    <!-- =====================================
                         FORM BOX
                    ====================================== -->

                    <div
                        class="bg-secondary rounded h-100 p-4 product-form-box"
                    >


                        <!-- =================================
                             HEADING
                        ================================== -->

                        <h6 class="mb-4">

                            <i class="fa fa-box me-2"></i>

                            Add Product

                        </h6>


                        <!-- =================================
                             MESSAGE
                        ================================== -->

                        <?php if ($message !== ""): ?>

                            <div
                                class="alert alert-<?php echo e($message_type); ?>"
                                role="alert"
                            >

                                <?php echo e($message); ?>

                            </div>

                        <?php endif; ?>


                        <!-- =================================
                             PRODUCT FORM
                        ================================== -->

                        <form
                            method="post"
                            action=""
                        >


                            <!-- =================================
                                 PRODUCT NAME
                            ================================== -->

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="name"
                                        class="form-label"
                                    >

                                        Product Name

                                    </label>


                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="form-control"
                                        placeholder="Enter Product Name"
                                        value="<?php echo e($name ?? ''); ?>"
                                        required
                                    >

                                </div>


                                <!-- =================================
                                     PRICE
                                ================================== -->

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="price"
                                        class="form-label"
                                    >

                                        Price

                                    </label>


                                    <input
                                        type="number"
                                        name="price"
                                        id="price"
                                        class="form-control"
                                        placeholder="Enter Product Price"
                                        value="<?php echo e($price ?? ''); ?>"
                                        min="0"
                                        step="0.01"
                                        required
                                    >

                                </div>

                            </div>


                            <!-- =================================
                                 DESCRIPTION
                            ================================== -->

                            <div class="row">

                                <div class="col-12 mb-3">

                                    <label
                                        for="discraption"
                                        class="form-label"
                                    >

                                        Description

                                    </label>


                                    <textarea
                                        name="discraption"
                                        id="discraption"
                                        class="form-control"
                                        rows="4"
                                        placeholder="Enter Product Description"
                                        required
                                    ><?php echo e($discraption ?? ''); ?></textarea>

                                </div>

                            </div>


                            <!-- =================================
                                 STOCK + CATEGORY
                            ================================== -->

                            <div class="row">


                                <!-- STOCK QUANTITY -->

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="stock_quantity"
                                        class="form-label"
                                    >

                                        Stock Quantity

                                    </label>


                                    <input
                                        type="number"
                                        name="stock_quantity"
                                        id="stock_quantity"
                                        class="form-control"
                                        placeholder="Enter Stock Quantity"
                                        value="<?php echo e($stock_quantity ?? ''); ?>"
                                        min="0"
                                        required
                                    >

                                </div>


                                <!-- CATEGORY ID -->

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="category_id"
                                        class="form-label"
                                    >

                                        Category ID

                                    </label>


                                    <input
                                        type="number"
                                        name="category_id"
                                        id="category_id"
                                        class="form-control"
                                        placeholder="Enter Category ID"
                                        value="<?php echo e($category_id ?? ''); ?>"
                                        min="1"
                                        required
                                    >

                                </div>

                            </div>


                            <!-- =================================
                                 BUTTONS
                            ================================== -->

                            <div class="product-form-buttons mt-4">


                                <!-- SAVE -->

                                <button
                                    type="submit"
                                    name="save_product"
                                    class="btn btn-primary"
                                >

                                    <i class="fa fa-save me-1"></i>

                                    Save Product

                                </button>


                                <!-- RESET -->

                                <button
                                    type="reset"
                                    class="btn btn-danger"
                                >

                                    <i class="fa fa-refresh me-1"></i>

                                    Reset

                                </button>


                            </div>


                        </form>


                    </div>

                </div>

            </div>

        </div>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <div class="container-fluid pt-4 px-4">

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


<!-- =========================================================
     JQUERY
========================================================= -->

<script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
></script>


<!-- =========================================================
     BOOTSTRAP
========================================================= -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
></script>


<!-- =========================================================
     CHART
========================================================= -->

<script src="lib/chart/chart.min.js"></script>


<!-- =========================================================
     EASING
========================================================= -->

<script src="lib/easing/easing.min.js"></script>


<!-- =========================================================
     WAYPOINTS
========================================================= -->

<script src="lib/waypoints/waypoints.min.js"></script>


<!-- =========================================================
     OWL CAROUSEL
========================================================= -->

<script src="lib/owlcarousel/owl.carousel.min.js"></script>


<!-- =========================================================
     MOMENT
========================================================= -->

<script src="lib/tempusdominus/js/moment.min.js"></script>

<script src="lib/tempusdominus/js/moment-timezone.min.js"></script>

<script
    src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"
></script>


<!-- =========================================================
     DARKPAN MAIN JS
========================================================= -->

<script src="js/main.js"></script>


<!-- =========================================================
     SIDEBAR FIX
========================================================= -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        // =====================================================
        // FIND TOGGLERS
        // =====================================================

        const togglers =
            document.querySelectorAll(
                ".sidebar-toggler, .navbar-toggler"
            );


        // =====================================================
        // FIND SIDEBAR
        // =====================================================

        const sidebar =
            document.querySelector(
                ".main-container .sidebar"
            );


        // =====================================================
        // FIND OVERLAY
        // =====================================================

        const overlay =
            document.getElementById(
                "sidebarOverlay"
            );


        // =====================================================
        // SAFETY CHECK
        // =====================================================

        if (!sidebar) {

            console.log("Sidebar not found.");

            return;
        }


        // =====================================================
        // TOGGLE SIDEBAR
        // =====================================================

        function toggleSidebar(event) {

            if (event) {

                event.preventDefault();

                event.stopImmediatePropagation();
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


        // =====================================================
        // ADD TOGGLE EVENTS
        // =====================================================

        togglers.forEach(
            function (toggler) {

                toggler.addEventListener(
                    "click",
                    toggleSidebar,
                    true
                );

            }
        );


        // =====================================================
        // SIDEBAR LINKS
        // =====================================================

        const sidebarLinks =
            sidebar.querySelectorAll("a");


        sidebarLinks.forEach(
            function (link) {

                link.addEventListener(
                    "click",
                    function () {

                        const href =
                            link.getAttribute("href");


                        // =================================================
                        // IGNORE EMPTY LINKS
                        // =================================================

                        if (
                            !href ||
                            href === "#" ||
                            href === "javascript:void(0)" ||
                            href === "javascript:void(0);"
                        ) {

                            return;
                        }


                        // =================================================
                        // IGNORE DROPDOWN
                        // =================================================

                        if (
                            link.hasAttribute("data-bs-toggle") ||
                            link.hasAttribute("data-toggle")
                        ) {

                            return;
                        }


                        // =================================================
                        // CLOSE MOBILE SIDEBAR
                        // =================================================

                        if (
                            window.innerWidth <= 991
                        ) {

                            document.body.classList.remove(
                                "sidebar-open"
                            );

                        }

                    },
                    true
                );

            }
        );


        // =====================================================
        // OVERLAY CLICK
        // =====================================================

        if (overlay) {

            overlay.addEventListener(
                "click",
                function (event) {

                    event.preventDefault();

                    event.stopPropagation();

                    document.body.classList.remove(
                        "sidebar-open"
                    );

                }
            );

        }


        // =====================================================
        // OUTSIDE CLICK
        // =====================================================

        document.addEventListener(
            "click",
            function (event) {


                if (window.innerWidth > 991) {

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
                    sidebar.contains(event.target);


                let clickedToggler = false;


                togglers.forEach(
                    function (toggler) {

                        if (
                            toggler.contains(event.target)
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


        // =====================================================
        // ESC KEY
        // =====================================================

        document.addEventListener(
            "keydown",
            function (event) {

                if (event.key === "Escape") {

                    document.body.classList.remove(
                        "sidebar-open"
                    );

                }

            }
        );


        // =====================================================
        // RESIZE FIX
        // =====================================================

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

    }
);

</script>


</body>

</html>