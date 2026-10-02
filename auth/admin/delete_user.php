<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    die("Access denied. Admins only.");
}

include "../config/database.php";

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "DELETE FROM users WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {

        header("Location: users.php");
        exit();

    } else {

        echo "Error: " . $conn->error;

    }
}

?>