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

$sql = "SELECT id, user_id, title, description,
               schedule_date, start_time, end_time,
               location, created_at
        FROM schedules
        ORDER BY schedule_date ASC, start_time ASC";

$result = $conn->query($sql);

if (!$result) {
    die("Database Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Schedules</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">event</span>
        <h1>Manage Schedules</h1>
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
        <th>User ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Date</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Location</th>
        <th>Created At</th>
        <th>Action</th>
    </tr>

    <?php while ($schedule = $result->fetch_assoc()) { ?>

        <tr>

            <td>
                <?php echo $schedule['id']; ?>
            </td>

            <td>
                <?php echo $schedule['user_id']; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($schedule['title']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($schedule['description']); ?>
            </td>

            <td>
                <?php echo $schedule['schedule_date']; ?>
            </td>

            <td>
                <?php echo $schedule['start_time']; ?>
            </td>

            <td>
                <?php echo $schedule['end_time']; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($schedule['location']); ?>
            </td>

            <td>
                <?php echo $schedule['created_at']; ?>
            </td>

            <td>

                <a href="delete_schedule.php?id=<?php echo $schedule['id']; ?>"
                   class="action-delete"
                   onclick="return confirm('Are you sure you want to delete this schedule?');">
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