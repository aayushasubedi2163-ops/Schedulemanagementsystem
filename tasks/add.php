<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

//gets user id
if (isset($_POST['add_task'])) {

    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];

    $sql = "INSERT INTO tasks
        (user_id, title, description, due_date, priority)
        VALUES
        ('$user_id', '$title', '$description', '$due_date', '$priority')";

if ($conn->query($sql) === TRUE) {

    $message = "Task added successfully!";
    $message_type = "success";

} else {

    $message = "Error: " . $conn->error;
    $message_type = "error";

}

}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Task</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">add_task</span>
        <h1>Add Task</h1>
    </div>
</header>

<div class="container">

<a href="view.php" class="back-link">
    <span class="material-symbols-outlined">arrow_back</span>
    Back to My Tasks
</a>

<div class="section">

    <?php if (isset($message)) { ?>

        <div class="alert alert-<?php echo $message_type; ?>">
            <span class="material-symbols-outlined"><?php echo $message_type == 'success' ? 'check_circle' : 'error'; ?></span>
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } ?>

    <form method="POST">

        <div class="form-group">
            <label>Task Title:</label>
            <input type="text" name="title" required>
        </div>

        <div class="form-group">
            <label>Description:</label>
            <textarea name="description"></textarea>
        </div>

        <div class="form-group">
            <label>Due Date:</label>
            <input type="date" name="due_date" required>
        </div>

        <div class="form-group">
            <label>Priority:</label>

            <select name="priority">

                <option value="Low">Low</option>
                <option value="Medium" selected>Medium</option>
                <option value="High">High</option>

            </select>
        </div>

        <button type="submit" name="add_task">
            <span class="material-symbols-outlined">add_task</span>
            Add Task
        </button>

    </form>

</div>

</div>

</body>

</html>
