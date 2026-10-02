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


/* Check membership */

$check_sql = "SELECT *
              FROM group_members
              WHERE group_id = '$group_id'
              AND user_id = '$user_id'";

$check_result = $conn->query($check_sql);

if (!$check_result) {
    die("Database Error: " . $conn->error);
}

if ($check_result->num_rows == 0) {
    die("You are not a member of this group.");
}


/* Get group members */

$members_sql = "SELECT users.id, users.name
                FROM users
                INNER JOIN group_members
                ON users.id = group_members.user_id
                WHERE group_members.group_id = '$group_id'";

$members = $conn->query($members_sql);

if (!$members) {
    die("Members Database Error: " . $conn->error);
}


/* Add Group Task */

if (isset($_POST['add_task'])) {

    $title = $conn->real_escape_string($_POST['title']);

    $description = $conn->real_escape_string(
        $_POST['description']
    );

    $due_date = $_POST['due_date'];

    $priority = $_POST['priority'];


    /* Assigned user */

    if (!empty($_POST['assigned_to'])) {

        $assigned_to = "'" .
            $conn->real_escape_string($_POST['assigned_to'])
            . "'";

    } else {

        $assigned_to = "NULL";

    }


    /* Insert task */

    $sql = "INSERT INTO group_tasks
            (group_id, assigned_to, title, description, due_date, priority)
            VALUES
            ('$group_id',
             $assigned_to,
             '$title',
             '$description',
             '$due_date',
             '$priority')";


    if ($conn->query($sql) === TRUE) {

        header(
            "Location: group_tasks.php?group_id=$group_id"
        );

        exit();

    } else {

        $message = "Task Database Error: " . $conn->error;
        $message_type = "error";

    }

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Group Task</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">add_task</span>
        <h1>Add Group Task</h1>
    </div>
</header>

<div class="container">

<a href="group_tasks.php?group_id=<?php echo $group_id; ?>" class="back-link">
    <span class="material-symbols-outlined">arrow_back</span>
    Back to Group Tasks
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
            <input
                type="text"
                name="title"
                required
            >
        </div>

        <div class="form-group">
            <label>Description:</label>
            <textarea
                name="description"
                rows="5"
                cols="40"
            ></textarea>
        </div>

        <div class="form-group">
            <label>Due Date:</label>
            <input
                type="date"
                name="due_date"
                required
            >
        </div>

        <div class="form-group">
            <label>Priority:</label>
            <select name="priority">

                <option value="Low">
                    Low
                </option>

                <option value="Medium" selected>
                    Medium
                </option>

                <option value="High">
                    High
                </option>

            </select>
        </div>

        <div class="form-group">
            <label>Assign To:</label>
            <select name="assigned_to">

                <option value="">
                    Unassigned
                </option>


                <?php while ($member = $members->fetch_assoc()) { ?>

                    <option value="<?php echo $member['id']; ?>">

                        <?php echo htmlspecialchars($member['name']); ?>

                    </option>

                <?php } ?>

            </select>
        </div>

        <!-- ADD TASK BUTTON -->

        <button
            type="submit"
            name="add_task"
        >
            <span class="material-symbols-outlined">add_task</span>
            Add Group Task
        </button>

    </form>

</div>

</div>

</body>

</html>
