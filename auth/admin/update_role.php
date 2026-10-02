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

if (isset($_POST['id']) && isset($_POST['role'])) {

    $id = $_POST['id'];
    $role = $_POST['role'];

    if ($role != 'admin' && $role != 'user') {
        die("Invalid role.");
    }

    $sql = "UPDATE users
            SET role = '$role'
            WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {

        header("Location: users.php");
        exit();

    } else {

        echo "Error: " . $conn->error;

    }
}

?>