<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Access Denied");
}

include "../config/database.php";

if (isset($_GET['id'])) {

    $schedule_id = $_GET['id'];

    $sql = "DELETE FROM schedules
            WHERE id = '$schedule_id'";

    if ($conn->query($sql) === TRUE) {

        header("Location: schedules.php");
        exit();

    } else {

        echo "Error: " . $conn->error;

    }
}

?>