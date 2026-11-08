<?php
/*
|--------------------------------------------------------------------------
| SIDEBAR
|--------------------------------------------------------------------------
| Same project links - only design and navigation improved
|--------------------------------------------------------------------------
*/
?>

<!-- =====================================================
     SIDEBAR START
===================================================== -->

<aside class="sidebar">

    <!-- =================================================
         SIDEBAR HEADER / LOGO
    ================================================= -->

    <div class="sidebar-header">

        <a href="index.php" class="sidebar-logo">

            <span class="logo-icon">
                <i class="fa fa-layer-group"></i>
            </span>

            <span class="logo-text">
                DarkPan
            </span>

        </a>

    </div>


    <!-- =================================================
         USER PROFILE
    ================================================= -->

    <div class="sidebar-user">

        <div class="user-image">

            <img
                src="img/user.jpg"
                alt="Admin"
            >

            <span class="online-dot"></span>

        </div>


        <div class="user-info">

            <h6>
                Jhon Doe
            </h6>

            <span>
                <i class="fa fa-circle"></i>
                Administrator
            </span>

        </div>

    </div>


    <!-- =================================================
         MENU TITLE
    ================================================= -->

    <div class="menu-title">
        MAIN MENU
    </div>


    <!-- =================================================
         SIDEBAR MENU
    ================================================= -->

    <div class="sidebar-menu">


        <!-- =================================================
             DASHBOARD
        ================================================= -->

        <a
            href="index.php"
            class="side-link"
        >

            <span class="side-icon">
                <i class="fa fa-home"></i>
            </span>

            <span class="side-text">
                Dashboard
            </span>

        </a>


        <!-- =================================================
             SEARCH AND FIND
        ================================================= -->

        <div class="side-dropdown">

            <a
                href="javascript:void(0);"
                class="side-link dropdown-btn"
            >

                <span class="side-icon">
                    <i class="fa fa-search"></i>
                </span>

                <span class="side-text">
                    Search & Find
                </span>

                <i class="fa fa-chevron-right dropdown-arrow"></i>

            </a>


            <div class="side-submenu">

                <a href="all.php">
                    <i class="fa fa-users"></i>
                    Select All Customer
                </a>

                <a href="customer_number_check.php">
                    <i class="fa fa-phone"></i>
                    Find Number Customer
                </a>

            </div>

        </div>


        <!-- =================================================
             USERS
        ================================================= -->

        <div class="side-dropdown">

            <a
                href="javascript:void(0);"
                class="side-link dropdown-btn"
            >

                <span class="side-icon">
                    <i class="fa fa-users"></i>
                </span>

                <span class="side-text">
                    Users
                </span>

                <i class="fa fa-chevron-right dropdown-arrow"></i>

            </a>


            <div class="side-submenu">

                <a href="users_add.php">
                    <i class="fa fa-user-plus"></i>
                    Users Add
                </a>

                <a href="User_view_product.php">
                    <i class="fa fa-user-check"></i>
                    Users View
                </a>

            </div>

        </div>


        <!-- =================================================
             PRODUCTS
        ================================================= -->


        <!-- =================================================
             CUSTOMERS
        ================================================= -->

        <div class="side-dropdown">

            <a
                href="javascript:void(0);"
                class="side-link dropdown-btn"
            >

                <span class="side-icon">
                    <i class="fa fa-user-friends"></i>
                </span>

                <span class="side-text">
                    Customers
                </span>

                <i class="fa fa-chevron-right dropdown-arrow"></i>

            </a>


            <div class="side-submenu">

                <a href="customer_product_add.php">
                    <i class="fa fa-user-plus"></i>
                    Customer Add
                </a>

                <a href="customer_product_view.php">
                    <i class="fa fa-address-book"></i>
                    Customer View
                </a>

            </div>

        </div>




        <div class="side-dropdown">

            <a
                href="javascript:void(0);"
                class="side-link dropdown-btn"
            >

                <span class="side-icon">
                    <i class="fa fa-box"></i>
                </span>

                <span class="side-text">
                    Products
                </span>

                <i class="fa fa-chevron-right dropdown-arrow"></i>

            </a>


            <div class="side-submenu">

                <a href="product_add.php">
                    <i class="fa fa-plus-circle"></i>
                    Product Add
                </a>

                <a href="product_view.php">
                    <i class="fa fa-boxes"></i>
                    Product View
                </a>

            </div>
        </div>


        <!-- =================================================
             SHOPPING CART
        ================================================= -->

        <div class="side-dropdown">

            <a
                href="javascript:void(0);"
                class="side-link dropdown-btn"
            >

                <span class="side-icon">
                    <i class="fa fa-shopping-cart"></i>
                </span>

                <span class="side-text">
                    Shopping Cart
                </span>

                <i class="fa fa-chevron-right dropdown-arrow"></i>

            </a>


            <div class="side-submenu">

                <a href="product_cart_item.php">
                    <i class="fa fa-cart-plus"></i>
                    Shopping Cart
                </a>

                <a href="product_cart_item_view.php">
                    <i class="fa fa-eye"></i>
                    Shopping Cart View
                </a>

            </div>

        </div>


        <!-- =================================================
             CART ITEMS
        ================================================= -->

        <div class="side-dropdown">

            <a
                href="javascript:void(0);"
                class="side-link dropdown-btn"
            >

                <span class="side-icon">
                    <i class="fa fa-cart-arrow-down"></i>
                </span>

                <span class="side-text">
                    Cart Items
                </span>

                <i class="fa fa-chevron-right dropdown-arrow"></i>

            </a>


            <div class="side-submenu">

                <a href="cart_items_product.php">
                    <i class="fa fa-plus"></i>
                    Cart Item
                </a>

                <a href="cart_items_product_view.php">
                    <i class="fa fa-list"></i>
                    Cart Item View
                </a>

            </div>

        </div>


        <!-- =================================================
             ORDER
        ================================================= -->

        <div class="side-dropdown">

            <a
                href="javascript:void(0);"
                class="side-link dropdown-btn"
            >

                <span class="side-icon">
                    <i class="fa fa-file-invoice"></i>
                </span>

                <span class="side-text">
                    Orders
                </span>

                <i class="fa fa-chevron-right dropdown-arrow"></i>

            </a>


            <div class="side-submenu">

                <a href="order_product.php">
                    <i class="fa fa-plus-circle"></i>
                    Order Product
                </a>

                <a href="order_product_view.php">
                    <i class="fa fa-eye"></i>
                    Order View
                </a>

            </div>

        </div>


        <!-- =================================================
             ORDER ITEMS
        ================================================= -->

        <div class="side-dropdown">

            <a
                href="javascript:void(0);"
                class="side-link dropdown-btn"
            >

                <span class="side-icon">
                    <i class="fa fa-clipboard-list"></i>
                </span>

                <span class="side-text">
                    Order Items
                </span>

                <i class="fa fa-chevron-right dropdown-arrow"></i>

            </a>


            <div class="side-submenu">

                <a href="order_item_product.php">
                    <i class="fa fa-plus"></i>
                    Order Item Product
                </a>

                <a href="order_item_product_view.php">
                    <i class="fa fa-list"></i>
                    Order Item View
                </a>

            </div>

        </div>


        <!-- =================================================
             PAYMENTS
        ================================================= -->

        <div class="side-dropdown">

            <a
                href="javascript:void(0);"
                class="side-link dropdown-btn"
            >

                <span class="side-icon">
                    <i class="fa fa-wallet"></i>
                </span>

                <span class="side-text">
                    Payments
                </span>

                <i class="fa fa-chevron-right dropdown-arrow"></i>

            </a>


            <div class="side-submenu">

                <a href="payement_product.php">
                    <i class="fa fa-money-bill"></i>
                    Payment Product
                </a>

                <a href="payement_product_view.php">
                    <i class="fa fa-receipt"></i>
                    Payment View
                </a>

            </div>

        </div>


    </div>


    <!-- =================================================
         SIDEBAR FOOTER
    ================================================= -->

    <div class="sidebar-footer">

        <div class="footer-line"></div>

        <span>
            <i class="fa fa-shield-alt"></i>
            Admin Panel
        </span>

    </div>

</aside>


<!-- =====================================================
     SIDEBAR CSS
===================================================== -->

<style>

/* =====================================================
   RESET
===================================================== */

.sidebar,
.sidebar * {
    box-sizing: border-box;
}


/* =====================================================
   SIDEBAR MAIN
===================================================== */

.sidebar {

    position: fixed;

    top: 0;
    left: 0;

    width: 260px;
    height: 100vh;

    background: #191c24;

    z-index: 99999;

    overflow-y: auto;
    overflow-x: hidden;

    border-right: 1px solid rgba(255,255,255,0.06);

    transition: all 0.3s ease;

    scrollbar-width: thin;
}


/* =====================================================
   SCROLLBAR
===================================================== */

.sidebar::-webkit-scrollbar {
    width: 5px;
}

.sidebar::-webkit-scrollbar-track {
    background: #191c24;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #3d414d;
    border-radius: 10px;
}


/* =====================================================
   HEADER
===================================================== */

.sidebar-header {

    height: 76px;

    display: flex;

    align-items: center;

    padding: 0 24px;

    border-bottom: 1px solid rgba(255,255,255,0.07);

}


/* =====================================================
   LOGO
===================================================== */

.sidebar-logo {

    width: 100%;

    display: flex;

    align-items: center;

    gap: 12px;

    text-decoration: none;

}


.logo-icon {

    width: 38px;
    height: 38px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: rgba(13,202,240,0.12);

    border-radius: 10px;

    color: #0dcaf0;

    font-size: 17px;

}


.logo-text {

    color: #ffffff;

    font-size: 21px;

    font-weight: 700;

    letter-spacing: 0.3px;

}


/* =====================================================
   USER PROFILE
===================================================== */

.sidebar-user {

    margin: 20px 16px;

    padding: 14px;

    display: flex;

    align-items: center;

    gap: 12px;

    background: rgba(255,255,255,0.045);

    border: 1px solid rgba(255,255,255,0.06);

    border-radius: 12px;

}


.user-image {

    position: relative;

    flex-shrink: 0;

}


.user-image img {

    width: 44px;
    height: 44px;

    object-fit: cover;

    border-radius: 50%;

    border: 2px solid rgba(13,202,240,0.6);

}


.online-dot {

    position: absolute;

    right: 1px;
    bottom: 1px;

    width: 11px;
    height: 11px;

    background: #20c997;

    border: 2px solid #191c24;

    border-radius: 50%;

}


.user-info {

    min-width: 0;

}


.user-info h6 {

    margin: 0 0 4px;

    color: #ffffff;

    font-size: 14px;

    font-weight: 600;

}


.user-info span {

    color: #8f96a3;

    font-size: 11px;

}


.user-info span i {

    color: #20c997;

    font-size: 7px;

    margin-right: 4px;

}


/* =====================================================
   MENU TITLE
===================================================== */

.menu-title {

    padding: 4px 24px 10px;

    color: #6f7684;

    font-size: 10px;

    font-weight: 700;

    letter-spacing: 1.2px;

}


/* =====================================================
   MENU
===================================================== */

.sidebar-menu {

    padding: 0 12px 20px;

}


/* =====================================================
   MAIN LINK
===================================================== */

.side-link {

    position: relative;

    width: 100%;

    min-height: 48px;

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 0 13px;

    margin-bottom: 4px;

    color: #aeb5c1;

    text-decoration: none;

    border-radius: 9px;

    cursor: pointer;

    transition: all 0.2s ease;

}


.side-link:hover {

    color: #ffffff;

    background: rgba(255,255,255,0.065);

}


.side-icon {

    width: 22px;

    min-width: 22px;

    display: flex;

    justify-content: center;

    align-items: center;

    color: #7d8593;

    font-size: 15px;

    transition: 0.2s ease;

}


.side-link:hover .side-icon {

    color: #0dcaf0;

}


.side-text {

    flex: 1;

    font-size: 13px;

    font-weight: 500;

}


/* =====================================================
   DROPDOWN ARROW
===================================================== */

.dropdown-arrow {

    font-size: 9px;

    color: #6f7684;

    transition: transform 0.25s ease;

}


/* =====================================================
   OPEN DROPDOWN
===================================================== */

.side-dropdown.active > .side-link {

    color: #ffffff;

    background: rgba(13,202,240,0.09);

}


.side-dropdown.active > .side-link .side-icon {

    color: #0dcaf0;

}


.side-dropdown.active > .side-link .dropdown-arrow {

    transform: rotate(90deg);

    color: #0dcaf0;

}


/* =====================================================
   SUBMENU
===================================================== */

.side-submenu {

    display: none;

    padding: 3px 0 7px 0;

}


.side-dropdown.active .side-submenu {

    display: block;

}


.side-submenu a {

    position: relative;

    min-height: 38px;

    display: flex;

    align-items: center;

    gap: 10px;

    margin: 2px 0 2px 36px;

    padding: 7px 10px;

    color: #858c99;

    font-size: 12px;

    text-decoration: none;

    border-left: 1px solid #353a46;

    transition: all 0.2s ease;

}


.side-submenu a i {

    width: 14px;

    text-align: center;

    font-size: 10px;

}


.side-submenu a:hover {

    color: #ffffff;

    background: rgba(255,255,255,0.045);

    border-left-color: #0dcaf0;

    padding-left: 14px;

}


.side-submenu a:hover i {

    color: #0dcaf0;

}


/* =====================================================
   FOOTER
===================================================== */

.sidebar-footer {

    padding: 10px 24px 20px;

    color: #606775;

    font-size: 10px;

}


.footer-line {

    height: 1px;

    background: rgba(255,255,255,0.06);

    margin-bottom: 12px;

}


.sidebar-footer i {

    margin-right: 5px;

    color: #0dcaf0;

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 991px) {

    .sidebar {

        left: -260px !important;

        width: 260px !important;

        box-shadow: none;

    }


    body.sidebar-open .sidebar {

        left: 0 !important;

        box-shadow: 10px 0 35px rgba(0,0,0,0.45);

    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media (max-width: 576px) {

    .sidebar {

        width: 270px !important;

        left: -270px !important;

    }


    body.sidebar-open .sidebar {

        left: 0 !important;

    }

}

</style>


<!-- =====================================================
     SIDEBAR JAVASCRIPT
===================================================== -->

<script>

document.addEventListener("DOMContentLoaded", function () {

    const sidebar = document.querySelector(".sidebar");

    if (!sidebar) {
        return;
    }


    /* =================================================
       DROPDOWN BUTTONS
    ================================================= */

    const dropdownButtons =
        sidebar.querySelectorAll(".dropdown-btn");


    dropdownButtons.forEach(function (button) {

        button.addEventListener("click", function (event) {

            event.preventDefault();

            event.stopPropagation();


            const parent =
                this.closest(".side-dropdown");


            /*
             * Agar doosra dropdown open hai
             * to usko close karo
             */

            sidebar
                .querySelectorAll(".side-dropdown.active")
                .forEach(function (item) {

                    if (item !== parent) {
                        item.classList.remove("active");
                    }

                });


            /*
             * Current dropdown toggle
             */

            parent.classList.toggle("active");

        });

    });


    /* =================================================
       SUBMENU LINKS
    ================================================= */

    sidebar
        .querySelectorAll(".side-submenu a")
        .forEach(function (link) {

            link.addEventListener("click", function (event) {

                event.stopPropagation();

                const href = this.getAttribute("href");


                if (!href || href === "#") {
                    return;
                }


                /*
                 * Mobile par sidebar close
                 */

                if (window.innerWidth <= 991) {

                    document.body.classList.remove(
                        "sidebar-open"
                    );

                }


                /*
                 * Direct navigation
                 */

                window.location.href = href;

            });

        });


    /* =================================================
       NORMAL SIDEBAR LINKS
    ================================================= */

    sidebar
        .querySelectorAll(
            '.side-link:not(.dropdown-btn)'
        )
        .forEach(function (link) {

            link.addEventListener("click", function () {

                const href =
                    this.getAttribute("href");


                if (!href || href === "#") {
                    return;
                }


                if (window.innerWidth <= 991) {

                    document.body.classList.remove(
                        "sidebar-open"
                    );

                }


                window.location.href = href;

            });

        });


    /* =================================================
       CLICK OUTSIDE
    ================================================= */

    document.addEventListener("click", function (event) {

        if (window.innerWidth > 991) {
            return;
        }


        const clickedInside =
            sidebar.contains(event.target);


        const toggle =
            event.target.closest(".sidebar-toggler");


        if (
            !clickedInside &&
            !toggle &&
            document.body.classList.contains(
                "sidebar-open"
            )
        ) {

            document.body.classList.remove(
                "sidebar-open"
            );

        }

    });


    /* =================================================
       RESIZE
    ================================================= */

    window.addEventListener("resize", function () {

        if (window.innerWidth > 991) {

            document.body.classList.remove(
                "sidebar-open"
            );

        }

    });

});

</script>


<!-- =====================================================
     SIDEBAR END
===================================================== -->