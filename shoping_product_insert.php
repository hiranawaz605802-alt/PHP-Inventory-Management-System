
<?php

include("Database.php");

$db = new Database();
$conn = $db->connection();


// =====================================================
// CHECK FORM SUBMIT
// =====================================================

if (!isset($_POST["add_to_cart"])) {

    header("Location: product_cart_item_view.php");
    exit();

}


// =====================================================
// GET CUSTOMER ID
// =====================================================

$customer_id = isset($_POST["customer_id"])
    ? intval($_POST["customer_id"])
    : 0;


// =====================================================
// CHECK CUSTOMER ID
// =====================================================

if ($customer_id <= 0) {

    echo "<script>
            alert('Please Select Customer');
            window.location.href='product_cart_item_view.php';
          </script>";

    exit();

}


// =====================================================
// CHECK CUSTOMER EXISTS
// =====================================================

$customer_sql = "
    SELECT customer_id
    FROM entity_asad_customer
    WHERE customer_id = ?
    LIMIT 1
";


$customer_stmt = mysqli_prepare(
    $conn,
    $customer_sql
);


if (!$customer_stmt) {

    die(
        "Customer Prepare Error: " .
        htmlspecialchars(mysqli_error($conn))
    );

}


mysqli_stmt_bind_param(
    $customer_stmt,
    "i",
    $customer_id
);


if (!mysqli_stmt_execute($customer_stmt)) {

    die(
        "Customer Execute Error: " .
        htmlspecialchars(
            mysqli_stmt_error($customer_stmt)
        )
    );

}


$customer_result =
    mysqli_stmt_get_result(
        $customer_stmt
    );


if (!$customer_result) {

    die(
        "Customer Result Error: " .
        htmlspecialchars(
            mysqli_stmt_error($customer_stmt)
        )
    );

}


// =====================================================
// CUSTOMER NOT FOUND
// =====================================================

if (mysqli_num_rows($customer_result) == 0) {

    mysqli_stmt_close($customer_stmt);

    echo "<script>
            alert('Selected Customer Does Not Exist');
            window.location.href='product_cart_item_view.php';
          </script>";

    exit();

}


mysqli_stmt_close($customer_stmt);


// =====================================================
// INSERT INTO SHOPPING CART
// =====================================================

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


$insert_stmt = mysqli_prepare(
    $conn,
    $insert_sql
);


// =====================================================
// PREPARE ERROR
// =====================================================

if (!$insert_stmt) {

    die("
        <div style='
            margin:30px;
            padding:25px;
            background:#dc3545;
            color:white;
            border-radius:10px;
            font-family:Arial;
        '>

            <h3>Cart Insert Error</h3>

            <p>
                "
                . htmlspecialchars(mysqli_error($conn))
                . "
            </p>

        </div>
    ");

}


// =====================================================
// BIND CUSTOMER ID
// =====================================================

mysqli_stmt_bind_param(
    $insert_stmt,
    "i",
    $customer_id
);


// =====================================================
// EXECUTE INSERT
// =====================================================

if (mysqli_stmt_execute($insert_stmt)) {

    // -------------------------------------------------
    // GET NEW CART ID
    // -------------------------------------------------

    $cart_id = mysqli_insert_id($conn);


    mysqli_stmt_close($insert_stmt);


    // -------------------------------------------------
    // SUCCESS
    // -------------------------------------------------

    echo "<script>

            alert(
                'Customer Added To Shopping Cart Successfully!\\n\\n' +
                'Customer ID: $customer_id\\n' +
                'Cart ID: $cart_id'
            );

            window.location.href='product_cart_item_view.php';

          </script>";

    exit();

}


// =====================================================
// INSERT ERROR
// =====================================================

$error = mysqli_stmt_error(
    $insert_stmt
);


mysqli_stmt_close($insert_stmt);


?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Cart Insert Error</title>

    <style>

        body {

            margin: 0;

            padding: 30px;

            background: #191c24;

            color: #ffffff;

            font-family: Arial, sans-serif;

        }


        .error-box {

            max-width: 800px;

            margin: 50px auto;

            padding: 30px;

            background: #dc3545;

            border-radius: 10px;

        }


        .error-box h2 {

            margin-top: 0;

        }


        .error-message {

            background: #ffffff;

            color: #111111;

            padding: 15px;

            border-radius: 6px;

            margin-top: 15px;

            word-break: break-word;

        }


        .back-btn {

            display: inline-block;

            margin-top: 20px;

            padding: 12px 20px;

            background: #ffffff;

            color: #dc3545;

            text-decoration: none;

            border-radius: 5px;

            font-weight: bold;

        }

    </style>

</head>

<body>


<div class="error-box">

    <h2>
        ❌ Shopping Cart Insert Error
    </h2>


    <p>
        Record insert nahi ho saka.
    </p>


    <p>
        <strong>Customer ID:</strong>
        <?php echo htmlspecialchars($customer_id); ?>
    </p>


    <div class="error-message">

        <strong>MySQL Error:</strong>

        <br><br>

        <?php
        echo htmlspecialchars($error);
        ?>

    </div>


    <a
        href="product_cart_item_view.php"
        class="back-btn"
    >
        ← Back To Customer Page
    </a>

</div>


</body>

</html>
