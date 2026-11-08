<?php
// =====================================================
// NAVBAR
// =====================================================


// =====================================================
// PURCHASE DETAILS PAGE
// =====================================================
// YAHAN APNI ACTUAL PURCHASE DETAILS FILE KA NAAM LIKHEN
// =====================================================

$purchase_page = "all2.php";


// =====================================================
// CURRENT SEARCH PHONE
// =====================================================

$navbar_phone = isset($_GET["phone_number"])
    ? trim($_GET["phone_number"])
    : "";

?>

<!-- =====================================================
     NAVBAR START
===================================================== -->

<nav
    class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0"
    style="z-index:100000;"
>


    <!-- =================================================
         MOBILE LOGO
    ================================================== -->

    <a
        href="index.php"
        class="navbar-brand d-flex d-lg-none me-4"
    >

        <h2 class="text-primary mb-0">

            <i class="fa fa-user-edit"></i>

        </h2>

    </a>


    <!-- =================================================
         SIDEBAR TOGGLE
    ================================================== -->

    <a
        href="#"
        class="sidebar-toggler flex-shrink-0"
        id="navbarSidebarToggler"
    >

        <i class="fa fa-bars"></i>

    </a>


    <!-- =================================================
         PHONE SEARCH
    ================================================== -->

    <form
        class="d-none d-md-flex ms-4 navbar-phone-search"
        method="GET"
        action="<?php echo htmlspecialchars(
            $purchase_page,
            ENT_QUOTES,
            "UTF-8"
        ); ?>"
    >

        <div
            class="navbar-search-wrapper"
        >

            <input
                type="search"
                name="phone_number"
                value="<?php echo htmlspecialchars(
                    $navbar_phone,
                    ENT_QUOTES,
                    "UTF-8"
                ); ?>"
                class="form-control bg-dark border-0 navbar-search-input"
                placeholder="Search customer phone..."
                autocomplete="off"
            >

            <button
                type="submit"
                class="navbar-search-button"
                title="Search Customer"
            >

                <i class="fa fa-search"></i>

            </button>

        </div>

    </form>


    <!-- =================================================
         RIGHT NAVBAR
    ================================================== -->

    <div
        class="navbar-nav align-items-center ms-auto"
    >


        <!-- =================================================
             MESSAGE
        ================================================== -->

        <div class="nav-item dropdown">

            <a
                href="#"
                class="nav-link dropdown-toggle"
                data-bs-toggle="dropdown"
            >

                <i class="fa fa-envelope me-lg-2"></i>

                <span class="d-none d-lg-inline-flex">
                    Message
                </span>

            </a>


            <div
                class="
                    dropdown-menu
                    dropdown-menu-end
                    bg-secondary
                    border-0
                    rounded-0
                    rounded-bottom
                    m-0
                "
            >

                <a
                    href="#"
                    class="dropdown-item"
                >

                    <div
                        class="d-flex align-items-center"
                    >

                        <img
                            class="rounded-circle"
                            src="img/user.jpg"
                            alt=""
                            style="
                                width:40px;
                                height:40px;
                                object-fit:cover;
                            "
                        >

                        <div class="ms-2">

                            <h6
                                class="fw-normal mb-0"
                            >
                                John sent you a message
                            </h6>

                            <small>
                                15 minutes ago
                            </small>

                        </div>

                    </div>

                </a>


                <hr
                    class="dropdown-divider"
                >


                <a
                    href="#"
                    class="dropdown-item text-center"
                >
                    See all messages
                </a>

            </div>

        </div>


        <!-- =================================================
             NOTIFICATION
        ================================================== -->

        <div class="nav-item dropdown">

            <a
                href="#"
                class="nav-link dropdown-toggle"
                data-bs-toggle="dropdown"
            >

                <i class="fa fa-bell me-lg-2"></i>

                <span class="d-none d-lg-inline-flex">
                    Notification
                </span>

            </a>


            <div
                class="
                    dropdown-menu
                    dropdown-menu-end
                    bg-secondary
                    border-0
                    rounded-0
                    rounded-bottom
                    m-0
                "
            >

                <a
                    href="#"
                    class="dropdown-item"
                >

                    <h6
                        class="fw-normal mb-0"
                    >
                        Profile updated
                    </h6>

                    <small>
                        15 minutes ago
                    </small>

                </a>


                <hr
                    class="dropdown-divider"
                >


                <a
                    href="#"
                    class="dropdown-item"
                >

                    <h6
                        class="fw-normal mb-0"
                    >
                        New user added
                    </h6>

                    <small>
                        15 minutes ago
                    </small>

                </a>


                <hr
                    class="dropdown-divider"
                >


                <a
                    href="#"
                    class="dropdown-item text-center"
                >
                    See all notifications
                </a>

            </div>

        </div>


        <!-- =================================================
             USER
        ================================================== -->

        <div class="nav-item dropdown">

            <a
                href="#"
                class="nav-link dropdown-toggle"
                data-bs-toggle="dropdown"
            >

                <img
                    class="rounded-circle me-lg-2"
                    src="img/user.jpg"
                    alt=""
                    style="
                        width:40px;
                        height:40px;
                        object-fit:cover;
                    "
                >

                <span class="d-none d-lg-inline-flex">
                    John Doe
                </span>

            </a>


            <div
                class="
                    dropdown-menu
                    dropdown-menu-end
                    bg-secondary
                    border-0
                    rounded-0
                    rounded-bottom
                    m-0
                "
            >

                <a
                    href="#"
                    class="dropdown-item"
                >
                    My Profile
                </a>


                <a
                    href="#"
                    class="dropdown-item"
                >
                    Settings
                </a>


                <a
                    href="logout.php"
                    class="dropdown-item"
                >
                    Log Out
                </a>

            </div>

        </div>


    </div>

</nav>


<!-- =====================================================
     NAVBAR END
===================================================== -->


<!-- =====================================================
     NAVBAR SEARCH CSS
===================================================== -->

<style>

.navbar-phone-search {

    width: 360px !important;

    max-width: 360px !important;

    min-width: 0 !important;

}


.navbar-search-wrapper {

    width: 100% !important;

    height: 40px !important;

    display: flex !important;

    align-items: center !important;

    position: relative !important;

}


.navbar-search-input {

    width: 100% !important;

    height: 40px !important;

    padding-right: 45px !important;

    color: #fff !important;

    border-radius: 6px !important;

}


.navbar-search-input::placeholder {

    color: #8f9bc0 !important;

    opacity: 1 !important;

}


.navbar-search-input:focus {

    outline: none !important;

    box-shadow:
        0 0 0 2px
        rgba(13,110,253,0.35) !important;

}


.navbar-search-button {

    width: 40px !important;

    height: 40px !important;

    position: absolute !important;

    top: 0 !important;

    right: 0 !important;

    border: 0 !important;

    background: transparent !important;

    color: #8f9bc0 !important;

    cursor: pointer !important;

    border-radius: 0 6px 6px 0 !important;

}


.navbar-search-button:hover {

    color: #0d6efd !important;

}


.navbar-search-button:focus {

    outline: none !important;

}


@media (max-width: 1199px) {

    .navbar-phone-search {

        width: 300px !important;

        max-width: 300px !important;

    }

}


@media (max-width: 991px) {

    .navbar-phone-search {

        width: 280px !important;

        max-width: 280px !important;

        margin-left: 15px !important;

    }

}


@media (max-width: 767px) {

    .navbar-phone-search {

        display: none !important;

    }

}

</style>



            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-line fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Today Sale</p>
                                <h6 class="mb-0">$1234</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-bar fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Total Sale</p>
                                <h6 class="mb-0">$1234</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-area fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Today Revenue</p>
                                <h6 class="mb-0">$1234</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-pie fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Total Revenue</p>
                                <h6 class="mb-0">$1234</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sale & Revenue End -->


