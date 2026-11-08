<?php
session_start();

// session destroy karo
session_destroy();

// login page par redirect
header("Location: index.php");
exit();
?>