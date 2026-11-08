
<?php

include("Database.php");


// =====================================================
// DATABASE CONNECTION
// =====================================================

$db = new Database();
$conn = $db->connection();


// =====================================================
// CHECK DATABASE CONNECTION
// =====================================================

if (!$conn) {
    die("Database connection failed.");
}


// =====================================================
// CHECK FORM SUBMISSION
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    // =================================================
    // GET FORM VALUES
    // =================================================

    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone_number = trim($_POST["phone_number"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $registration_date = trim($_POST["registration_date"] ?? "");


    // =================================================
    // VALIDATION
    // =================================================

    if (
        $first_name === "" ||
        $last_name === "" ||
        $email === "" ||
        $phone_number === "" ||
        $address === "" ||
        $registration_date === ""
    ) {

        echo "<script>
                alert('Please fill all fields.');
                window.location.href = 'customer_add.php';
              </script>";

        exit;
    }


    // =================================================
    // PHONE NUMBER VALIDATION
    // =================================================

    // Pakistani mobile number example:
    // 03460760286

    if (!preg_match('/^03[0-9]{9}$/', $phone_number)) {

        echo "<script>
                alert('Please enter a valid Pakistani phone number. Example: 03460760286');
                window.location.href = 'customer_add.php';
              </script>";

        exit;
    }


    // =================================================
    // INSERT QUERY
    // =================================================

    $sql = "INSERT INTO entity_asad_customer
            (
                first_name,
                last_name,
                email,
                phone_number,
                address,
                registration_date
            )
            VALUES
            (?, ?, ?, ?, ?, ?)";


    // =================================================
    // PREPARE
    // =================================================

    $stmt = $conn->prepare($sql);

    if (!$stmt) {

        die("Prepare failed: " . $conn->error);
    }


    // =================================================
    // BIND PARAMETERS
    // =================================================
    
    // IMPORTANT:
    // phone_number is STRING, therefore "s"

    $stmt->bind_param(
        "ssssss",
        $first_name,
        $last_name,
        $email,
        $phone_number,
        $address,
        $registration_date
    );


    // =================================================
    // EXECUTE INSERT
    // =================================================

    if ($stmt->execute()) {

        echo "<script>
                alert('Customer added successfully!');
                window.location.href = 'customer_product_view.php';
              </script>";

        exit;

    } else {

        echo "<script>
                alert('Customer insert failed: " .
                addslashes($stmt->error) .
                "');
                window.location.href = 'customer_add.php';
              </script>";

        exit;
    }


    // =================================================
    // CLOSE
    // =================================================

    $stmt->close();
}

?>
