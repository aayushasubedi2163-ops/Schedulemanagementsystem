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

    $group_id = $_GET['id'];

    /* Delete group members first */

    $member_sql = "DELETE FROM group_members
                   WHERE group_id = '$group_id'";

    $conn->query($member_sql);

    /* Delete the group */

    $group_sql = "DELETE FROM groups
                  WHERE id = '$group_id'";

    if ($conn->query($group_sql) === TRUE) {

        header("Location: groups.php");
        exit();

    } else {

        echo "Error: " . $conn->error;

    }
}

?>