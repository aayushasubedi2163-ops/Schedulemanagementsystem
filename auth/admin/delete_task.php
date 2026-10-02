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

    $task_id = $_GET['id'];

    $sql = "DELETE FROM tasks
            WHERE id = '$task_id'";

    if ($conn->query($sql) === TRUE) {

        header("Location: tasks.php");
        exit();

    } else {

        echo "Error: " . $conn->error;

    }
}

?>