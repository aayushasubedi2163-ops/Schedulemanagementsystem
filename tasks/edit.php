<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];
$id = $_GET['id'];

$sql = "SELECT * FROM tasks
        WHERE id = '$id'
        AND user_id = '$user_id'";

$result = $conn->query($sql);

if ($result->num_rows != 1) {
    die("Task not found.");
}

$task = $result->fetch_assoc();
if (isset($_POST['update_task'])) {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];

    $sql = "UPDATE tasks SET
            title = '$title',
            description = '$description',
            due_date = '$due_date',
            priority = '$priority',
            status = '$status'
            WHERE id = '$id'
            AND user_id = '$user_id'";

    if ($conn->query($sql) === TRUE) {

        header("Location: view.php");
        exit();

    } else {

        echo "Error: " . $conn->error;

    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Task</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">edit_note</span>
        <h1>Edit Task</h1>
    </div>
</header>

<div class="container">

<a href="view.php" class="back-link">
    <span class="material-symbols-outlined">arrow_back</span>
    Back to My Tasks
</a>

<div class="section">

    <form method="POST">

        <div class="form-group">
            <label>Task Title:</label>

            <input type="text"
                   name="title"
                   value="<?php echo $task['title']; ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Description:</label>

            <textarea name="description"><?php echo $task['description']; ?></textarea>
        </div>

        <div class="form-group">
            <label>Due Date:</label>

            <input type="date"
                   name="due_date"
                   value="<?php echo $task['due_date']; ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Priority:</label>

            <select name="priority">

                <option value="Low"
                    <?php if ($task['priority'] == 'Low') echo 'selected'; ?>>
                    Low
                </option>

                <option value="Medium"
                    <?php if ($task['priority'] == 'Medium') echo 'selected'; ?>>
                    Medium
                </option>

                <option value="High"
                    <?php if ($task['priority'] == 'High') echo 'selected'; ?>>
                    High
                </option>

            </select>
        </div>

        <div class="form-group">
            <label>Status:</label>

            <select name="status">

                <option value="Pending"
                    <?php if ($task['status'] == 'Pending') echo 'selected'; ?>>
                    Pending
                </option>

                <option value="In Progress"
                    <?php if ($task['status'] == 'In Progress') echo 'selected'; ?>>
                    In Progress
                </option>

                <option value="Completed"
                    <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>
                    Completed
                </option>

            </select>
        </div>

        <button type="submit" name="update_task">
            <span class="material-symbols-outlined">save</span>
            Update Task
        </button>

    </form>

</div>

</div>

</body>

</html>
