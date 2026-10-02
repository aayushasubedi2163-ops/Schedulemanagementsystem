<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];


/* Check group ID */

if (!isset($_GET['group_id'])) {
    die("Group ID is missing.");
}

$group_id = $_GET['group_id'];


/* Check whether user is a member */

$check = "SELECT *
          FROM group_members
          WHERE group_id = '$group_id'
          AND user_id = '$user_id'";

$result = $conn->query($check);

if (!$result) {
    die("Membership Database Error: " . $conn->error);
}

if ($result->num_rows == 0) {
    die("You are not a member of this group.");
}


/* Get group information */

$group_sql = "SELECT *
              FROM groups
              WHERE id = '$group_id'";

$group_result = $conn->query($group_sql);

if (!$group_result) {
    die("Group Database Error: " . $conn->error);
}

if ($group_result->num_rows == 0) {
    die("Group not found.");
}

$group = $group_result->fetch_assoc();


/* Get group tasks */

$tasks_sql = "SELECT group_tasks.*, users.name AS assigned_name
              FROM group_tasks
              LEFT JOIN users
              ON group_tasks.assigned_to = users.id
              WHERE group_tasks.group_id = '$group_id'
              ORDER BY group_tasks.due_date ASC";

$tasks = $conn->query($tasks_sql);


/* Check whether task query worked */

if (!$tasks) {
    die("Task Database Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Group Tasks</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">checklist</span>
        <h1><?php echo htmlspecialchars($group['name']); ?></h1>
    </div>
</header>

<div class="container">

<div class="page-actions">

    <a href="../groups/view.php" class="back-link">
        <span class="material-symbols-outlined">arrow_back</span>
        Back to Groups
    </a>

    <a href="../dashboard/index.php" class="btn btn-outline btn-sm">
        <span class="material-symbols-outlined">dashboard</span>
        Back to Dashboard
    </a>

</div>

<div class="section">

    <p>
        <?php echo htmlspecialchars($group['description']); ?>
    </p>

</div>

<div class="page-header">

    <h2>Group Tasks</h2>

    <div class="page-actions">

        <a href="add_task.php?group_id=<?php echo $group_id; ?>" class="btn">
            <span class="material-symbols-outlined">add</span>
            Add Group Task
        </a>

    </div>

</div>

<?php if ($tasks->num_rows > 0) { ?>

    <div class="list">

    <?php while ($task = $tasks->fetch_assoc()) { ?>

        <div class="list-item">

            <h3>
                <?php echo htmlspecialchars($task['title']); ?>
            </h3>

            <p>
                <?php echo htmlspecialchars($task['description']); ?>
            </p>

            <div class="list-item-meta">

                <span>
                    <span class="material-symbols-outlined">event</span>
                    Due <?php echo htmlspecialchars($task['due_date']); ?>
                </span>

                <span class="chip chip-<?php echo strtolower($task['priority']); ?>">
                    <?php echo htmlspecialchars($task['priority']); ?>
                </span>

                <span class="chip chip-<?php echo strtolower(str_replace(' ', '', $task['status'])); ?>">
                    <?php echo htmlspecialchars($task['status']); ?>
                </span>

                <span>
                    <span class="material-symbols-outlined">person</span>
                    <?php

                    if (!empty($task['assigned_name'])) {

                        echo htmlspecialchars($task['assigned_name']);

                    } else {

                        echo "Unassigned";

                    }

                    ?>
                </span>

            </div>

        </div>

    <?php } ?>

    </div>

<?php } else { ?>

    <div class="empty-state">
        <span class="material-symbols-outlined">checklist</span>
        No group tasks yet.
    </div>

<?php } ?>

</div>

</body>

</html>
