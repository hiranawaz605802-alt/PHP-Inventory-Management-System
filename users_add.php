<?php

// =====================================================
// USER ADD PAGE
// =====================================================

$current_page = basename($_SERVER["PHP_SELF"]);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <title>DarkPan - Add User</title>

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
         GOOGLE FONT
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


    <!-- =====================================================
         PAGE + SIDEBAR CSS
    ====================================================== -->

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

        .main-page-container {

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
           SAME SYSTEM AS CUSTOMER PURCHASE DETAILS
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

            background:
                rgba(0, 0, 0, 0.55);

            z-index: 99998;
        }


        /* =========================================================
           USER PAGE WRAPPER
        ========================================================= */

        .user-page-wrapper {

            width: 100% !important;

            max-width: 1100px !important;

            margin: 0 auto !important;

            padding: 0 !important;
        }


        /* =========================================================
           USER FORM CARD
        ========================================================= */

        .user-form-card {

            width: 100% !important;

            max-width: 100% !important;

            background: #191c24 !important;

            border-radius: 10px !important;

            padding: 28px !important;
        }


        /* =========================================================
           FORM TITLE
        ========================================================= */

        .user-form-title {

            font-size: 20px;

            font-weight: 600;

            margin-bottom: 25px;
        }


        /* =========================================================
           LABEL
        ========================================================= */

        .user-form-card .form-label {

            font-weight: 600;

            margin-bottom: 8px;
        }


        /* =========================================================
           INPUTS
        ========================================================= */

        .user-form-card .form-control,
        .user-form-card .form-select {

            background: #ffffff !important;

            color: #222222 !important;

            border:
                1px solid
                #ced4da !important;

            min-height: 46px !important;

            border-radius: 6px !important;
        }


        .user-form-card .form-control::placeholder {

            color: #777 !important;
        }


        .user-form-card .form-control:focus,
        .user-form-card .form-select:focus {

            border-color: #86b7fe !important;

            box-shadow:
                0 0 0 .15rem
                rgba(13, 110, 253, .15) !important;
        }


        /* =========================================================
           BUTTONS
        ========================================================= */

        .user-buttons {

            display: flex;

            gap: 10px;

            flex-wrap: wrap;

            margin-top: 10px;
        }


        .user-buttons .btn {

            min-height: 44px;

            padding: 10px 20px;

            font-weight: 600;
        }


        /* =========================================================
           ALERT
        ========================================================= */

        .user-alert {

            border-radius: 6px;

            margin-bottom: 20px;
        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .user-footer {

            margin-top: 25px;
        }


        /* =========================================================
           BACK TO TOP HIDE
        ========================================================= */

        .back-to-top {

            display: none !important;

            visibility: hidden !important;

            opacity: 0 !important;
        }


        /* =========================================================
           TABLET
        ========================================================= */

        @media (max-width: 991px) {

            /* -----------------------------------------------------
               SIDEBAR
            ----------------------------------------------------- */

            .main-page-container .sidebar {

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

                transition:
                    left 0.3s ease !important;

                overflow-y: auto !important;

                overflow-x: hidden !important;
            }


            /* -----------------------------------------------------
               SIDEBAR OPEN
            ----------------------------------------------------- */

            body.sidebar-open
            .main-page-container
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
               USER WRAPPER
            ----------------------------------------------------- */

            .user-page-wrapper {

                width: 100% !important;

                max-width: 100% !important;
            }

        }


        /* =========================================================
           MOBILE
        ========================================================= */

        @media (max-width: 767px) {

            .main-page-container .sidebar {

                width: 250px !important;

                min-width: 250px !important;

                left: -250px !important;
            }


            body.sidebar-open
            .main-page-container
            .sidebar {

                left: 0 !important;
            }


            .content {

                width: 100% !important;

                max-width: 100% !important;

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


            .user-form-card {

                padding: 18px !important;

                border-radius: 8px !important;
            }


            .user-form-title {

                font-size: 18px;
            }


            .user-buttons {

                width: 100%;

                flex-direction: column;
            }


            .user-buttons .btn {

                width: 100%;
            }


            .user-footer {

                text-align: center;
            }

        }


        /* =========================================================
           SMALL MOBILE
        ========================================================= */

        @media (max-width: 480px) {

            .user-form-card {

                padding: 15px !important;
            }


            .user-form-card .form-control,
            .user-form-card .form-select {

                min-height: 44px !important;

                font-size: 14px !important;
            }


            .user-form-card .form-label {

                font-size: 14px;
            }


            .user-form-title {

                font-size: 17px;
            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     MAIN PAGE
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
        id="sidebarOverlay">
    </div>


    <!-- =================================================
         CONTENT
    ================================================== -->

    <div class="content">


        <!-- =================================================
             NAVBAR
        ================================================== -->

        <?php include("navbar.php"); ?>


        <!-- =================================================
             USER FORM AREA
        ================================================== -->

        <div class="container-fluid pt-4 px-4">


            <div class="user-page-wrapper">


                <!-- =================================================
                     FORM CARD
                ================================================= -->

                <div class="user-form-card">


                    <!-- =================================================
                         SUCCESS MESSAGE
                    ================================================= -->

                    <?php

                    if (isset($_GET["success"])) {

                    ?>

                        <div
                            class="alert alert-success user-alert"
                            role="alert">

                            <i
                                class="fa fa-check-circle me-2">
                            </i>

                            User added successfully.

                        </div>

                    <?php

                    }


                    /* =================================================
                       ERROR MESSAGE
                    ================================================= */

                    if (isset($_GET["error"])) {

                    ?>

                        <div
                            class="alert alert-danger user-alert"
                            role="alert">

                            <i
                                class="fa fa-exclamation-circle me-2">
                            </i>

                            <?php

                            echo htmlspecialchars(
                                $_GET["error"],
                                ENT_QUOTES,
                                "UTF-8"
                            );

                            ?>

                        </div>

                    <?php

                    }

                    ?>


                    <!-- =================================================
                         HEADING
                    ================================================= -->

                    <h6 class="user-form-title">

                        <i
                            class="fa fa-user-plus me-2">
                        </i>

                        Add User

                    </h6>


                    <!-- =================================================
                         FORM
                    ================================================= -->

                    <form
                        method="POST"
                        action="users_insert.php"
                        autocomplete="off"
                    >


                        <!-- =================================================
                             NAME + EMAIL
                        ================================================= -->

                        <div class="row">


                            <!-- NAME -->

                            <div class="col-md-6 mb-3">

                                <label
                                    for="name"
                                    class="form-label"
                                >

                                    Name

                                </label>


                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control"
                                    placeholder="Enter User Name"
                                    autocomplete="name"
                                    required
                                >

                            </div>


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

                        </div>


                        <!-- =================================================
                             PASSWORD + STATUS
                        ================================================== -->

                        <div class="row">


                            <!-- PASSWORD -->

                            <div class="col-md-6 mb-3">

                                <label
                                    for="password"
                                    class="form-label"
                                >

                                    Password

                                </label>


                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control"
                                    placeholder="Enter Password"
                                    autocomplete="new-password"
                                    minlength="6"
                                    required
                                >

                            </div>


                            <!-- STATUS -->

                            <div class="col-md-6 mb-3">

                                <label
                                    for="status"
                                    class="form-label"
                                >

                                    Status

                                </label>


                                <select
                                    name="status"
                                    id="status"
                                    class="form-select"
                                    required
                                >

                                    <option
                                        value="Active"
                                        selected
                                    >

                                        Active

                                    </option>


                                    <option
                                        value="Inactive"
                                    >

                                        Inactive

                                    </option>

                                </select>

                            </div>

                        </div>


                        <!-- =================================================
                             BUTTONS
                        ================================================== -->

                        <div class="user-buttons">


                            <!-- SAVE -->

                            <button
                                type="submit"
                                name="save_user"
                                class="btn btn-primary"
                            >

                                <i
                                    class="fa fa-save me-1">
                                </i>

                                Save User

                            </button>


                            <!-- RESET -->

                            <button
                                type="reset"
                                class="btn btn-danger"
                            >

                                <i
                                    class="fa fa-refresh me-1">
                                </i>

                                Reset

                            </button>


                        </div>


                    </form>


                </div>


            </div>


        </div>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <div
            class="container-fluid pt-4 px-4 user-footer"
        >

            <div
                class="bg-secondary rounded-top p-4"
            >

                <div class="row">


                    <!-- LEFT -->

                    <div
                        class="col-12 col-sm-6
                               text-center text-sm-start"
                    >

                        &copy;

                        <a
                            href="#"
                            class="text-decoration-none"
                        >

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


<!-- =====================================================
     JQUERY
====================================================== -->

<script
    src="https://code.jquery.com/jquery-3.4.1.min.js">
</script>


<!-- =====================================================
     BOOTSTRAP
====================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js">
</script>


<!-- =====================================================
     DARKPAN LIBRARIES
====================================================== -->

<script
    src="lib/chart/chart.min.js">
</script>

<script
    src="lib/easing/easing.min.js">
</script>

<script
    src="lib/waypoints/waypoints.min.js">
</script>

<script
    src="lib/owlcarousel/owl.carousel.min.js">
</script>

<script
    src="lib/tempusdominus/js/moment.min.js">
</script>

<script
    src="lib/tempusdominus/js/moment-timezone.min.js">
</script>

<script
    src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js">
</script>


<!-- =====================================================
     DARKPAN MAIN JS
====================================================== -->

<script
    src="js/main.js">
</script>


<!-- =====================================================
     SIDEBAR FIX
     SAME JAVASCRIPT AS CUSTOMER PAGE
====================================================== -->

<script>

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
                ".main-page-container .sidebar"
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
                           CLOSE MOBILE SIDEBAR
                        ================================= */

                        document.body.classList.remove(
                            "sidebar-open"
                        );


                        /* =================================
                           MOBILE
                        ================================= */

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