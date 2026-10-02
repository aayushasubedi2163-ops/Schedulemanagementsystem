<?php

session_start();

include "../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";

    $result = $conn->query($sql);

    if ($result === false) {

        $error = "Database Error: " . $conn->error;

    } elseif ($result->num_rows == 0) {

        $error = "Email not found.";

    } else {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {

                header("Location: ../admin/index.php");
                exit();

            } else {

                header("Location: ../dashboard/index.php");
                exit();

            }

        } else {

            $error = "Incorrect password.";

        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Login - Schedule Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body class="auth-page">

<div class="auth-container">

    <div class="auth-card">

        <div class="auth-brand">
            <span class="material-symbols-outlined">event_available</span>
        </div>

        <h1>Welcome Back</h1>

        <p class="subtitle">
            Login to manage your schedules and tasks
        </p>


        <?php if ($error != "") { ?>

            <div class="auth-message auth-error">

                <span class="material-symbols-outlined" style="font-size:16px;vertical-align:-3px;">error</span>
                <?php echo htmlspecialchars($error); ?>

            </div>

        <?php } ?>


        <form method="POST">

            <div class="auth-form-group">

                <label>Email</label>

                <div class="auth-input-wrapper">

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >

                </div>

            </div>


            <div class="auth-form-group">

                <label>Password</label>

                <div class="auth-input-wrapper">

                    <input
                        type="password"
                        name="password"
                        id="loginPassword"
                        placeholder="Enter your password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        onclick="togglePassword('loginPassword', this)"
                    >
                        👁️
                    </button>

                </div>

            </div>


            <button
                type="submit"
                name="login"
                class="auth-button"
            >
                <span class="material-symbols-outlined">login</span>
                Login
            </button>

        </form>


        <div class="auth-link">

            Don't have an account?

            <a href="register.php">
                Register
            </a>

        </div>

    </div>

</div>


<script>

function togglePassword(inputId, button) {

    const input = document.getElementById(inputId);

    if (input.type === "password") {

        input.type = "text";
        button.textContent = "🙈";

    } else {

        input.type = "password";
        button.textContent = "👁️";

    }

}

</script>

</body>

</html>