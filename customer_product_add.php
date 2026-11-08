<?php
// =====================================================
// CUSTOMER ADD PAGE
// =====================================================
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <title>DarkPan - Customer Add</title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">


    <!-- =====================================================
         FAVICON
    ====================================================== -->

    <link href="img/favicon.ico" rel="icon">


    <!-- =====================================================
         GOOGLE WEB FONTS
    ====================================================== -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

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
   SAME AS PURCHASE DETAILS PAGE
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

.customer-page-container {

    width: 100% !important;
    max-width: 100% !important;

    min-width: 0 !important;

    margin: 0 !important;

    overflow-x: hidden !important;
}


/* =========================================================
   CUSTOMER FORM BOX
========================================================= */

.customer-form-box {

    width: 100% !important;
    max-width: 100% !important;

    min-width: 0 !important;

    overflow: hidden !important;
}


.customer-form-box .form-control {

    min-height: 45px;

}


.customer-form-box textarea.form-control {

    min-height: 110px;

}


/* =========================================================
   FORM BUTTONS
========================================================= */

.customer-form-buttons {

    display: flex;

    gap: 10px;

    flex-wrap: wrap;
}


/* =========================================================
   MOBILE / TABLET
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

        transition: left 0.3s ease !important;

        overflow-y: auto !important;
        overflow-x: hidden !important;
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
       OVERLAY OPEN
    ----------------------------------------------------- */

    body.sidebar-open
    .sidebar-mobile-overlay {

        display: block !important;
    }


    /* -----------------------------------------------------
       CONTENT FULL WIDTH
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
        max-width: 100% !important;

        min-height: 70px !important;

        padding-left: 15px !important;
        padding-right: 15px !important;
    }


    /* -----------------------------------------------------
       REMOVE DESKTOP COLLAPSE
    ----------------------------------------------------- */

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


    /* -----------------------------------------------------
       CUSTOMER PAGE
    ----------------------------------------------------- */

    .customer-page-container {

        width: 100% !important;
        max-width: 100% !important;

        padding-left: 15px !important;
        padding-right: 15px !important;

        overflow-x: hidden !important;
    }


    /* -----------------------------------------------------
       FORM ROW
    ----------------------------------------------------- */

    .customer-form-box .row {

        width: 100% !important;

        margin-left: 0 !important;
        margin-right: 0 !important;
    }


    .customer-form-box .col-md-6,
    .customer-form-box .col-md-12 {

        padding-left: 0 !important;
        padding-right: 0 !important;
    }

}


/* =========================================================
   TABLET 768 - 991
========================================================= */

@media (min-width: 768px) and (max-width: 991px) {

    .customer-page-container {

        padding-left: 15px !important;
        padding-right: 15px !important;
    }


    .customer-form-box {

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


    .customer-page-container {

        width: 100% !important;
        max-width: 100% !important;

        min-width: 0 !important;

        padding-left: 8px !important;
        padding-right: 8px !important;

        margin: 0 !important;

        overflow-x: hidden !important;
    }


    .customer-form-box {

        width: 100% !important;
        max-width: 100% !important;

        padding: 15px !important;

        overflow: hidden !important;
    }


    .customer-form-box h6 {

        font-size: 16px !important;

        margin-bottom: 20px !important;
    }


    .customer-form-buttons {

        width: 100% !important;

        flex-direction: column !important;
    }


    .customer-form-buttons button {

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


    .customer-page-container {

        padding-left: 6px !important;
        padding-right: 6px !important;
    }


    .customer-form-box {

        padding: 12px !important;

        border-radius: 7px !important;
    }


    .customer-form-box h6 {

        font-size: 15px !important;
    }


    .customer-form-box .form-control {

        min-height: 43px !important;

        font-size: 13px !important;
    }


    .customer-form-box textarea.form-control {

        min-height: 100px !important;
    }


    .customer-form-box .form-label {

        font-size: 13px !important;
    }

}


/* =========================================================
   VERY SMALL MOBILE
========================================================= */

@media (max-width: 400px) {

    .customer-page-container {

        padding-left: 5px !important;
        padding-right: 5px !important;
    }


    .customer-form-box {

        padding: 10px !important;
    }


    .customer-form-box h6 {

        font-size: 14px !important;
    }


    .customer-form-box .form-control {

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
             CUSTOMER FORM
        ================================================== -->

        <div
            class="container-fluid pt-4 px-4 customer-page-container"
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
                        class="bg-secondary rounded h-100 p-4 customer-form-box"
                    >


                        <!-- =================================
                             HEADING
                        ================================== -->

                        <h6 class="mb-4">

                            <i class="fa fa-user-plus me-2"></i>

                            Add Customer

                        </h6>


                        <!-- =================================
                             CUSTOMER FORM
                        ================================== -->

                        <form
                            method="post"
                            action="customer_product_insert.php"
                        >


                            <!-- =================================
                                 FIRST NAME + LAST NAME
                            ================================== -->

                            <div class="row">


                                <!-- FIRST NAME -->

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="first_name"
                                        class="form-label"
                                    >

                                        First Name

                                    </label>


                                    <input
                                        type="text"
                                        name="first_name"
                                        id="first_name"
                                        class="form-control"
                                        placeholder="Enter First Name"
                                        autocomplete="given-name"
                                        required
                                    >

                                </div>


                                <!-- LAST NAME -->

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="last_name"
                                        class="form-label"
                                    >

                                        Last Name

                                    </label>


                                    <input
                                        type="text"
                                        name="last_name"
                                        id="last_name"
                                        class="form-control"
                                        placeholder="Enter Last Name"
                                        autocomplete="family-name"
                                        required
                                    >

                                </div>

                            </div>


                            <!-- =================================
                                 EMAIL + PHONE
                            ================================== -->

                            <div class="row">


                                <!-- EMAIL -->

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="email"
                                        class="form-label"
                                    >

                                        Email

                                    </label>


                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="form-control"
                                        placeholder="Enter Email Address"
                                        autocomplete="email"
                                        required
                                    >

                                </div>


                                <!-- PHONE NUMBER -->

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="phone_number"
                                        class="form-label"
                                    >

                                        Phone Number

                                    </label>


                                    <input
                                        type="text"
                                        name="phone_number"
                                        id="phone_number"
                                        class="form-control"
                                        placeholder="Enter Phone Number"
                                        autocomplete="tel"
                                        inputmode="tel"
                                        required
                                    >

                                </div>

                            </div>


                            <!-- =================================
                                 ADDRESS
                            ================================== -->

                            <div class="row">

                                <div class="col-12 mb-3">

                                    <label
                                        for="address"
                                        class="form-label"
                                    >

                                        Address

                                    </label>


                                    <textarea
                                        name="address"
                                        id="address"
                                        class="form-control"
                                        rows="4"
                                        placeholder="Enter Customer Address"
                                        autocomplete="street-address"
                                        required
                                    ></textarea>

                                </div>

                            </div>


                            <!-- =================================
                                 REGISTRATION DATE
                            ================================== -->

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="registration_date"
                                        class="form-label"
                                    >

                                        Registration Date

                                    </label>


                                    <input
                                        type="date"
                                        name="registration_date"
                                        id="registration_date"
                                        class="form-control"
                                        value="<?php echo date('Y-m-d'); ?>"
                                        required
                                    >

                                </div>

                            </div>


                            <!-- =================================
                                 BUTTONS
                            ================================== -->

                            <div class="customer-form-buttons mt-4">


                                <!-- SAVE -->

                                <button
                                    type="submit"
                                    name="save_customer"
                                    class="btn btn-primary"
                                >

                                    <i class="fa fa-save me-1"></i>

                                    Save Customer

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


                    <!-- =====================================
                         LEFT
                    ====================================== -->

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


                    <!-- =====================================
                         RIGHT
                    ====================================== -->

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
     SAME SYSTEM AS PURCHASE DETAILS PAGE
========================================================= -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        /* =====================================================
           FIND ALL SIDEBAR TOGGLERS
        ====================================================== */

        const togglers =
            document.querySelectorAll(
                ".sidebar-toggler, .navbar-toggler"
            );


        /* =====================================================
           FIND SIDEBAR
        ====================================================== */

        const sidebar =
            document.querySelector(
                ".main-container .sidebar"
            );


        /* =====================================================
           FIND OVERLAY
        ====================================================== */

        const overlay =
            document.getElementById(
                "sidebarOverlay"
            );


        /* =====================================================
           SAFETY CHECK
        ====================================================== */

        if (!sidebar) {

            console.log(
                "Sidebar not found."
            );

            return;
        }


        /* =====================================================
           TOGGLE SIDEBAR
        ====================================================== */

        function toggleSidebar(event) {

            if (event) {

                event.preventDefault();

                event.stopImmediatePropagation();

            }


            /* =================================================
               MOBILE / TABLET
            ================================================= */

            if (window.innerWidth <= 991) {

                document.body.classList.toggle(
                    "sidebar-open"
                );


                document.body.classList.remove(
                    "sidebar-collapsed"
                );

            }


            /* =================================================
               DESKTOP
            ================================================= */

            else {

                document.body.classList.toggle(
                    "sidebar-collapsed"
                );


                document.body.classList.remove(
                    "sidebar-open"
                );

            }

        }


        /* =====================================================
           ADD TOGGLE EVENTS
           CAPTURE TRUE
        ====================================================== */

        togglers.forEach(
            function (toggler) {

                toggler.addEventListener(
                    "click",
                    toggleSidebar,
                    true
                );

            }
        );


        /* =====================================================
           SIDEBAR LINKS
        ====================================================== */

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


                        /* =====================================
                           IGNORE EMPTY LINKS
                        ====================================== */

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


                        /* =====================================
                           IGNORE BOOTSTRAP DROPDOWN
                        ====================================== */

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


                        /* =====================================
                           MOBILE / TABLET
                        ====================================== */

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


        /* =====================================================
           OVERLAY CLICK
        ====================================================== */

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


        /* =====================================================
           OUTSIDE CLICK
        ====================================================== */

        document.addEventListener(
            "click",
            function (event) {


                /* =============================================
                   ONLY MOBILE / TABLET
                ============================================== */

                if (
                    window.innerWidth > 991
                ) {

                    return;
                }


                /* =============================================
                   SIDEBAR NOT OPEN
                ============================================== */

                if (
                    !document.body.classList.contains(
                        "sidebar-open"
                    )
                ) {

                    return;
                }


                /* =============================================
                   CLICK INSIDE SIDEBAR
                ============================================== */

                const clickedInsideSidebar =
                    sidebar.contains(
                        event.target
                    );


                /* =============================================
                   CHECK TOGGLE
                ============================================== */

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


                /* =============================================
                   CLOSE IF OUTSIDE
                ============================================== */

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


        /* =====================================================
           ESC KEY
        ====================================================== */

        document.addEventListener(
            "keydown",
            function (event) {

                if (
                    event.key === "Escape"
                ) {

                    document.body.classList.remove(
                        "sidebar-open"
                    );

                }

            }
        );


        /* =====================================================
           RESIZE FIX
        ====================================================== */

        window.addEventListener(
            "resize",
            function () {


                /* =============================================
                   DESKTOP
                ============================================== */

                if (
                    window.innerWidth > 991
                ) {

                    document.body.classList.remove(
                        "sidebar-open"
                    );

                }


                /* =============================================
                   MOBILE / TABLET
                ============================================== */

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