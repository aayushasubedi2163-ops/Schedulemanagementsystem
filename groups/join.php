<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];

if (isset($_POST['join_group'])) {

    $group_id = $_POST['group_id'];

    $check = "SELECT * FROM group_members
              WHERE group_id = '$group_id'
              AND user_id = '$user_id'";

    $result = $conn->query($check);

    if ($result->num_rows > 0) {

        $message = "You are already a member of this group.";
        $message_type = "error";

    } else {

        $sql = "INSERT INTO group_members
                (group_id, user_id)
                VALUES
                ('$group_id', '$user_id')";

        if ($conn->query($sql) === TRUE) {

            header("Location: view.php");
            exit();

        } else {

            $message = "Error: " . $conn->error;
            $message_type = "error";

        }
    }
}

$groups = $conn->query("SELECT * FROM groups");

?>
<!DOCTYPE html>
<html>

<head>

    <title>Join Group</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">group_add</span>
        <h1>Join Group</h1>
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
            <label>Select Group:</label>

            <select name="group_id" required>

                <option value="">Select a group</option>

                <?php while ($group = $groups->fetch_assoc()) { ?>

                    <option value="<?php echo $group['id']; ?>">
                        <?php echo $group['name']; ?>
                    </option>

                <?php } ?>

            </select>
        </div>

        <button type="submit" name="join_group">
            <span class="material-symbols-outlined">group_add</span>
            Join Group
        </button>

    </form>

</div>

</div>

</body>

</html>
