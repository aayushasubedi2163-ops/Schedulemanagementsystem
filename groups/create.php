<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];

if (isset($_POST['create_group'])) {

    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);

    // Create the group
    $sql = "INSERT INTO groups
            (name, description, created_by)
            VALUES
            ('$name', '$description', '$user_id')";

    if ($conn->query($sql) === TRUE) {

        // Get the ID of the newly created group
        $group_id = $conn->insert_id;

        // Add creator as a group member
        $member_sql = "INSERT INTO group_members
                       (group_id, user_id)
                       VALUES
                       ('$group_id', '$user_id')";

        if ($conn->query($member_sql) === TRUE) {

            header("Location: view.php");
            exit();

        } else {

            $message = "Member Error: " . $conn->error;
            $message_type = "error";

        }

    } else {

        $message = "Group Error: " . $conn->error;
        $message_type = "error";

    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Create Group</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">add</span>
        <h1>Create Group</h1>
    </div>
</header>

<div class="container">

<a href="view.php" class="back-link">
    <span class="material-symbols-outlined">arrow_back</span>
    Back to Groups
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
            <label>Group Name:</label>
            <input type="text"
                   name="name"
                   required>
        </div>

        <div class="form-group">
            <label>Description:</label>
            <textarea name="description"></textarea>
        </div>

        <button type="submit" name="create_group">
            <span class="material-symbols-outlined">add</span>
            Create Group
        </button>

    </form>

</div>

</div>

</body>

</html>
