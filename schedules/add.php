<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

if (isset($_POST['add_schedule'])) {

    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $schedule_date = $_POST['schedule_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $location = $_POST['location'];

    $sql = "INSERT INTO schedules
            (user_id, title, description, schedule_date, start_time, end_time, location)
            VALUES
            ('$user_id', '$title', '$description', '$schedule_date',
             '$start_time', '$end_time', '$location')";

    if ($conn->query($sql) === TRUE) {

        $message = "Schedule added successfully!";
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
    <title>Add Schedule</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">event</span>
        <h1>Add Schedule</h1>
    </div>
</header>

<div class="container">

<a href="view.php" class="back-link">
    <span class="material-symbols-outlined">arrow_back</span>
    Back to My Schedules
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
            <label>Title:</label>
            <input type="text" name="title" required>
        </div>

        <div class="form-group">
            <label>Description:</label>
            <textarea name="description"></textarea>
        </div>

        <div class="form-group">
            <label>Date:</label>
            <input type="date" name="schedule_date" required>
        </div>

        <div class="form-group">
            <label>Start Time:</label>
            <input type="time" name="start_time" required>
        </div>

        <div class="form-group">
            <label>End Time:</label>
            <input type="time" name="end_time">
        </div>

        <div class="form-group">
            <label>Location:</label>
            <input type="text" name="location">
        </div>

        <button type="submit" name="add_schedule">
            <span class="material-symbols-outlined">add_circle</span>
            Add Schedule
        </button>

    </form>

</div>

</div>

</body>
</html>
