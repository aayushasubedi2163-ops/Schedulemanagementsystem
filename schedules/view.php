<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM schedules
        WHERE user_id = '$user_id'
        ORDER BY schedule_date, start_time";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>My Schedules</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">event</span>
        <h1>My Schedules</h1>
    </div>
</header>

<nav class="app-nav">

    <a href="../dashboard/index.php">
        <span class="material-symbols-outlined">dashboard</span>
        Dashboard
    </a>

    <a href="view.php">
        <span class="material-symbols-outlined">event</span>
        My Schedules
    </a>

    <a href="../tasks/view.php">
        <span class="material-symbols-outlined">checklist</span>
        My Tasks
    </a>

    <a href="../notifications/index.php">
        <span class="material-symbols-outlined">notifications</span>
        Notifications
    </a>

    <a href="../profile/index.php">
        <span class="material-symbols-outlined">person</span>
        My Profile
    </a>

    <a href="../auth/logout.php" class="nav-logout">
        <span class="material-symbols-outlined">logout</span>
        Logout
    </a>

</nav>

<div class="container">

<div class="page-header">
    <h2>My Schedules</h2>
    <div class="page-actions">
        <a href="add.php" class="btn">
            <span class="material-symbols-outlined">add_circle</span>
            Add New Schedule
        </a>
    </div>
</div>

<?php if ($result->num_rows > 0) { ?>

    <div class="list">

    <?php while ($row = $result->fetch_assoc()) { ?>

        <div class="list-item">

            <h3>
                <?php echo $row['title']; ?>
            </h3>

            <p>
                <?php echo $row['description']; ?>
            </p>

            <div class="list-item-meta">

                <span>
                    <span class="material-symbols-outlined">event</span>
                    <?php echo $row['schedule_date']; ?>
                </span>

                <span>
                    <span class="material-symbols-outlined">schedule</span>
                    <?php echo $row['start_time']; ?> - <?php echo $row['end_time']; ?>
                </span>

                <span>
                    <span class="material-symbols-outlined">location_on</span>
                    <?php echo $row['location']; ?>
                </span>

            </div>

            <div class="list-item-actions">

                <a href="edit.php?id=<?php echo $row['id']; ?>">
                    <span class="material-symbols-outlined">edit</span>
                    Edit
                </a>

                <a href="delete.php?id=<?php echo $row['id']; ?>" class="action-delete">
                    <span class="material-symbols-outlined">delete</span>
                    Delete
                </a>

            </div>

        </div>

    <?php } ?>

    </div>

<?php } else { ?>

    <div class="empty-state">
        <span class="material-symbols-outlined">event_busy</span>
        No schedules found.
    </div>

<?php } ?>

</div>

</body>
</html>
