```php
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <title>DarkPan - Bootstrap 5 Admin Template</title>

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
         DARKPAN
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
   SAME AS PURCHASE DETAILS CODE
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
   DASHBOARD CONTENT
========================================================= */

.dashboard-content {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;

    min-height: calc(100vh - 70px);

    margin: 0 !important;

    padding: 24px !important;

    background: #000 !important;

    overflow-x: hidden !important;
}


/* =========================================================
   SALES CARD
========================================================= */

.sales-card {

    width: 100% !important;

    max-width: 100% !important;

    min-width: 0 !important;
}


.sales-card canvas {

    width: 100% !important;

    max-width: 100% !important;

    max-height: 350px;
}


/* =========================================================
   FOOTER
========================================================= */

.dashboard-footer {

    width: 100% !important;

    max-width: 100% !important;

    margin-top: 24px;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 991px) {

    /* ---------------------------------------------
       SIDEBAR
    --------------------------------------------- */

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


    /* ---------------------------------------------
       SIDEBAR OPEN
    --------------------------------------------- */

    body.sidebar-open
    .main-container
    .sidebar {

        left: 0 !important;
    }


    /* ---------------------------------------------
       OVERLAY
    --------------------------------------------- */

    body.sidebar-open
    .sidebar-mobile-overlay {

        display: block !important;
    }


    /* ---------------------------------------------
       CONTENT
    --------------------------------------------- */

    .content {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        margin-left: 0 !important;

        padding: 0 !important;
    }


    /* ---------------------------------------------
       NAVBAR
    --------------------------------------------- */

    .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        padding-left: 15px !important;

        padding-right: 15px !important;
    }


    /* ---------------------------------------------
       DASHBOARD
    --------------------------------------------- */

    .dashboard-content {

        width: 100% !important;

        max-width: 100% !important;

        padding: 15px !important;

        margin: 0 !important;
    }


    /* ---------------------------------------------
       FOOTER
    --------------------------------------------- */

    .dashboard-footer {

        margin-top: 15px;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767px) {

    /* ---------------------------------------------
       SIDEBAR
    --------------------------------------------- */

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


    /* ---------------------------------------------
       CONTENT
    --------------------------------------------- */

    .content {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        margin-left: 0 !important;

        padding: 0 !important;
    }


    /* ---------------------------------------------
       NAVBAR
    --------------------------------------------- */

    .content > .navbar {

        width: 100% !important;

        min-height: 70px !important;

        margin: 0 !important;

        padding-left: 10px !important;

        padding-right: 10px !important;
    }


    /* ---------------------------------------------
       DASHBOARD
    --------------------------------------------- */

    .dashboard-content {

        width: 100% !important;

        max-width: 100% !important;

        min-width: 0 !important;

        padding: 8px !important;

        margin: 0 !important;

        overflow-x: hidden !important;
    }


    /* ---------------------------------------------
       SALES CARD
    --------------------------------------------- */

    .sales-card {

        width: 100% !important;

        max-width: 100% !important;

        padding: 12px !important;
    }


    .sales-card canvas {

        width: 100% !important;

        max-width: 100% !important;
    }


    /* ---------------------------------------------
       FOOTER
    --------------------------------------------- */

    .dashboard-footer {

        width: 100% !important;

        max-width: 100% !important;

        margin-top: 15px;
    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 575px) {

    .dashboard-content {

        padding: 6px !important;
    }


    .sales-card {

        padding: 10px !important;
    }


    .dashboard-footer {

        margin-top: 10px;
    }

}


/* =========================================================
   VERY SMALL MOBILE
========================================================= */

@media (max-width: 400px) {

    .dashboard-content {

        padding: 5px !important;
    }


    .sales-card {

        padding: 8px !important;
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

    <?php
        include("sidebar.php");
    ?>


    <!-- =================================================
         MOBILE SIDEBAR OVERLAY
    ================================================== -->

    <div
        class="sidebar-mobile-overlay"
        id="sidebarOverlay"
    ></div>


    <!-- =================================================
         CONTENT START
    ================================================== -->

    <div class="content">


        <!-- =================================================
             NAVBAR
        ================================================== -->

        <?php
            include("navbar.php");
        ?>


        <!-- =================================================
             DASHBOARD CONTENT
        ================================================== -->

        <div class="dashboard-content">


            <!-- =================================================
                 SALES CHART
            ================================================== -->

            <div class="container-fluid px-0">

                <div class="row g-4">


                    <div class="col-12 col-xl-8">


                        <div
                            class="
                                bg-secondary
                                text-center
                                rounded
                                p-4
                                sales-card
                            "
                        >


                            <div
                                class="
                                    d-flex
                                    align-items-center
                                    justify-content-between
                                    mb-4
                                "
                            >

                                <h6 class="mb-0">

                                    Worldwide Sales

                                </h6>


                                <a href="#">

                                    Show All

                                </a>

                            </div>


                            <div
                                style="
                                    width:100%;
                                    height:350px;
                                "
                            >

                                <canvas
                                    id="worldwide-sales"
                                ></canvas>

                            </div>


                        </div>


                    </div>


                </div>

            </div>


            <!-- =================================================
                 FOOTER
            ================================================== -->

            <div
                class="
                    container-fluid
                    px-0
                    dashboard-footer
                "
            >


                <div
                    class="
                        bg-secondary
                        rounded-top
                        p-4
                    "
                >


                    <div class="row">


                        <div
                            class="
                                col-12
                                col-sm-6
                                text-center
                                text-sm-start
                            "
                        >

                            &copy;

                            <a href="#">

                                Your Site Name

                            </a>,

                            All Right Reserved.

                        </div>


                        <div
                            class="
                                col-12
                                col-sm-6
                                text-center
                                text-sm-end
                            "
                        >

                            Designed By

                            <a
                                href="https://htmlcodex.com"
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


        <!-- =================================================
             CONTENT END
        ================================================== -->


    </div>


    <!-- =====================================================
         BACK TO TOP
    ====================================================== -->

    <a
        href="#"
        class="
            btn
            btn-lg
            btn-primary
            btn-lg-square
            back-to-top
        "
    >

        <i class="bi bi-arrow-up"></i>

    </a>


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
     CHART
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

<script
    src="js/main.js"
></script>


<!-- =====================================================
     SIDEBAR FIX
====================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        /* =================================================
           FIND SIDEBAR TOGGLERS
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


            /* ---------------------------------------------
               MOBILE / TABLET
            --------------------------------------------- */

            if (window.innerWidth <= 991) {

                document.body.classList.toggle(
                    "sidebar-open"
                );

                document.body.classList.remove(
                    "sidebar-collapsed"
                );

            }


            /* ---------------------------------------------
               DESKTOP
            --------------------------------------------- */

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
           ADD TOGGLER EVENTS
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


                        /* ---------------------------------
                           IGNORE EMPTY LINKS
                        --------------------------------- */

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


                        /* ---------------------------------
                           IGNORE DROPDOWN BUTTONS
                        --------------------------------- */

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


                        /* ---------------------------------
                           CLOSE MOBILE SIDEBAR
                        --------------------------------- */

                        document.body.classList.remove(
                            "sidebar-open"
                        );


                        /* ---------------------------------
                           MOBILE / TABLET
                        --------------------------------- */

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


<!-- =====================================================
     WORLDWIDE SALES CHART
====================================================== -->

<script>

const ctx =
    document.getElementById(
        "worldwide-sales"
    );


if (ctx) {

    new Chart(
        ctx,
        {

            type: "line",

            data: {

                labels: [

                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec"

                ],

                datasets: [

                    {

                        label: "Sales",

                        data: [

                            15,
                            25,
                            20,
                            30,
                            28,
                            35,
                            32,
                            40,
                            38,
                            45,
                            42,
                            50

                        ],

                        fill: true,

                        tension: 0.4

                    }

                ]

            },


            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        display: true

                    }

                },


                scales: {

                    y: {

                        beginAtZero: true

                    }

                }

            }

        }
    );

}

</script>


</body>

</html>
```
