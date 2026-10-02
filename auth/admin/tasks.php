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

$sql = "SELECT id, user_id, title, description, due_date, priority
        FROM tasks
        ORDER BY due_date ASC";

$result = $conn->query($sql);

if (!$result) {
    die("Database Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Tasks</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">checklist</span>
        <h1>Manage Tasks</h1>
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
        <th>Due Date</th>
        <th>Priority</th>
        <th>Action</th>
    </tr>

    <?php while ($task = $result->fetch_assoc()) { ?>

        <tr>

            <td>
                <?php echo $task['id']; ?>
            </td>

            <td>
                <?php echo $task['user_id']; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($task['title']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($task['description']); ?>
            </td>

            <td>
                <?php echo $task['due_date']; ?>
            </td>

            <td>
                <span class="chip chip-<?php echo strtolower($task['priority']); ?>"><?php echo htmlspecialchars($task['priority']); ?></span>
            </td>

            <td>

                <a href="delete_task.php?id=<?php echo $task['id']; ?>"
                   class="action-delete"
                   onclick="return confirm('Are you sure you want to delete this task?');">
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