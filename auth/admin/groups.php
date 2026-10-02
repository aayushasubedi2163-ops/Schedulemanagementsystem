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

/* Get all groups */

$sql = "SELECT id, name, description, created_by FROM groups";

$result = $conn->query($sql);

if (!$result) {
    die("Database Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Groups</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">diversity_3</span>
        <h1>Manage Groups</h1>
    </div>
</header>

<div class="container">

<a href="index.php" class="back-link">
    <span class="material-symbols-outlined">arrow_back</span>
    Back to Admin Dashboard
</a>

<div class="table-wrap">

<table>

    <tr>
        <th>ID</th>
        <th>Group Name</th>
        <th>Description</th>
        <th>Created By</th>
        <th>Action</th>
    </tr>

    <?php while ($group = $result->fetch_assoc()) { ?>

        <tr>

            <td>
                <?php echo $group['id']; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($group['name']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($group['description']); ?>
            </td>

            <td>
                <?php echo $group['created_by']; ?>
            </td>

            <td>

                <a href="delete_group.php?id=<?php echo $group['id']; ?>"
                   class="action-delete"
                   onclick="return confirm('Are you sure you want to delete this group?');">
                    <span class="material-symbols-outlined" style="font-size:16px;vertical-align:-3px;">delete</span>
                    Delete

                </a>

            </td>

        </tr>

    <?php } ?>

</table>

</div>

</div>

</body>

</html>