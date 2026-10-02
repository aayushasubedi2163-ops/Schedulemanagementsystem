<?php

include "../config/database.php";

$error = "";
$success = "";

if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    /* Check password */

    if ($password !== $confirm_password) {

        $error = "Passwords do not match.";

    } else {


        /* Check existing email */

        $check_sql = "SELECT id FROM users WHERE email = '$email'";

        $check_result = $conn->query($check_sql);


        if (!$check_result) {

            $error = "Database Error: " . $conn->error;

        } elseif ($check_result->num_rows > 0) {

            $error = "An account with this email already exists.";

        } else {


            /* Hash password */

            $password_hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );


            /* Insert user */

            $sql = "INSERT INTO users
                    (name, email, password, role)
                    VALUES
                    ('$name', '$email', '$password_hash', 'user')";


            if ($conn->query($sql) === TRUE) {

                $success = "Registration successful! You can now login.";

            } else {

                $error = "Registration Error: " . $conn->error;

            }

        }

    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Register - Schedule Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body class="auth-page">

<div class="auth-container">

<div class="auth-card">

<div class="auth-brand">
    <span class="material-symbols-outlined">person_add</span>
</div>

<h1>Create Account</h1>

<p class="subtitle">
Create your Schedule Management account
</p>


<?php if ($error != "") { ?>

    <div class="auth-message auth-error">

        <span class="material-symbols-outlined" style="font-size:16px;vertical-align:-3px;">error</span>
        <?php echo htmlspecialchars($error); ?>

    </div>

<?php } ?>


<?php if ($success != "") { ?>

    <div class="auth-message auth-success">

        <span class="material-symbols-outlined" style="font-size:16px;vertical-align:-3px;">check_circle</span>
        <?php echo htmlspecialchars($success); ?>

    </div>

<?php } ?>


<form method="POST">


    <div class="auth-form-group">

        <label>Name</label>

        <div class="auth-input-wrapper">

            <input
                type="text"
                name="name"
                placeholder="Enter your name"
                required
            >

        </div>

    </div>


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
                id="registerPassword"
                placeholder="Create a password"
                minlength="6"
                required
            >

            <button
                type="button"
                class="password-toggle"
                onclick="togglePassword('registerPassword', this)"
            >
                👁️
            </button>

        </div>

    </div>


    <div class="auth-form-group">

        <label>Confirm Password</label>

        <div class="auth-input-wrapper">

            <input
                type="password"
                name="confirm_password"
                id="confirmPassword"
                placeholder="Confirm your password"
                minlength="6"
                required
            >

            <button
                type="button"
                class="password-toggle"
                onclick="togglePassword('confirmPassword', this)"
            >
                👁️
            </button>

        </div>

    </div>


    <button
        type="submit"
        name="register"
        class="auth-button"
    >
        <span class="material-symbols-outlined">person_add</span>
        Create Account
    </button>


</form>


<div class="auth-link">

    Already have an account?

    <a href="login.php">
        Login
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