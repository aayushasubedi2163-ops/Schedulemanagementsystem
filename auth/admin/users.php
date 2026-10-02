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

$sql = "SELECT id, name, email, role FROM users";

$result = $conn->query($sql);

if (!$result) {
    die("Database Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Users</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">group</span>
        <h1>Manage Users</h1>
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
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
    </tr>

    <?php while ($user = $result->fetch_assoc()) { ?>

        <tr>

            <td>
                <?php echo $user['id']; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($user['name']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($user['email']); ?>
            </td>

            <td>
                <span class="chip chip-<?php echo htmlspecialchars($user['role']); ?>">
                    <?php echo htmlspecialchars($user['role']); ?>
                </span>
            </td>

            <td>

                <?php if ($user['id'] != $_SESSION['user_id']) { ?>

                    <a href="delete_user.php?id=<?php echo $user['id']; ?>"
                       class="action-delete"
                       onclick="return confirm('Are you sure you want to delete this user?');">
                        <span class="material-symbols-outlined" style="font-size:16px;vertical-align:-3px;">delete</span>
                        Delete
                    </a>

                <?php } else { ?>

                    <span style="color:var(--md-on-surface-muted);font-size:13px;">Current Admin</span>

                <?php } ?>

            </td>

        </tr>

    <?php } ?>

</table>

</div>

</div>

</body>

</html>
