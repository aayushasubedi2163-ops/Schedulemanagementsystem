<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];
$id = $_GET['id'];

$sql = "SELECT * FROM schedules
        WHERE id = '$id'
        AND user_id = '$user_id'";

$result = $conn->query($sql);

if ($result->num_rows != 1) {
    die("Schedule not found.");
}

$schedule = $result->fetch_assoc();
if (isset($_POST['update_schedule'])) {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $schedule_date = $_POST['schedule_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $location = $_POST['location'];

    $sql = "UPDATE schedules SET
            title = '$title',
            description = '$description',
            schedule_date = '$schedule_date',
            start_time = '$start_time',
            end_time = '$end_time',
            location = '$location'
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
    <title>Edit Schedule</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header class="app-bar">
    <div class="app-bar-title">
        <span class="material-symbols-outlined">edit_calendar</span>
        <h1>Edit Schedule</h1>
    </div>
</header>

<div class="container">

<a href="view.php" class="back-link">
    <span class="material-symbols-outlined">arrow_back</span>
    Back to My Schedules
</a>

<div class="section">

    <form method="POST">

        <div class="form-group">
            <label>Title:</label>

            <input type="text"
                   name="title"
                   value="<?php echo $schedule['title']; ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Description:</label>

            <textarea name="description"><?php echo $schedule['description']; ?></textarea>
        </div>

        <div class="form-group">
            <label>Date:</label>

            <input type="date"
                   name="schedule_date"
                   value="<?php echo $schedule['schedule_date']; ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Start Time:</label>

            <input type="time"
                   name="start_time"
                   value="<?php echo $schedule['start_time']; ?>"
                   required>
        </div>

        <div class="form-group">
            <label>End Time:</label>

            <input type="time"
                   name="end_time"
                   value="<?php echo $schedule['end_time']; ?>">
        </div>

        <div class="form-group">
            <label>Location:</label>

            <input type="text"
                   name="location"
                   value="<?php echo $schedule['location']; ?>">
        </div>

        <button type="submit" name="update_schedule">
            <span class="material-symbols-outlined">save</span>
            Update Schedule
        </button>

    </form>

</div>

</div>

</body>
</html>
