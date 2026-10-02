<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];

if (!isset($_GET['group_id'])) {
    die("Group not found.");
}

$group_id = $_GET['group_id'];


/* Check membership */

$check_sql = "SELECT *
              FROM group_members
              WHERE group_id = '$group_id'
              AND user_id = '$user_id'";

$check_result = $conn->query($check_sql);

if (!$check_result || $check_result->num_rows == 0) {
    die("You are not a member of this group.");
}


/* Get group information */

$group_sql = "SELECT name, description
              FROM groups
              WHERE id = '$group_id'";

$group_result = $conn->query($group_sql);

$group = $group_result->fetch_assoc();


/* Get members */

$members_sql = "SELECT users.id,
                       users.name,
                       users.email
                FROM users
                INNER JOIN group_members
                ON users.id = group_members.user_id
                WHERE group_members.group_id = '$group_id'";

$members = $conn->query($members_sql);

if (!$members) {
    die("Database Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Group Members</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">group</span>
        <h1>Group Members</h1>
    </div>
</header>

<div class="container">

<div class="page-actions">

    <a href="view.php" class="back-link">
        <span class="material-symbols-outlined">arrow_back</span>
        Back to My Groups
    </a>

    <a href="group_tasks.php?group_id=<?php echo $group_id; ?>" class="btn btn-outline btn-sm">
        <span class="material-symbols-outlined">checklist</span>
        View Group Tasks
    </a>

</div>

<div class="section">

    <h2>
        <?php echo htmlspecialchars($group['name']); ?>
    </h2>

    <p>
        <?php echo htmlspecialchars($group['description']); ?>
    </p>

</div>

<div class="section">

    <h2>Group Members</h2>

    <?php if ($members->num_rows > 0) { ?>

        <div class="table-wrap">

        <table>

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>

            <?php while ($member = $members->fetch_assoc()) { ?>

                <tr>

                    <td>
                        <?php echo $member['id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($member['name']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($member['email']); ?>
                    </td>

                </tr>

            <?php } ?>

        </table>

        </div>

    <?php } else { ?>

        <div class="empty-state">
            <span class="material-symbols-outlined">group_off</span>
            No members found.
        </div>

    <?php } ?>

</div>

</div>

</body>

</html>
