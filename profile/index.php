<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];

$message = "";


/* =========================
   UPDATE PROFILE
========================= */

if (isset($_POST['update_profile'])) {

    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);

    $sql = "UPDATE users
            SET name = '$name',
                email = '$email'
            WHERE id = '$user_id'";

    if ($conn->query($sql) === TRUE) {

        $_SESSION['name'] = $name;

        $message = "Profile updated successfully.";

    } else {

        $message = "Error: " . $conn->error;

    }
}


/* =========================
   GET USER INFORMATION
========================= */

$sql = "SELECT id, name, email, role
        FROM users
        WHERE id = '$user_id'";

$result = $conn->query($sql);

if (!$result) {
    die("Database Error: " . $conn->error);
}

$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>

<head>

    <title>My Profile</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">person</span>
        <h1>My Profile</h1>
    </div>
</header>

<div class="container">

<a href="../dashboard/index.php" class="back-link">
    <span class="material-symbols-outlined">arrow_back</span>
    Back to Dashboard
</a>


<?php if ($message != "") { ?>

    <?php if (strpos($message, 'Error') !== false) { ?>

        <div class="alert alert-error">
            <span class="material-symbols-outlined">error</span>
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } else { ?>

        <div class="alert alert-success">
            <span class="material-symbols-outlined">check_circle</span>
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } ?>

<?php } ?>


<div class="section">

<h2>Profile Information</h2>

<form method="POST">

    <div class="form-group">
        <label>Name:</label>
        <input
            type="text"
            name="name"
            value="<?php echo htmlspecialchars($user['name']); ?>"
            required
        >
    </div>

    <div class="form-group">
        <label>Email:</label>
        <input
            type="email"
            name="email"
            value="<?php echo htmlspecialchars($user['email']); ?>"
            required
        >
    </div>

    <div class="form-group">
        <label>Role:</label>
        <input
            type="text"
            value="<?php echo htmlspecialchars($user['role']); ?>"
            readonly
        >
    </div>

    <button type="submit" name="update_profile">
        <span class="material-symbols-outlined">save</span>
        Update Profile
    </button>

</form>

</div>

<a href="../auth/logout.php" class="btn btn-outline btn-sm">
    <span class="material-symbols-outlined">logout</span>
    Logout
</a>

</div>
</body>

</html>
