<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];
$id = $_GET['id'];

$sql = "DELETE FROM schedules
        WHERE id = '$id'
        AND user_id = '$user_id'";

if ($conn->query($sql) === TRUE) {

    header("Location: view.php");
    exit();

} else {

    echo "Error: " . $conn->error;

}

?>