<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];

$sql = "SELECT groups.id,
               groups.name,
               groups.description,
               groups.created_by
        FROM groups
        INNER JOIN group_members
        ON groups.id = group_members.group_id
        WHERE group_members.user_id = '$user_id'
        ORDER BY groups.id DESC";

$result = $conn->query($sql);

if (!$result) {
    die("Database Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>My Groups</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">diversity_3</span>
        <h1>My Groups</h1>
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

    <a href="../tasks/view.php">
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
    <h2>My Groups</h2>
    <div class="page-actions">

        <a href="create.php" class="btn">
            <span class="material-symbols-outlined">add</span>
            Create New Group
        </a>

        <a href="join.php" class="btn btn-outline">
            <span class="material-symbols-outlined">group_add</span>
            Join Group
        </a>

    </div>
</div>


<?php if ($result->num_rows > 0) { ?>

    <div class="list">

    <?php while ($group = $result->fetch_assoc()) { ?>

        <div class="list-item">

            <h3>
                <?php echo htmlspecialchars($group['name']); ?>
            </h3>

            <p>
                <?php echo htmlspecialchars($group['description']); ?>
            </p>

            <div class="list-item-actions">

                <a href="group_tasks.php?group_id=<?php echo $group['id']; ?>">
                    <span class="material-symbols-outlined">visibility</span>
                    View Group Tasks
                </a>

                <a href="members.php?group_id=<?php echo $group['id']; ?>">
                    <span class="material-symbols-outlined">group</span>
                    View Members
                </a>

            </div>

        </div>

    <?php } ?>

    </div>

<?php } else { ?>

    <div class="empty-state">
        <span class="material-symbols-outlined">group_off</span>
        You are not a member of any group.
    </div>

<?php } ?>

</div>

</body>

</html>
