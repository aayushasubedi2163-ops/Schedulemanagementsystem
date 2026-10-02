<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html>

<head>

    <title>Notifications</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">notifications</span>
        <h1>Notifications</h1>
    </div>
</header>

<div class="container">

<a href="../dashboard/index.php" class="back-link">
    <span class="material-symbols-outlined">arrow_back</span>
    Back to Dashboard
</a>


<!-- =========================
     TODAY'S SCHEDULES
========================= -->

<div class="section">

<h2><span class="material-symbols-outlined" style="vertical-align:-6px;">today</span> Today's Schedules</h2>

<?php

$sql = "SELECT *
        FROM schedules
        WHERE user_id = '$user_id'
        AND schedule_date = CURDATE()
        ORDER BY start_time";

$result = $conn->query($sql);

if (!$result) {
    die("Database Error: " . $conn->error);
}

if ($result->num_rows > 0) {

    echo '<div class="list">';

    while ($schedule = $result->fetch_assoc()) {

        echo '<div class="list-item">'
            . '<h3>' . htmlspecialchars($schedule['title']) . '</h3>'
            . '<div class="list-item-meta">'
            . '<span><span class="material-symbols-outlined">schedule</span>Today at ' . $schedule['start_time'] . '</span>'
            . '<span><span class="material-symbols-outlined">location_on</span>' . htmlspecialchars($schedule['location']) . '</span>'
            . '</div>'
            . '</div>';

    }

    echo '</div>';

} else {

    echo '<div class="empty-state"><span class="material-symbols-outlined">event_busy</span>No schedules for today.</div>';

}

?>

</div>


<!-- =========================
     UPCOMING SCHEDULES
========================= -->

<div class="section">

<h2><span class="material-symbols-outlined" style="vertical-align:-6px;">upcoming</span> Upcoming Schedules</h2>

<?php

$sql = "SELECT *
        FROM schedules
        WHERE user_id = '$user_id'
        AND schedule_date > CURDATE()
        AND schedule_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
        ORDER BY schedule_date, start_time";

$result = $conn->query($sql);

if (!$result) {
    die("Database Error: " . $conn->error);
}

if ($result->num_rows > 0) {

    echo '<div class="list">';

    while ($schedule = $result->fetch_assoc()) {

        echo '<div class="list-item">'
            . '<h3>' . htmlspecialchars($schedule['title']) . '</h3>'
            . '<div class="list-item-meta">'
            . '<span><span class="material-symbols-outlined">event</span>Date: ' . $schedule['schedule_date'] . '</span>'
            . '<span><span class="material-symbols-outlined">schedule</span>Time: ' . $schedule['start_time'] . '</span>'
            . '</div>'
            . '</div>';

    }

    echo '</div>';

} else {

    echo '<div class="empty-state"><span class="material-symbols-outlined">event_busy</span>No upcoming schedules in the next 7 days.</div>';

}

?>

</div>


<!-- =========================
     UPCOMING TASKS
========================= -->

<div class="section">

<h2><span class="material-symbols-outlined" style="vertical-align:-6px;">checklist</span> Upcoming Tasks</h2>

<?php

$sql = "SELECT *
        FROM tasks
        WHERE user_id = '$user_id'
        AND status != 'Completed'
        AND due_date >= CURDATE()
        AND due_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
        ORDER BY due_date";

$result = $conn->query($sql);

if (!$result) {
    die("Database Error: " . $conn->error);
}

if ($result->num_rows > 0) {

    echo '<div class="list">';

    while ($task = $result->fetch_assoc()) {

        $priority_class = strtolower($task['priority']);

        echo '<div class="list-item">'
            . '<h3>' . htmlspecialchars($task['title']) . '</h3>'
            . '<div class="list-item-meta">'
            . '<span><span class="material-symbols-outlined">event</span>Due Date: ' . $task['due_date'] . '</span>'
            . '<span class="chip chip-' . $priority_class . '">' . htmlspecialchars($task['priority']) . '</span>'
            . '</div>'
            . '</div>';

    }

    echo '</div>';

} else {

    echo '<div class="empty-state"><span class="material-symbols-outlined">task_alt</span>No upcoming tasks.</div>';

}

?>

</div>


<!-- =========================
     OVERDUE TASKS
========================= -->

<div class="section">

<h2><span class="material-symbols-outlined" style="vertical-align:-6px;">warning</span> Overdue Tasks</h2>

<?php

$sql = "SELECT *
        FROM tasks
        WHERE user_id = '$user_id'
        AND status != 'Completed'
        AND due_date < CURDATE()
        ORDER BY due_date";

$result = $conn->query($sql);

if (!$result) {
    die("Database Error: " . $conn->error);
}

if ($result->num_rows > 0) {

    echo '<div class="list">';

    while ($task = $result->fetch_assoc()) {

        $priority_class = strtolower($task['priority']);

        echo '<div class="list-item">'
            . '<h3>' . htmlspecialchars($task['title']) . '</h3>'
            . '<div class="list-item-meta">'
            . '<span><span class="material-symbols-outlined">event</span>Due Date: ' . $task['due_date'] . '</span>'
            . '<span class="chip chip-' . $priority_class . '">' . htmlspecialchars($task['priority']) . '</span>'
            . '</div>'
            . '</div>';

    }

    echo '</div>';

} else {

    echo '<div class="empty-state"><span class="material-symbols-outlined">task_alt</span>No overdue tasks.</div>';

}

?>

</div>

</div>

</body>

</html>
