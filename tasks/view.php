<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM tasks
        WHERE user_id = '$user_id'
        ORDER BY due_date";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>My Tasks</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">checklist</span>
        <h1>My Tasks</h1>
    </div>
</header>

<nav class="app-nav">

    <a href="../dashboard/index.php">
        <span class="material-symbols-outlined">dashboard</span>
        Dashboard
    </a>

    <a href="../schedules/view.php">
        <span class="material-symbols-outlined">event</span>
        My Schedules
    </a>

    <a href="view.php">
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
    <h2>My Tasks</h2>
    <div class="page-actions">
        <a href="add.php" class="btn">
            <span class="material-symbols-outlined">add_task</span>
            Add New Task
        </a>
    </div>
</div>

<?php if ($result->num_rows > 0) { ?>

    <div class="list">

    <?php while ($task = $result->fetch_assoc()) { ?>

        <?php $priority_class = strtolower($task['priority']); ?>
        <?php $status_class = strtolower(str_replace(' ', '', $task['status'])); ?>

        <div class="list-item priority-<?php echo $priority_class; ?>">

            <h3>
                <?php echo $task['title']; ?>
            </h3>

            <p>
                <?php echo $task['description']; ?>
            </p>

            <div class="list-item-meta">

                <span>
                    <span class="material-symbols-outlined">event</span>
                    Due <?php echo $task['due_date']; ?>
                </span>

                <span class="chip chip-<?php echo $priority_class; ?>">
                    <?php echo $task['priority']; ?>
                </span>

                <span class="chip chip-<?php echo $status_class; ?>">
                    <?php echo $task['status']; ?>
                </span>

            </div>

            <div class="list-item-actions">

                <a href="edit.php?id=<?php echo $task['id']; ?>">
                    <span class="material-symbols-outlined">edit</span>
                    Edit
                </a>

                <a href="delete.php?id=<?php echo $task['id']; ?>" class="action-delete">
                    <span class="material-symbols-outlined">delete</span>
                    Delete
                </a>

            </div>

        </div>

    <?php } ?>

    </div>

<?php } else { ?>

    <div class="empty-state">
        <span class="material-symbols-outlined">task_alt</span>
        No tasks found.
    </div>

<?php } ?>

</div>

</body>

</html>
