<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];


/* =========================
   SCHEDULE COUNTS
========================= */

$sql = "SELECT COUNT(*) AS total
        FROM schedules
        WHERE user_id = '$user_id'";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

$total_schedules = $row['total'];


/* Today's schedules */

$sql = "SELECT COUNT(*) AS total
        FROM schedules
        WHERE user_id = '$user_id'
        AND schedule_date = CURDATE()";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

$today_schedules = $row['total'];


/* Upcoming schedules */

$sql = "SELECT COUNT(*) AS total
        FROM schedules
        WHERE user_id = '$user_id'
        AND schedule_date > CURDATE()";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

$upcoming_schedules = $row['total'];


/* =========================
   TODAY'S SCHEDULES
========================= */

$sql = "SELECT *
        FROM schedules
        WHERE user_id = '$user_id'
        AND schedule_date = CURDATE()
        ORDER BY start_time";

$today_result = $conn->query($sql);


/* =========================
   UPCOMING SCHEDULES
========================= */

$sql = "SELECT *
        FROM schedules
        WHERE user_id = '$user_id'
        AND schedule_date > CURDATE()
        ORDER BY schedule_date, start_time
        LIMIT 5";

$upcoming = $conn->query($sql);


/* =========================
   TASKS
========================= */

$sql = "SELECT COUNT(*) AS total
        FROM tasks
        WHERE user_id = '$user_id'";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

$total_tasks = $row['total'];


/* Pending tasks */

$sql = "SELECT COUNT(*) AS total
        FROM tasks
        WHERE user_id = '$user_id'
        AND status != 'Completed'";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

$pending_tasks = $row['total'];


/* Pending task details */

$sql = "SELECT *
        FROM tasks
        WHERE user_id = '$user_id'
        AND status != 'Completed'
        ORDER BY due_date ASC
        LIMIT 5";

$pending_result = $conn->query($sql);


/* =========================
   GROUPS
========================= */

$sql = "SELECT COUNT(*) AS total
        FROM group_members
        WHERE user_id = '$user_id'";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

$total_groups = $row['total'];


/* Group details */

$group_sql = "SELECT groups.*
              FROM groups
              INNER JOIN group_members
              ON groups.id = group_members.group_id
              WHERE group_members.user_id = '$user_id'
              ORDER BY groups.created_at DESC";

$group_result = $conn->query($group_sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<header class="app-bar">

    <div class="app-bar-title">
        <span class="material-symbols-outlined">event_available</span>
        <h1>Schedule Management System</h1>
    </div>

    <div class="app-bar-user">
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>
    </div>

</header>

<nav class="app-nav">

    <a href="index.php">
        <span class="material-symbols-outlined">dashboard</span>
        Dashboard
    </a>

    <a href="../schedules/add.php">
        <span class="material-symbols-outlined">add_circle</span>
        Add Schedule
    </a>

    <a href="../schedules/view.php">
        <span class="material-symbols-outlined">event</span>
        My Schedules
    </a>

    <a href="../tasks/add.php">
        <span class="material-symbols-outlined">add_task</span>
        Add Task
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


<!-- =========================
     SUMMARY
========================= -->

<div class="page-header">
    <h2>Dashboard Summary</h2>
</div>

<div class="cards">

    <div class="card">
        <h3>Total Schedules</h3>
        <p><?php echo $total_schedules; ?></p>
    </div>

    <div class="card">
        <h3>Today's Schedules</h3>
        <p><?php echo $today_schedules; ?></p>
    </div>

    <div class="card">
        <h3>Upcoming</h3>
        <p><?php echo $upcoming_schedules; ?></p>
    </div>

    <div class="card">
        <h3>Total Tasks</h3>
        <p><?php echo $total_tasks; ?></p>
    </div>

    <div class="card">
        <h3>Pending Tasks</h3>
        <p><?php echo $pending_tasks; ?></p>
    </div>

    <div class="card">
        <h3>My Groups</h3>
        <p><?php echo $total_groups; ?></p>
    </div>

</div>


<!-- =========================
     TODAY'S SCHEDULES
========================= -->

<div class="section">

<h2><span class="material-symbols-outlined" style="vertical-align:-6px;">today</span> Today's Schedules</h2>

<?php if ($today_result && $today_result->num_rows > 0) { ?>

    <div class="list">

    <?php while ($schedule = $today_result->fetch_assoc()) { ?>

        <div class="list-item">

            <h3>
                <?php echo htmlspecialchars($schedule['title']); ?>
            </h3>

            <div class="list-item-meta">

                <span>
                    <span class="material-symbols-outlined">schedule</span>
                    <?php echo $schedule['start_time']; ?> - <?php echo $schedule['end_time']; ?>
                </span>

                <span>
                    <span class="material-symbols-outlined">location_on</span>
                    <?php echo htmlspecialchars($schedule['location']); ?>
                </span>

            </div>

        </div>

    <?php } ?>

    </div>

<?php } else { ?>

    <div class="empty-state">
        <span class="material-symbols-outlined">event_busy</span>
        No schedules for today.
    </div>

<?php } ?>

</div>


<!-- =========================
     UPCOMING SCHEDULES
========================= -->

<div class="section">

<h2><span class="material-symbols-outlined" style="vertical-align:-6px;">upcoming</span> Upcoming Schedules</h2>

<?php if ($upcoming && $upcoming->num_rows > 0) { ?>

    <div class="list">

    <?php while ($schedule = $upcoming->fetch_assoc()) { ?>

        <div class="list-item">

            <h3>
                <?php echo htmlspecialchars($schedule['title']); ?>
            </h3>

            <div class="list-item-meta">

                <span>
                    <span class="material-symbols-outlined">event</span>
                    <?php echo $schedule['schedule_date']; ?>
                </span>

                <span>
                    <span class="material-symbols-outlined">schedule</span>
                    <?php echo $schedule['start_time']; ?> - <?php echo $schedule['end_time']; ?>
                </span>

                <span>
                    <span class="material-symbols-outlined">location_on</span>
                    <?php echo htmlspecialchars($schedule['location']); ?>
                </span>

            </div>

        </div>

    <?php } ?>

    </div>

<?php } else { ?>

    <div class="empty-state">
        <span class="material-symbols-outlined">event_busy</span>
        No upcoming schedules.
    </div>

<?php } ?>

</div>


<!-- =========================
     PENDING TASKS
========================= -->

<div class="section">

<h2><span class="material-symbols-outlined" style="vertical-align:-6px;">checklist</span> Pending Tasks</h2>

<?php if ($pending_result && $pending_result->num_rows > 0) { ?>

    <div class="list">

    <?php while ($task = $pending_result->fetch_assoc()) { ?>

        <?php $priority_class = strtolower($task['priority']); ?>

        <div class="list-item priority-<?php echo $priority_class; ?>">

            <h3>
                <?php echo htmlspecialchars($task['title']); ?>
            </h3>

            <div class="list-item-meta">

                <span>
                    <span class="material-symbols-outlined">event</span>
                    Due <?php echo $task['due_date']; ?>
                </span>

                <span class="chip chip-<?php echo $priority_class; ?>">
                    <?php echo htmlspecialchars($task['priority']); ?>
                </span>

            </div>

        </div>

    <?php } ?>

    </div>

<?php } else { ?>

    <div class="empty-state">
        <span class="material-symbols-outlined">task_alt</span>
        No pending tasks.
    </div>

<?php } ?>

</div>


<!-- =========================
     GROUPS
========================= -->

<div class="section">

<div class="page-header">

    <h2><span class="material-symbols-outlined" style="vertical-align:-6px;">group</span> My Groups</h2>

    <div class="page-actions">

        <a href="../groups/create.php" class="btn btn-sm">
            <span class="material-symbols-outlined">add</span>
            Create Group
        </a>

        <a href="../groups/join.php" class="btn btn-outline btn-sm">
            <span class="material-symbols-outlined">group_add</span>
            Join Group
        </a>

    </div>

</div>


<?php if ($group_result && $group_result->num_rows > 0) { ?>

    <div class="list">

    <?php while ($group = $group_result->fetch_assoc()) { ?>

        <div class="list-item">

            <h3>
                <?php echo htmlspecialchars($group['name']); ?>
            </h3>

            <p>
                <?php echo htmlspecialchars($group['description']); ?>
            </p>

            <div class="list-item-actions">

                <a href="../groups/group_tasks.php?group_id=<?php echo $group['id']; ?>">
                    <span class="material-symbols-outlined">visibility</span>
                    View Group Tasks
                </a>

            </div>

        </div>

    <?php } ?>

    </div>

<?php } else { ?>

    <div class="empty-state">
        <span class="material-symbols-outlined">group_off</span>
        You are not a member of any group yet.
    </div>

<?php } ?>

</div>

</div>
</body>

</html>